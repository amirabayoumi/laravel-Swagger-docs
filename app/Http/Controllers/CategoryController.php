<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use OpenApi\Annotations as OA;


/**
 * @OA\Schema(
 *     schema="CategoryRequest",
 *     required={"name"},
 *     @OA\Property(property="name", type="string", example="Books"),
 *     @OA\Property(property="description", type="string", example="All kinds of books")
 * )
 */

 /**
 * @OA\Parameter(
 *     parameter="category",
 *     name="category",
 *     in="path",
 *     required=true,
 *     description="ID of the category",
 *     @OA\Schema(type="integer", example=1)
 * )
 */
class CategoryController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/categories",
     *     summary="Get all categories",
     *     tags={"Categories"},
     *     @OA\Response(response=200, description="All categories")
     * )
     */
    public function index()
    {
        return CategoryResource::collection(Category::all());
    }

    /**
     * @OA\Get(
     *     path="/categories/create",
     *     summary="Show form to create a new category",
     *     tags={"Categories"},
     *     @OA\Response(response=200, description="Create category form")
     * )
     */
    public function create()
    {
        return view('categories.create');
    }

    /**
     * @OA\Post(
     *     path="/categories",
     *     summary="Create a new category",
     *     tags={"Categories"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/CategoryRequest")
     *     ),
     *     @OA\Response(response=201, description="Category created successfully")
     * )
     */
    public function store(CategoryRequest $request)
    {
        Category::create($request->validated());

        return redirect()->route('categories.index')->with('success', 'Category created successfully.');
    }

    /**
     * @OA\Get(
     *     path="/categories/{category}",
     *     summary="Show a category",
     *     tags={"Categories"},
     *     @OA\Parameter(ref="#/components/parameters/category"),
     *     @OA\Response(response=200, description="Category details")
     * )
     */
    public function show(Category $category)
    {
        return view('categories.show', compact('category'));
    }

    /**
     * @OA\Get(
     *     path="/categories/{category}/edit",
     *     summary="Show form to edit a category",
     *     tags={"Categories"},
     *     @OA\Parameter(ref="#/components/parameters/category"),
     *     @OA\Response(response=200, description="Edit category form")
     * )
     */
    public function edit(Category $category)
    {
        return view('categories.edit', compact('category'));
    }

    /**
     * @OA\Patch(
     *     path="/categories/{category}",
     *     summary="Update an existing category",
     *     tags={"Categories"},
     *     @OA\Parameter(ref="#/components/parameters/category"),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/CategoryRequest")
     *     ),
     *     @OA\Response(response=200, description="Category updated successfully")
     * )
     */
    public function update(CategoryRequest $request, Category $category)
    {
        $category->update($request->validated());

        return redirect()->route('categories.index')->with('success', 'Category updated successfully.');
    }

    /**
     * @OA\Delete(
     *     path="/categories/{category}",
     *     summary="Delete a category",
     *     tags={"Categories"},
     *     @OA\Parameter(ref="#/components/parameters/category"),
     *     @OA\Response(response=200, description="Category deleted successfully")
     * )
     */
    public function destroy(Category $category)
    {
        $category->delete();

        return redirect()->route('categories.index')->with('success', 'Category deleted successfully.');
    }
}

