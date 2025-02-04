<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Category $category, Request $request)
    {
        $query = Category::query();
        if($request->has('name')){
            $query->where('name','like','%' . $request->query('name') . '%');
        }
        $limit = $request->has('query') ? $request->query('query'): 10;
        $categories = $query->paginate(intval($limit))->withQueryString();
        return Inertia::render('Category/Index',[
            'user' => auth()->user(),
            'queryLimit' => intval($limit),
            'queryName' => $request->has('name') ? $request->query('name') : null,
            'categories' => CategoryResource::collection($categories)
        ]);
        return Inertia::render('Category/Home',[
            'categories' => Category::all()
        ]);
    }

    public function create()
    {
        return inertia('Category/Create');
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
        try {
            Category::create($request->validated());
            return redirect()->back();
        } catch (\Exception $e) {
            return redirect()->back()->with('toast', $e->getMessage());
        }
        
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        return response()->json($category);
        
    }
    public function edit(Category $category)
    {
        return inertia('Category/Edit', [
            'category' => $category
        ]);
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        try {
            $category->update($request->validated());
            return redirect()->back();
        } catch (\Exception $e) {
            return redirect()->back()->with('toast', $e->getMessage());
        }
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->back()->with('toast','Category has been deleted.');
    }
}
