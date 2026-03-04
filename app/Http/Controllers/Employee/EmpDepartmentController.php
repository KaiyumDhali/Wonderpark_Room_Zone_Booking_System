<?php

namespace App\Http\Controllers\Employee;

use App\Models\EmpBranch;
use App\Models\EmpDepartment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use validator;
use Illuminate\Support\Str;

class EmpDepartmentController extends Controller {

    function __construct() {
        $this->middleware('permission:read employee department|write employee department|create employee department', ['only' => ['index', 'show']]);
        $this->middleware('permission:create employee department', ['only' => ['create', 'store']]);
        $this->middleware('permission:write employee department', ['only' => ['edit', 'update', 'destroy']]);
    }

    public function index() {
        $departments = EmpDepartment::join('emp_branches', 'emp_branches.id', '=', 'emp_departments.branch_id')
                        ->select('emp_departments.*', 'emp_branches.branch_name')
                        ->orderby('id', 'desc')->get();

        return view('pages.employee.emp_department.index', compact('departments'));
    }

    public function create() {
        $allEmpBranch = EmpBranch::pluck('branch_name', 'id')->all();
        return view('pages.employee.emp_department._department-add', compact('allEmpBranch'));
    }

    public function store(Request $request) {
        $validatedData = $request->validate([
            'department_name' => 'required|string|max:255|unique:emp_departments,department_name',
            'department_name_bangla' => 'nullable|string|max:255|unique:emp_departments,department_name_bangla',
            'status' => 'nullable|boolean',
        ]);
        $cleanedData = array_map(fn($value) => is_string($value) ? Str::of($value)->stripTags()->trim() : $value, $validatedData);
        // $cleanedData['created_by'] = Auth::user()->name;
        // dd($cleanedData);
        try {
            EmpDepartment::create($cleanedData);
            return back()->with(['message' => 'Successfully created!', 'alert-type' => 'success']);
        } catch (\Exception $e) {
            return back()->with(['message' => 'Failed to create. Please try again.', 'alert-type' => 'danger']);
        }
    }

    public function show($id) {
        //
    }

    public function edit(EmpDepartment $empDepartment) {
        $allEmpBranch = EmpBranch::pluck('branch_name', 'id')->all();
        return view('pages.employee.emp_department._department-update', compact('empDepartment', 'allEmpBranch'));
    }

    public function update(Request $request, EmpDepartment $empDepartment) {

        $validatedData = $request->validate([
            'department_name' => 'required|string|max:255',
            'department_name_bangla' => 'nullable|string|max:255',
            'status' => 'nullable|boolean',
        ]);
        $cleanedData = array_map(fn($value) => is_string($value) ? Str::of($value)->stripTags()->trim() : $value, $validatedData);
        try {
            // $empDepartment = EmpDepartment::findOrFail($id);
            $empDepartment->update($cleanedData);

            return back()->with(['message' => 'Successfully updated!', 'alert-type' => 'info']);
        } catch (\Exception $e) {
            return back()->with(['message' => 'Failed to update. Please try again.', 'alert-type' => 'danger']);
        }
    }

    public function destroy(EmpDepartment $empDepartment) {
        try {
            // $empDepartment = EmpType::findOrFail($id);
            $empDepartment->delete();
            return back()->with(['message' => 'Successfully deleted!', 'alert-type' => 'success']);
        } catch (\Exception $e) {
            return back()->with(['message' => 'Failed to delete. Please try again.', 'alert-type' => 'danger']);
        }
    }

}
