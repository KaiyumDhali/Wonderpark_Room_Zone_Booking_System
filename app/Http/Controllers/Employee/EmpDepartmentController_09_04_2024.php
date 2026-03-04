<?php

namespace App\Http\Controllers\Employee;

use App\Models\EmpBranch;
use App\Models\EmpDepartment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use validator;

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
        $request->validate([
            'department_name' => 'required',
        ]);

        $input = $request->all();
        
        $cleanedData = array_map(function ($value) {
            return is_string($value) ? strip_tags($value) : $value;
        }, $input);
        
        EmpDepartment::create($cleanedData);

        return redirect()->route('emp_department.index')->with([
                    'message' => 'successfully created !',
                    'alert-type' => 'success'
        ]);
    }

    public function show($id) {
        //
    }

    public function edit(EmpDepartment $empDepartment) {
        $allEmpBranch = EmpBranch::pluck('branch_name', 'id')->all();
        return view('pages.employee.emp_department._department-update', compact('empDepartment', 'allEmpBranch'));
    }

    public function update(Request $request, EmpDepartment $empDepartment) {

        $request->validate([
            'department_name' => 'required|string|max:255',
        ]);

        $empDepartment->branch_id = $request->branch_id;
        $empDepartment->department_name = strip_tags($request->department_name);
        $empDepartment->status = $request->status;
        $empDepartment->update();

        return redirect()->route('emp_department.index')->with([
                    'message' => 'successfully updated !',
                    'alert-type' => 'info'
        ]);
    }

    public function destroy(EmpDepartment $branch) {
        $branch->delete();

        return redirect()->route('branch.index')->with([
                    'message' => 'successfully deleted !',
                    'alert-type' => 'danger'
        ]);
    }

}
