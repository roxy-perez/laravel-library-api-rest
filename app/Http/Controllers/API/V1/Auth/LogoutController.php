<?php

namespace App\Http\Controllers\API\V1\Auth;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Contracts\API\Auth\AuthServiceInterface;

class LogoutController extends Controller
{
    public function __construct(private readonly AuthServiceInterface $authService)
    {
        parent::__construct();
    }

    public function __invoke(): JsonResponse
    {
        return $this->authService->logout();
    }
}
