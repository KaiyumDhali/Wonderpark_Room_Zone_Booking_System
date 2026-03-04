<?php

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;

use App\Models\CompanySetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Validator;
use DB;

class CompanySettingController extends Controller
{

    // function __construct()
    // {
    //     $this->middleware('permission:read company setting|write company setting|create company setting', ['only' => ['index', 'show']]);
    //     $this->middleware('permission:create company setting', ['only' => ['create', 'store']]);
    //     $this->middleware('permission:write company setting', ['only' => ['edit', 'update', 'destroy']]);
    // }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $companySettings = CompanySetting::where('status', 1)->get();

        // dd($companySettings);

        return response()->json([
            'status' => true,
            'data' => $companySettings
        ]);

        // $companySettings = CompanySetting::get();
        // return view('pages.employee.company.index', compact('companySettings'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.employee.company.add');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'company_name' => ['required', 'string', 'max:255'],
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        $input = $request->all();

        if ($request->hasfile('company_logo_one')) {
            $image = $request->file('company_logo_one');
            $name = date('d-m-Y-H-i-s') . '_' . $image->getClientOriginalName();
            $image_path = $image->storeAs('public/images/company', $name);
            $input['company_logo_one'] = $image_path;
        }
        if ($request->hasfile('company_logo_two')) {
            $image = $request->file('company_logo_two');
            $name = date('d-m-Y-H-i-s') . '-' . $image->getClientOriginalName();
            $image_path = $image->storeAs('public/images/company', $name);
            $input['company_logo_two'] = $image_path;
        }
        if ($request->hasfile('company_footer_logo')) {
            $image = $request->file('company_footer_logo');
            $name = date('d-m-Y-H-i-s') . '-' . $image->getClientOriginalName();
            $image_path = $image->storeAs('public/images/company', $name);
            $input['company_footer_logo'] = $image_path;
        }
        // $input['created_by'] = $created_by;

        // dd($input);
        CompanySetting::create($input);
        return redirect()->route('emp_company.index')->with([
            'message' => 'successfully created !',
            'alert-type' => 'success'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $companySetting = CompanySetting::find($id);
        return view('pages.employee.company.show', compact('companySetting'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $companySetting = CompanySetting::find($id);
        return view('pages.employee.company.update', compact('companySetting'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $companySetting = CompanySetting::find($id);

        $validator = Validator::make($request->all(), [
            'company_name' => ['required', 'string', 'max:255'],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $input = $request->all();

        // if ($request->hasfile('company_logo_one')) {
        //     $image = $request->file('company_logo_one');
        //     $name = date('d-m-Y-H-i-s') . '_' . $image->getClientOriginalName();
        //     $image_path = $image->storeAs('public/images/company', $name);
        //     $input['company_logo_one'] = $image_path;
        // }

        // if ($request->hasfile('company_logo_two')) {
        //     $image = $request->file('company_logo_two');
        //     $name = date('d-m-Y-H-i-s') . '-' . $image->getClientOriginalName();
        //     $image_path = $image->storeAs('public/images/company', $name);
        //     $input['company_logo_two'] = $image_path;
        // }

        // if ($request->hasfile('company_footer_logo')) {
        //     $image = $request->file('company_footer_logo');
        //     $name = date('d-m-Y-H-i-s') . '-' . $image->getClientOriginalName();
        //     $image_path = $image->storeAs('public/images/company', $name);
        //     $input['company_footer_logo'] = $image_path;
        // }

        // Handle file uploads and deletions
        foreach (['company_logo_one', 'company_logo_two', 'company_footer_logo'] as $fileField) {
            if ($request->hasfile($fileField)) {
                Storage::delete($companySetting->$fileField);
                $input[$fileField] = $request->file($fileField)->storeAs('public/images/company', date('d-m-Y-H-i-s') . '_' . $request->file($fileField)->getClientOriginalName());
            }
        }

        $companySetting->update($input);

        return redirect()->route('emp_company.index')->with([
            'message' => 'Successfully updated!',
            'alert-type' => 'info'
        ]);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $companySetting = CompanySetting::find($id);
        // Delete associated files from storage
        if ($companySetting->company_logo_one) {
            Storage::delete($companySetting->company_logo_one);
        }

        if ($companySetting->company_logo_two) {
            Storage::delete($companySetting->company_logo_two);
        }

        if ($companySetting->company_footer_logo) {
            Storage::delete($companySetting->company_footer_logo);
        }

        // Delete the CompanySetting record
        $companySetting->delete();

        // You can add a redirect or any other response logic here
        return redirect()->route('emp_company.index')->with([
            'message' => 'successfully deleted !',
            'alert-type' => 'danger'
        ]);
    }
}
