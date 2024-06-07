<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\User\UserProfileResource;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\Passport;

class AuthController extends BaseApiController
{
    private UserService $userService;

    public function __construct(UserService $userService)
    {
        parent::__construct();
        $this->userService = $userService;
    }

    protected function attemptLogin(Request $request)
    {
        $field = filter_var($request->input('username'), FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        return Auth::attempt(
            [
                $field => $request->input('username'),
                'password' => $request->input('password'),
            ],
            $request->filled('remember')
        );
    }

    public function login(LoginRequest $request): JsonResponse
    {
        try {
            if ($this->attemptLogin($request)) {
                $user = $request->user();
                $token = $user->createToken('api')->accessToken;

                $responseData = array(
                    'user' => new UserProfileResource($user),
                    'token' => $token
                );

                $response = $this->respondOK($responseData, 'Berhasi masuk');
            } else {
                $responseData = array('password' => ['Password dan username tidak cocok']);
                $response = $this->respondUnauthorized(
                    array('errors' => $responseData)
                );
            }
        } catch (\Throwable $th) {
            $response = $this->respondInternalError(
                array('errors' => $th->getMessage())
            );
        } finally {
            return $response;
        }
    }

    public function register(RegisterRequest $request): JsonResponse
    {
        try {
            $user = $this->userService->create($request->all());
            $token = $user->createToken('api')->accessToken;

            $responseData = array(
                'user' => new ProfileResource($user),
                'token' => $token
            );

            return $this->respondCreated($responseData, 'Berhasil terdaftar');
        } catch (\Throwable $th) {
            return $this->respondInternalError(['errors' => $th->getMessage()]);
        }
    }

    public function logout(Request $request): JsonResponse
    {
        try {
            $user = $request->user();
            $user->token()->revoke();

            $response = $this->respondOK(['message' => 'Berhasil logout']);
        } catch (\Throwable $th) {
            $response = $this->respondInternalError(['errors' => $th->getMessage()]);
        } finally {
            return $response;
        }
    }

    public function validateToken(Request $request): JsonResponse
    {
        try {
            $isValid = auth('api')->check();
            if ($isValid) {
                $response = $this->respondOK(['message' => 'Token valid']);
            } else {
                $response = $this->respondUnauthorized(['message' => 'Token tidak valid/expired']);
            }
        } catch (\Throwable $th) {
            $response = $this->respondInternalError(['errors' => $th->getMessage()]);
        } finally {
            return $response;
        }
    }
}
