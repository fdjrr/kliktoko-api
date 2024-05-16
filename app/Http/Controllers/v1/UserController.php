<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Throwable;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $users = User::query()->filter([
                'search' => $request->search,
            ])->paginate($request->per_page ?? 15)->withQueryString();

            return (UserResource::collection($users))->additional([
                'status' => true,
            ])->response()->setStatusCode(200);
        } catch (Throwable $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage(),
            ])->setStatusCode(500);
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
    public function store(StoreUserRequest $request): JsonResponse
    {
        try {
            if (User::query()->where('email', $request->email)->exists()) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Email already exists!',
                ])->setStatusCode(400);
            }

            $user           = new User();
            $user->name     = $request->name;
            $user->email    = $request->email;
            $user->password = Hash::make($request->password);
            $user->save();

            return (new UserResource($user))->additional([
                'status'  => true,
                'message' => 'User updated successfully!',
            ])->response()->setStatusCode(200);
        } catch (Throwable $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage(),
            ])->setStatusCode(500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user): JsonResponse
    {
        try {
            return (new UserResource($user))->additional([
                'status' => true,
            ])->response()->setStatusCode(200);
        } catch (Throwable $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage(),
            ])->setStatusCode(500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user): JsonResponse
    {
        try {
            if ($request->email != $user->email && User::query()->where('email', $request->email)->exists()) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Email already exists!',
                ])->setStatusCode(400);
            }

            $user->name     = $request->name;
            $user->email    = $request->email;
            $user->password = $request->password ? Hash::make($request->password) : $user->password;
            $user->save();

            return (new UserResource($user))->additional([
                'status'  => true,
                'message' => 'User updated successfully!',
            ])->response()->setStatusCode(200);
        } catch (Throwable $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage(),
            ])->setStatusCode(500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user): JsonResponse
    {
        try {
            $user->delete();

            return response()->json([
                'status'  => true,
                'message' => 'User deleted successfully!',
            ])->setStatusCode(200);
        } catch (Throwable $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage(),
            ])->setStatusCode(500);
        }
    }
}
