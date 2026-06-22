<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    /**
     * Standard success payload for the AJAX modal engine.
     * Controllers call this for XHR requests and fall back to a redirect otherwise.
     */
    protected function ajaxSuccess(string $message, ?string $redirect = null, array $extra = []): JsonResponse
    {
        return response()->json(array_merge([
            'success' => true,
            'message' => $message,
            'redirect' => $redirect,
        ], $extra));
    }

    /**
     * Standard error payload (business-rule failures that aren't validation errors).
     */
    protected function ajaxError(string $message, int $status = 422): JsonResponse
    {
        return response()->json(['success' => false, 'message' => $message], $status);
    }
}
