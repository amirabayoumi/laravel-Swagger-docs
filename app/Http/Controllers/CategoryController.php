<?php

namespace App\Http\Controllers;


use App\Models\Category;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     *
     */
    public function index()
    {
        $categories = Category::all();
        return view('categories.index', compact('categories'));
    }

    /**
     *
     */
    public function create()
    {
        return view('categories.create');
    }

    /**
     *
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
        ]);

        $validated['user_id'] = Auth::id();

        // Create the category
        $category = Category::create($validated);

        return redirect()->route('categories')
            ->with('success', 'Category created successfully.');
    }

    /**
     *
     */
    public function show(Category $category)
    {
        return view('categories.show', compact('category'));
    }

    /**
     *
     */
    public function edit(Category $category)
    {
        return view('categories.edit', compact('category'));
    }

    /**
     *
     */
    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
        ]);

        // Update the category
        $category->update($validated);

        return redirect()->route('categories')
            ->with('success', 'Category updated successfully.');
    }

    /**
     *
     */
    public function destroy(Category $category)
    {
        $category->delete();

        return redirect()->route('categories')
            ->with('success', 'Category deleted successfully.');
    }
}
