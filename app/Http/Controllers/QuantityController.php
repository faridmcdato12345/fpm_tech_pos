<?php

namespace App\Http\Controllers;

use App\Models\Quantity;
use Illuminate\Http\Request;

class QuantityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Quantity $quantity)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Quantity $quantity)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Quantity $quantity)
    {
        $query = Quantity::where('id',$request->product_id)->update(['value' => $request->value]);
        return redirect()->back()->with('toast','Success! Product has been edited.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Quantity $quantity)
    {
        //
    }
}
