<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUnitRequest;
use App\Http\Resources\UnitResource;
use App\Models\Unit;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UnitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Unit $unit, Request $request)
    {
        $query = Unit::query();
        if($request->has('name')){
            $query->where('name','like','%' . $request->query('name') . '%');
        }
        $limit = $request->has('query') ? $request->query('query'): 10;
        $units = $query->paginate(intval($limit))->withQueryString();
        return Inertia::render('Unit/Index',[
            'user' => auth()->user(),
            'queryLimit' => intval($limit),
            'queryName' => $request->has('name') ? $request->query('name') : null,
            'units' => UnitResource::collection($units)
        ]);
    }

    public function create(Request $request)
    {
        return inertia('Unit/Create');
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUnitRequest $request)
    {
        try {
            Unit::create($request->validated());
            return redirect()->back();
        } catch (\Exception $e) {
            return redirect()->back()->with('toast', $e->getMessage());
        }
        
    }

    /**
     * Display the specified resource.
     */
    public function show(Unit $unit)
    {
        return response()->json($unit);
    }

    public function edit(Unit $unit)
    {
        return inertia('Unit/Edit', [
            'unit' => $unit
        ]);
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(StoreUnitRequest $request, Unit $unit)
    {
        try {
            $unit->name = $request->input('name');
            $unit->save();
            return redirect()->route('unit.index')->with('toast',[
                'message' => 'Success! Unit has been updated',
                'status' => true
            ]);
        } catch (\Exception $e) {
            return  redirect()->back()->with('toast', $e->getMessage());
        }
        
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Unit $unit)
    {
        $unit->delete();
        return redirect()->back()->with('toast','Unit has been deleted.');
    }
}
