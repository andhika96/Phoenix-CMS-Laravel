<?php

namespace App\Http\Controllers\Api\v1;

use App\Enums\QueryAcceptedComparatorEnum;
use App\Http\Controllers\Api\BaseApiController;
use App\Http\Resources\User\UserListResource;
use App\Http\Resources\User\UserProfileResource;
use App\Models\Account;
use App\Services\UserService;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UserController extends BaseApiController
{
    public UserService $userService;

    public function __construct()
    {
        $this->userService = new UserService(new Account());
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $indexes = $request->all();
            $orderByColumns = $indexes['orderByColumns'] ?? [];
            $orderBy = $orderByColumns ? explode(",", $orderByColumns) : [];
            $any = $request->input('any', true);
            $limit = $request->input('limit', 10);
            $comparator = $request->input('comparator', 'like');
            $qcomparator = QueryAcceptedComparatorEnum::tryFrom($comparator) ?? QueryAcceptedComparatorEnum::EQUAL;

            if (isset($indexes['ignoreIds'])) {
                $indexes['ignore'] = explode(',', $indexes['ignoreIds']);
                unset($indexes['ignoreIds']);
            }

            $results = $this->userService->findByIndexes(
                $indexes,
                $any,
                $limit,
                $orderBy,
                $qcomparator
            );

            $formattedResponse = $this->paginateResponse($results, UserListResource::class);

            return $this->setStatusMsg($formattedResponse['total'] ? 'success' : 'failed')
                ->respondOK($formattedResponse, $formattedResponse['total'] ? 'Data found' : 'No data found');
        } catch (\Throwable $e) {
            return $this->respondInternalError(null, $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // Log::debug(json_encode($request));
            $attrData = $request->all();
            $data = $this->userService->create($attrData);

            $responseData = [
                'data' => new UserProfileResource($data)
            ];

            $response = $this->respondCreated($responseData);
        } catch (\Throwable $th) {
            $response = $this->respondInternalError(['errors' => $th->getMessage()]);
        } finally {
            return $response;
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $idOrSlug)
    {
        try {
            $data = $this->userService->findById($idOrSlug);
            throw_if(empty($data), new NotFoundHttpException(404));

            $data = new UserProfileResource($data);

            $response = $this->setStatusMsg("success")->respondOK(array(
                'data' => $data
            ));
        } catch (NotFoundHttpException $th) {
            $response = $this->setStatusMsg("failed")->respondForbidden();
        } catch (\Throwable $th) {
            $response = $this->setStatusMsg("failed")->respondInternalError(null, $th->getMessage());
        } finally {
            return $response;
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $idOrSlug)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $idOrSlug)
    {
        try {
            $data = $this->userService->update($request->all(), $idOrSlug);

            $response = $this->respondOK(array(
                'data' => new UserProfileResource($data)
            ));
        } catch (\Throwable $th) {
            $response = $this->respondInternalError(null, $th->getMessage());
        } finally {
            return $response;
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $idOrSlug)
    {
        try {
            $data = $this->userService->findById($idOrSlug);
            // $user = $request->user();

            throw_if(!$data, new NotFoundHttpException("data not found"));

            $data->delete();
            $response = $this->respondOK();

        } catch (\Throwable $th) {
            $response = $this->respondInternalError(null, $th->getMessage());
        } finally {
            return $response;
        }
    }
}
