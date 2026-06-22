<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    protected AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function show()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.login');
    }

    public function authenticate(LoginRequest $request): JsonResponse
    {
        try {
            $result = $this->authService->authenticate(
                $request->validated()['email'],
                $request->validated()['password']
            );

            if ($result['success']) {
                return response()->json([
                    'success' => true,
                    'message' => __('app.auth.login_successful'),
                    'user' => $result['user'],
                    'redirect' => route('dashboard'),
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => __('app.auth.invalid_credentials'),
            ], 401);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('app.error'),
            ], 500);
        }
    }
}
