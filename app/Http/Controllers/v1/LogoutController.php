<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Throwable;

class LogoutController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        try {
            $tokenId = Str::before($request->bearerToken(), '|');
            $request->user()->tokens()->where('id', $tokenId)->delete();

            Auth::guard('web')->logout();

            return response()->json([
                'status'  => true,
                'message' => 'Logout successfully!',
            ])->setStatusCode(200);
        } catch (Throwable $e) {
            return response()->json([
                'status'  => true,
                'message' => $e->getMessage(),
            ])->setStatusCode(500);
        }
    }
}
