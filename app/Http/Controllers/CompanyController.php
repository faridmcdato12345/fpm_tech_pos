<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\StoreCompanyRequest;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return inertia('Company/Index',[
            'companies' => Company::all()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return inertia('Company/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $request->validate([
                'company_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
            ]);
            if($request->hasFile('company_logo')){
                $link = $request->file('company_logo')->store('images','public');
                $imageUrl = Storage::url($link);
            }
            Company::create([
                'name' => $request->name,
                'address' => $request->address,
                'mobile_number' => $request->mobile_number,
                'email' => $request->email,
                'company_logo' => $imageUrl
            ]);
            DB::commit();
            return redirect()->back()->with('toast','Successfully created company details');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with($e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Company $company)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Company $company)
    {
        return inertia('Company/Edit',[
            'company' => $company,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Company $company)
    {
        try {
            $company->update($request->all());
            return redirect()->back()->with('toast','Successfully updated company details');
        } catch (\Exception $e) {
            return redirect()->back()->with('toast', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Company $company)
    {
        $company->delete();
        return redirect()->back()->with('toast','Company details has been deleted.');
    }
}
