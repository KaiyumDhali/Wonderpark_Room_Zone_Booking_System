<?php

namespace App\Http\Controllers\Employee;

use App\Models\EmpDesignation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use validator;
use Illuminate\Support\Str;

class EmpDesignationController extends Controller {

    function __construct() {
        $this->middleware('permission:read employee designation|write employee designation|create employee designation', ['only' => ['index', 'show']]);
        $this->middleware('permission:create employee designation', ['only' => ['create', 'store']]);
        $this->middleware('permission:write employee designation', ['only' => ['edit', 'update', 'destroy']]);
    }

    public function index() {
        $designations = EmpDesignation::get();
        return view('pages.employee.emp_designation.index', compact('designations'));
    }

    public function create() {
        return view('pages.employee.emp_designation._designation-add');
    }

    public function store(Request $request) {
        $validatedData = $request->validate([
            'designation_name' => 'required|string|max:255|unique:emp_designations,designation_name',
            'designation_name_bangla' => 'nullable|string|max:255|unique:emp_designations,designation_name_bangla',
            'status' => 'nullable|boolean',
        ]);
        $cleanedData = array_map(fn($value) => is_string($value) ? Str::of($value)->stripTags()->trim() : $value, $validatedData);
        // $cleanedData['created_by'] = Auth::user()->name;
        // dd($cleanedData);
        try {
            EmpDesignation::create($cleanedData);
            return back()->with(['message' => 'Successfully created!', 'alert-type' => 'success']);
        } catch (\Exception $e) {
            return back()->with(['message' => 'Failed to create. Please try again.', 'alert-type' => 'danger']);
        }
    }

    public function show($id) {
        //
    }

    public function edit(EmpDesignation $empDesignation) {
        return view('pages.employee.emp_designation._designation-update', compact('empDesignation'));
    }

    public function update(Request $request, EmpDesignation $empDesignation) {

        $validatedData = $request->validate([
            'designation_name' => 'required|string|max:255',
            'designation_name_bangla' => 'nullable|string|max:255',
            'status' => 'nullable|boolean',
        ]);
        $cleanedData = array_map(fn($value) => is_string($value) ? Str::of($value)->stripTags()->trim() : $value, $validatedData);
        try {
            // $empDepartment = EmpDepartment::findOrFail($id);
            $empDesignation->update($cleanedData);

            return back()->with(['message' => 'Successfully updated!', 'alert-type' => 'info']);
        } catch (\Exception $e) {
            return back()->with(['message' => 'Failed to update. Please try again.', 'alert-type' => 'danger']);
        }
    }

    public function destroy(EmpDesignation $empDesignation) {
        try {
            // $empDepartment = EmpType::findOrFail($id);
            $empDesignation->delete();
            return back()->with(['message' => 'Successfully deleted!', 'alert-type' => 'success']);
        } catch (\Exception $e) {
            return back()->with(['message' => 'Failed to delete. Please try again.', 'alert-type' => 'danger']);
        }
    }

}
