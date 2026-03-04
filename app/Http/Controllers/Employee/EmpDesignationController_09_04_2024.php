<?php

namespace App\Http\Controllers\Employee;

use App\Models\EmpDesignation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use validator;

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
        $request->validate([
            'designation_name' => 'required',
        ]);

        $input = $request->all();

        $cleanedData = array_map(function ($value) {
            return is_string($value) ? strip_tags($value) : $value;
        }, $input);

        EmpDesignation::create($cleanedData);

        return redirect()->route('emp_designation.index')->with([
                    'message' => 'successfully created !',
                    'alert-type' => 'success'
        ]);
    }

    public function show($id) {
        //
    }

    public function edit(EmpDesignation $empDesignation) {
        return view('pages.employee.emp_designation._designation-update', compact('empDesignation'));
    }

    public function update(Request $request, EmpDesignation $empDesignation) {

        $request->validate([
            'designation_name' => 'required|string|max:255',
        ]);

        $empDesignation->designation_name = $request->designation_name;
        $empDesignation->status = $request->status;
        $empDesignation->update();

        return redirect()->route('emp_designation.index')->with([
                    'message' => 'successfully updated !',
                    'alert-type' => 'info'
        ]);
    }

    public function destroy(EmpDesignation $empDesignation) {
        $empDesignation->delete();
        return redirect()->route('emp_designation.index')->with([
                    'message' => 'successfully deleted !',
                    'alert-type' => 'danger'
        ]);
    }

}
