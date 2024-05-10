<?php

namespace App\Services;

use App\Contracts\IOrderService;
use App\Enums\OrderStatusEnum;
use App\Enums\QueryAcceptedComparatorEnum;
use App\Mail\OrderUpgrade as MailOrderUpgrade;
use App\Models\OrderUpgrade;
use App\Models\UserBoosterQuota;
use App\Models\UserPublishQuota;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;

use Illuminate\Support\Facades\Storage;
use Throwable;

class OrderUpgradeService implements IOrderService
{
    public function create(array $attr): ?OrderUpgrade
    {
        // Begin a database transaction
        DB::beginTransaction();

        try {
            // Generate the order ID prefix
            $date = date('dmY');
            $orderIdPrefix = "Handover_{$date}_";
            $tempId = uniqid($orderIdPrefix);

            // Create a new instance of OrderUpgrade with the provided attributes
            $data = new OrderUpgrade($attr);
            $data->order_id = $tempId;

            // Save the new order to the database within the transaction
            if ($data->save()) {
                // Get the ID of the newly created record
                $id = $data->id;

                // Update the order ID with the appended record ID
                $order_id = "{$orderIdPrefix}{$id}";

                // Assign the updated order ID to the model
                $data->order_id = $order_id;

                // Save the model again to update the order ID in the database
                $data->save();

                // Send email
                Mail::to($data->user)->send(new MailOrderUpgrade($data));

                // Commit the transaction
                DB::commit();

                return $data;
            }

            // Rollback the transaction if saving fails
            DB::rollBack();
            throw new Exception("Failed to save new order to database");
        } catch (Exception $e) {
            // Rollback the transaction in case of an exception
            DB::rollBack();
            throw $e;
        }
    }


    public function findById($id): ?OrderUpgrade
    {
        $data = OrderUpgrade::find($id);

        if (!$data) {
            $data = OrderUpgrade::where("order_id", $id)->first();
        }

        return $data;
    }

    public function findByOrderId($id): ?OrderUpgrade
    {
        return OrderUpgrade::where("order_id", $id)->first();
    }

    public function latestUserOrder($user_id): ?OrderUpgrade
    {
        return OrderUpgrade::where('user_id', $user_id)
            ->latest()
            ->first();
    }

    public function findByIndexes(
        array $indexes,
        bool $any,
        $limit,
        array $orderBy,
        QueryAcceptedComparatorEnum $comparator = QueryAcceptedComparatorEnum::LIKE
    ) {
        $table = (new OrderUpgrade)->getTable();
        $columns = Schema::getColumnListing($table);
        $query = OrderUpgrade::query();

        foreach ($indexes as $column => $value) {
            if ($comparator == QueryAcceptedComparatorEnum::LIKE) {
                $value = "%{$value}%";
            }

            if (in_array($column, $columns)) {
                $any
                    ? $query->orWhere($column, $comparator->value, $value)
                    : $query->where($column, $comparator->value, $value);
            }
        }

        if (!empty($orderBy)) {
            foreach ($orderBy as $orderByColumn) {
                $orderByArray = explode(' ', $orderByColumn);
                $orderByColumn = $orderByArray[0];
                $orderByDirection = isset($orderByArray[1]) ? $orderByArray[1] : 'ASC';

                if (in_array($orderByColumn, $columns)) {
                    $query->orderBy($orderByColumn, $orderByDirection);
                }
            }
        }

        if ($limit == -1) {
            $results = $query->get();
        } else {
            $perPage = $limit;
            $currentPage = request()->get('page', 1);
            $results = $query->paginate($perPage, ['*'], 'page', $currentPage);
        }

        return $results;
    }

    /**
     * List all user.
     */
    public function list(): Collection
    {
        return OrderUpgrade::all();
    }

