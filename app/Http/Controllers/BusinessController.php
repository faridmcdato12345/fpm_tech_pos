<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBusinessRequest;
use App\Models\Business;
use Illuminate\Http\Request;

class BusinessController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

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
    public function store(StoreBusinessRequest $request)
    {
        try {
            Business::create($request->validated());
            $estimatedDuration = 3000; // 3 seconds
            return redirect()->back()->with([
                'estimatedDuration' => $estimatedDuration
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with('toast', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Business $business)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Business $business)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreBusinessRequest $request, Business $business)
    {
        try {
            $business->update($request->validated());
            return redirect()->back();
        } catch (\Exception $e) {
            return redirect()->back()->with('toast', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Business $business)
    {
        $business->delete();
        return redirect()->back()->with('toast','Business has been deleted.');
    }
}
