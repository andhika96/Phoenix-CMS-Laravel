<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Response;

class BaseApiController extends Controller
{
    protected $statusCode = 200;
    protected $recordLimit = 25;

    public function __construct()
    {
        // $this->middleware('cors');
        // $this->middleware('auth:api');
    }

    public function respond($data, $status = null, $headers = []): JsonResponse
    {
        return Response::json($data, $status ?? $this->statusCode, $headers);
    }

    public function respondDetail($message, $success = true, $extras = []): JsonResponse
    {
        $responseArray = [
            'success' => $success,
            'message' => $message,
            'status' => $this->statusCode,
        ];

        if (!empty($extras)) {
            $responseArray = array_merge($responseArray, $extras);
        }

        return $this->respond($responseArray);
    }

    public function setStatusCode($statusCode): BaseApiController
    {
        $this->statusCode = $statusCode;
        return $this;
    }

    public function generateResponse($statusCode, $extras = null, $message = '', $success = true): JsonResponse
    {
        return $this->setStatusCode($statusCode)->respondDetail($message, $success, $extras);
    }

    public function respondOK($extras = null, $message = 'Success!', $success = true): JsonResponse
    {
        return $this->generateResponse(200, $extras, $message, $success);
    }

    public function respondCreated($extras = null, $message = 'The resource has been created', $success = true): JsonResponse
    {
        return $this->generateResponse(201, $extras, $message, $success);
    }

    public function respondDeleted($extras = null, $message = 'The resource has been deleted', $success = false): JsonResponse
    {
        return $this->generateResponse(204, $extras, $message, $success);
    }

    public function respondRedirect(string $url, $status = 302, array $headers = []): JsonResponse
    {
        return $this->generateResponse($status, ['redirect_uri' => $url], null, true, $headers);
    }

    public function respondBadRequest($extras = null, $message = 'Bad request!', $success = false): JsonResponse
    {
        return $this->generateResponse(400, $extras, $message, $success);
    }

    public function respondUnauthorized($extras = null, $message = 'Unauthorized!', $success = false): JsonResponse
    {
        return $this->generateResponse(401, $extras, $message, $success);
    }

    public function respondForbidden($extras = null, $message = 'Forbidden!', $success = false): JsonResponse
    {
        return $this->generateResponse(403, $extras, $message, $success);
    }

    public function respondNotFound($extras = null, $message = 'Not found!', $success = true): JsonResponse
    {
        return $this->generateResponse(404, $extras, $message, $success);
    }

    public function respondInternalError($extras = null, $message = 'Internal error!', $success = false): JsonResponse
    {
        return $this->generateResponse(500, $extras, $message, $success);
    }

    public function paginateResponse(LengthAwarePaginator|Collection $paginatedData, $resource = null): array
    {
        if ($paginatedData instanceof LengthAwarePaginator) {
            if (is_subclass_of($resource, JsonResource::class)) {
                $items = $resource::collection($paginatedData->items());
            } else {
                $items = $paginatedData->items();
            }

            return [
                'data' => $items,
                'total' => $paginatedData->total(),
                'limit' => $paginatedData->perPage(),
                'last_page' => $paginatedData->lastPage(),
            ];
        } elseif ($paginatedData instanceof Collection) {
            if (is_subclass_of($resource, JsonResource::class)) {
                $items = $resource::collection($paginatedData->all());
            } else {
                $items = $paginatedData->all();
            }

            return [
                'data' => $items,
                'total' => $paginatedData->count(),
                'limit' => $paginatedData->count(),
                'last_page' => 1,
            ];
        }

        throw new \InvalidArgumentException('Invalid type for $paginatedData');
    }

    public function formatErrors($errors): array
    {
        $bag = [];

        foreach ($errors as $value) {
            $key = explode(' ', $value)[0];
            $bag[] = [
                'name' => $key,
                'message' => $value,
            ];
        }

        return $bag;
    }
}