    public function patchStatus(OrderUpgrade $order, OrderStatusEnum $status, array $attr): OrderUpgrade
    {
        // Begin a database transaction
        DB::beginTransaction();
        try {
            $order->order_complete_date = $status == OrderStatusEnum::SUCCESS ? date("Y-m-d H:i:s") : null;
            $order->bank = $attr['bank'] ?? null;
            $order->payment_type = $attr['payment_type'] ?? null;
            $order->payment_option_type = $attr['payment_option_type'] ?? null;
            $order->order_status = $status->value;
            $order->save();

            if ($order->order_status == OrderStatusEnum::SUCCESS->value) {
                $user = $order->user;
                
                if ($order->package_id) {
                    // Set user as premium
                    $user->is_premium = true;

                    // Synchronize roles (using the addon role_id or 'vip' role if addon is not set)
                    $role = $order->addon ? $order->addon->role_id : 'vip';
                    $user->syncRoles([$role]);

                    // Save the changes to the user model
                    $user->save();

                    // Create a new UserQuota instance
                    $newQuota = new UserPublishQuota([
                        'package_id' => $order->package->id ?? null,
                        'expired_at' => $order->package->duration ? Carbon::now()->addDays($order->package->duration)->toDateTimeString() : null,
                        // 'expired_at' => Carbon::now()->addMinutes(5)->toDateTimeString(),
                        'type_id' => $order->package->type_id ?? null,
                        'quota' => $order->package->quota ?? null
                    ]);


                    // Save the new quota through the relationship
                    $user->quotas('publish')->save($newQuota);
                }

                if ($order->booster_id) {
                    $newBoosterQuota = new UserBoosterQuota([
                        'booster_id' => $order->booster->id ?? null,
                        // 'expired_at' => Carbon::now()->addMinutes(5)->toDateTimeString(),
                        'expired_at' => $order->booster->duration ? Carbon::now()->addDays($order->booster->duration)->toDateTimeString() : null,
                        // 'type_id' => $order->booster->type_id ?? null
                    ]);

                    // Log::debug(Carbon::now()->addMinutes(5)->toDateTimeString());
                    // Log::debug(json_encode($newBoosterQuota));

                    // Save the new quota through the relationship
                    $user->quotas('booster')->save($newBoosterQuota);
                    // Log::debug(json_encode($user->quotas('booster')));
                }
            } elseif ($order->order_status == OrderStatusEnum::FAILED->value) {
                $user = $order->user;
                $user->is_premium = false;
                $user->syncRoles(['basic']);
                $user->save();
            }

            Mail::to($user)->send(new MailOrderUpgrade($order));
            DB::commit();
            return $order;
        } catch (Throwable $th) {
            // Rollback the transaction in case of an exception
            DB::rollBack();
            throw $th;
        }
    }

    public function delete(OrderUpgrade $order): ?bool
    {
        return $order->delete();
    }

    public function generateInvoice(OrderUpgrade $order)
    {
        $pdf = Pdf::loadView('pdf.invoice', compact('order'));
        $pdf->setPaper('a4', 'landscape');

        return $pdf->download("invoice_{$order->order_id}.pdf");
    }

    public function totalOrder($status = "all"): int
    {
        $data = OrderUpgrade::query();
        if ($status == "success") {
            $data = $data->where('order_status', OrderStatusEnum::SUCCESS->value);
        } elseif ($status == "pending") {
            $data = $data->where('order_status', OrderStatusEnum::PENDING->value);
        } elseif ($status == "failed") {
            $data = $data->where("order_status", OrderStatusEnum::EXPIRED->value)
                ->orWhere("order_status", OrderStatusEnum::FAILED->value);
        }

        return $data->count();
    }

    public function totalEarning($status = "all"): int
    {
        $data = OrderUpgrade::query();
        if ($status == "success") {
            $data = $data->where('order_status', OrderStatusEnum::SUCCESS->value);
        } elseif ($status == "pending") {
            $data = $data->where('order_status', OrderStatusEnum::PENDING->value);
        } elseif ($status == "failed") {
            $data = $data->where("order_status", OrderStatusEnum::EXPIRED->value)
                ->orWhere("order_status", OrderStatusEnum::FAILED->value)
                ->count();
        }

        return $data->sum('order_total');
    }
}
