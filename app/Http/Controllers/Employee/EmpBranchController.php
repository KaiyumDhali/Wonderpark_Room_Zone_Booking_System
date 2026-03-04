<?php

namespace App\Http\Controllers\Employee;

use App\Models\EmpBranch;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Auth;
use validator;

class EmpBranchController extends Controller {

    function __construct() {
        $this->middleware('permission:read employee branch|write employee branch|create employee branch', ['only' => ['index', 'show']]);
        $this->middleware('permission:create employee branch', ['only' => ['create', 'store']]);
        $this->middleware('permission:write employee branch', ['only' => ['edit', 'update', 'destroy']]);
    }

    public function index() {
        $branches = EmpBranch::get();

        return view('pages.employee.emp_branch.index', compact('branches'));
    }

    public function create() {
        return view('pages.employee.emp_branch._branch-add');
    }

    public function store(Request $request) {
        $request->validate([
            'branch_name' => 'required',
        ]);

        $input = $request->all();

        $cleanedData = array_map(function ($value) {
            return is_string($value) ? strip_tags($value) : $value;
        }, $input);

        EmpBranch::create($cleanedData);

        return redirect()->route('emp_branch.index')->with([
                    'message' => 'successfully created !',
                    'alert-type' => 'success'
        ]);
    }

    public function show($id) {
        //
    }

    public function edit(EmpBranch $empBranch) {
        return view('pages.employee.emp_branch._branch-update', compact('empBranch'));
    }

    public function update(Request $request, EmpBranch $empBranch) {

        $request->validate([
            'branch_name' => 'required|string|max:255',
        ]);

        $empBranch->branch_code = strip_tags($request->branch_code);
        $empBranch->branch_name = strip_tags($request->branch_name);
        $empBranch->branch_mobile = strip_tags($request->branch_mobile);
        $empBranch->branch_email = strip_tags($request->branch_email);
        $empBranch->branch_address = strip_tags($request->branch_address);
        $empBranch->status = $request->status;
        $empBranch->update();

        return redirect()->route('emp_branch.index')->with([
                    'message' => 'successfully updated !',
                    'alert-type' => 'info'
        ]);
    }

    public function destroy(EmpBranch $empBranch) {
        $empBranch->delete();

        return redirect()->route('emp_branch.index')->with([
                    'message' => 'successfully deleted !',
                    'alert-type' => 'danger'
        ]);
    }

}
