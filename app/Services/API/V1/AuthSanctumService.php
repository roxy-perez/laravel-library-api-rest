<?php

namespace App\Services\API\V1;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use App\Services\API\V1\ApiResponseService;
use App\Contracts\API\Auth\AuthServiceInterface;

class AuthSanctumService implements AuthServiceInterface
{
    public function login(array $credentials): JsonResponse
    {
        if (!auth()->attempt([
            'email' => data_get($credentials, 'email'),
            'password' => data_get($credentials, 'password')
        ])) {
            return ApiResponseService::error('Unauthorized');
        }

        $token = auth()
            ->user()
            ->createToken(data_get($credentials, 'device_name'))
            ->plainTextToken;

        return ApiResponseService::success([
            'token' => $token,
            'token_type' => 'Bearer'
        ]);
    }

    /*************  ✨ Codeium Command ⭐  *************/
    /**
     * Registers a new user and generates an authentication token.
     *
     * @param array $data An array containing user registration details.
     * @return JsonResponse A JSON response containing the authentication token and token type.
     */

    /******  51d883ca-d53b-48fb-b565-e125995a8756  *******/
    public function register(array $data): JsonResponse
    {
        $user = User::create($data);
        $token = $user->createToken(data_get($data, 'device_name'))->plainTextToken;

        return ApiResponseService::success([
            'token' => $token,
            'token_type' => 'Bearer'
        ]);
    }

    public function logout(): JsonResponse
    {
        auth()->user()->tokens()->delete();

        return ApiResponseService::success([
            null,
            'Logged out successfully'
        ]);
    }
}
