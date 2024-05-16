<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\VerifyLoginRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Throwable;

class LoginController extends Controller
{
    public function verify(VerifyLoginRequest $request): JsonResponse
    {
        try {
            $user = User::query()->where('email', $request->email)->first();
            if (!$user || !Hash::check($request->password, $user->password)) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Your email or password is incorrect!',
                ])->setStatusCode(401);
            }

            Auth::login($user);

            $access_token = $user->createToken(config('app.name'))->plainTextToken;

            return (new UserResource($user))->additional([
                'status'       => true,
                'message'      => 'Login successfully!',
                'token_type'   => 'Bearer',
                'access_token' => $access_token,
            ])->response()->setStatusCode(200);
        } catch (Throwable $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage(),
            ])->setStatusCode(500);
        }
    }
}
