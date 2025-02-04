<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBrandRequest;
use Inertia\Inertia;
use App\Models\Brand;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Resources\BrandResource;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Category $brand, Request $request)
    {
        $query = Brand::query();
        if($request->has('name')){
            $query->where('name','like','%' . $request->query('name') . '%');
        }
        $limit = $request->has('query') ? $request->query('query'): 10;
        $brands = $query->paginate(intval($limit))->withQueryString();
        return Inertia::render('Brand/Index',[
            'user' => auth()->user(),
            'queryLimit' => intval($limit),
            'queryName' => $request->has('name') ? $request->query('name') : null,
            'brands' => BrandResource::collection($brands)
        ]);
    }

    public function create()
    {
        return inertia('Category/Create');
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBrandRequest $request)
    {
        try {
            Brand::create($request->validated());
            return redirect()->back();
        } catch (\Exception $e) {
            return redirect()->back()->with('toast', $e->getMessage());
        }

    }

    /**
     * Display the specified resource.
     */
    public function show(Brand $brand)
    {
        return response()->json($brand);

    }
    public function edit(Brand $brand)
    {
        return inertia('Category/Edit', [
            'category' => $brand
        ]);
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(StoreBrandRequest $request, Brand $brand)
    {
        try {
            $brand->update($request->all());
            return redirect()->back();
        } catch (\Exception $e) {
            return redirect()->back()->with('toast', $e->getMessage());
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Brand $brand)
    {
        $brand->delete();
        return redirect()->back()->with('toast','Brand has been deleted.');
    }
}
