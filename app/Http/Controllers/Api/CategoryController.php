<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use OpenApi\Annotations as OA;
use App\Http\Resources\CategoryResource;
use App\Http\Requests\CategoryRequest;

/**
 * @OA\Tag(
 *     name="Categories",
 *     description="API Endpoints for managing categories"
 * )
 */
class CategoryController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/categories",
     *     summary="Get all categories",
     *     tags={"Categories"},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Category")
     *         )
     *     )
     * )
     */
    public function index()
    {
        $categories = Category::all();
        return CategoryResource::collection($categories);
    }

    /**
     * @OA\Post(
     *     path="/api/categories",
     *     summary="Create a new category",
     *     tags={"Categories"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/CategoryRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Category created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Category")
     *     ),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function store(CategoryRequest $request)
    {
        $category = Category::create($request->validated());
        return new CategoryResource($category);
    }

    /**
     * @OA\Get(
     *     path="/api/categories/{category}",
     *     summary="Get a category",
     *     tags={"Categories"},
     *     @OA\Parameter(
     *         name="category",
     *         in="path",
     *         description="ID of category to return",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Category details",
     *         @OA\JsonContent(ref="#/components/schemas/Category")
     *     ),
     *     @OA\Response(response=404, description="Category not found")
     * )
     */
    public function show(Category $category)
    {
        return new CategoryResource($category);
    }

    /**
     * @OA\Put(
     *     path="/api/categories/{category}",
     *     summary="Update an existing category",
     *     tags={"Categories"},
     *     @OA\Parameter(
     *         name="category",
     *         in="path",
     *         description="ID of category to update",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/CategoryRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Category updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Category")
     *     ),
     *     @OA\Response(response=404, description="Category not found"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function update(CategoryRequest $request, Category $category)
    {
        $category->update($request->validated());
        return new CategoryResource($category);
    }

    /**
     * @OA\Delete(
     *     path="/api/categories/{category}",
     *     summary="Delete a category",
     *     tags={"Categories"},
     *     @OA\Parameter(
     *         name="category",
     *         in="path",
     *         description="ID of category to delete",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=204, description="Category deleted successfully"),
     *     @OA\Response(response=404, description="Category not found")
     * )
     */
    public function destroy(Category $category)
    {
        $category->delete();
        return response()->json(null, 204);
    }
}
