<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $categories = Category::query()->filter([
                'search' => $request->search,
            ])->paginate($request->per_page ?? 15)->withQueryString();

            return (CategoryResource::collection($categories))->additional([
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
    public function store(StoreCategoryRequest $request): JsonResponse
    {
        try {
            if (Category::where('name', $request->name)->exists()) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Category already exists!',
                ])->setStatusCode(400);
            }

            $category       = new Category();
            $category->name = $request->name;
            $category->save();

            return (new CategoryResource($category))->additional([
                'status'  => true,
                'message' => 'Category created successfully!',
            ])->response()->setStatusCode(201);
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
    public function show(Category $category): JsonResponse
    {
        try {
            return (new CategoryResource($category))->additional([
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
    public function edit(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, Category $category): JsonResponse
    {
        try {
            if ($request->name != $category->name && Category::where('name', $request->name)->exists()) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Category already exists!',
                ])->setStatusCode(400);
            }

            $category->name = $request->name;
            $category->save();

            return (new CategoryResource($category))->additional([
                'status'  => true,
                'message' => 'Category updated successfully!',
            ])->response()->setStatusCode(code: 200);
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
    public function destroy(Category $category): JsonResponse
    {
        try {
            $category->delete();

            return response()->json([
                'status'  => true,
                'message' => 'Category deleted successfully!',
            ])->setStatusCode(200);
        } catch (Throwable $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage(),
            ])->setStatusCode(500);
        }
    }
}
