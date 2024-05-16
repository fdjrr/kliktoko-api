<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Category;
use App\Models\Product;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $products = Product::query()->with([
                'user',
                'category',
            ])->filter([
                        'search'      => $request->search,
                        'category_id' => $request->category_id,
                    ])->paginate($request->per_page ?? 15)->withQueryString();

            return (ProductResource::collection($products))->additional([
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
    public function store(StoreProductRequest $request): JsonResponse
    {
        try {
            $category = Category::find($request->category_id);
            if (!$category) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Category not found!',
                ])->setStatusCode(400);
            }

            $image = $request->file('image')->store('products');

            $product              = new Product();
            $product->name        = $request->name;
            $product->price       = $request->price;
            $product->description = $request->description;
            $product->image       = $image;
            $product->category_id = $category->id;
            $product->save();

            return (new ProductResource($product->load([
                'user',
                'category',
            ])))->additional([
                        'status'  => true,
                        'message' => 'Product created successfully!',
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
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, Product $product): JsonResponse
    {
        try {
            $category = Category::find($request->category_id);
            if (!$category) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Category not found!',
                ])->setStatusCode(400);
            }

            if ($request->hasFile('image')) {
                $image = $request->file('image')->store('products');
            }

            $product->name        = $request->name;
            $product->price       = $request->price;
            $product->description = $request->description;
            $product->image       = $request->hasFile('image') ? $image : $product->image;
            $product->category_id = $category->id;
            $product->save();

            return (new ProductResource($product->load([
                'user',
                'category',
            ])))->additional([
                        'status'  => true,
                        'message' => 'Product updated successfully!',
                    ])->response()->setStatusCode(200);
        } catch (Throwable $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage(),
                'errors'  => $e->getLine(),
            ])->setStatusCode(500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product): JsonResponse
    {
        try {
            $product->delete();

            return response()->json([
                'status'  => true,
                'message' => 'Product deleted successfully!',
            ])->setStatusCode(200);
        } catch (Throwable $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage(),
            ])->setStatusCode(500);
        }
    }
}
