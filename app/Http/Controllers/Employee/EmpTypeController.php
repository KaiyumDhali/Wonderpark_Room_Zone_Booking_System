<?php
namespace App\Http\Controllers\Employee;
use App\Http\Controllers\Controller;
use App\Models\EmpType;
use App\Models\EmpDepartment;
use App\Models\EmpDesignation;

use App\Models\EmpSection;
use App\Models\EmpLine;
use App\Models\EmpGrade;
use App\Models\EmpSalarySection;
use App\Models\EmpQuiteType;

use Illuminate\Http\Request;
use Carbon\Carbon;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;


class EmpTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $empTypes = EmpType::orderBy('id', 'desc')->get();
        $empDepartments = EmpDepartment::orderBy('id', 'desc')->get();
        $empDesignations = EmpDesignation::orderBy('id', 'desc')->get();
        $empSections = EmpSection::orderBy('id', 'desc')->get();
        $empLines = EmpLine::orderBy('id', 'desc')->get();
        $empGrades = EmpGrade::orderBy('id', 'desc')->get();
        $empSalarySections = EmpSalarySection::orderBy('id', 'desc')->get();
        $empQuiteTypes = EmpQuiteType::orderBy('id', 'desc')->get();
       
        return view('pages.employee.employee_master_setting.index', compact('empTypes', 'empDepartments', 'empDesignations', 'empSections', 'empLines', 'empGrades', 'empSalarySections', 'empQuiteTypes'));
    }

    public function create() { }
    /**
     * store the specified resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData  = $request->validate([
            'type_name' => 'required|string|max:255|unique:emp_types,type_name',
            'type_name_bangla' => 'nullable|string|max:255|unique:emp_types,type_name_bangla',
            'status' => 'nullable|boolean',
        ]);
        $cleanedData = array_map(fn($value) => is_string($value) ? Str::of($value)->stripTags()->trim() : $value, $validatedData);
        // $cleanedData['created_by'] = Auth::user()->name;
        // dd($cleanedData);
        try {
            EmpType::create($cleanedData);
            return back()->with(['message' => 'Successfully created!', 'alert-type' => 'success']);
        } catch (\Exception $e) {
            return back()->with(['message' => 'Failed to create. Please try again.', 'alert-type' => 'danger']);
        }
    }


    public function show(EmpType $empType) { }

    public function edit(EmpType $empType) { }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'type_name' => 'required|string|max:255|unique:emp_types,type_name,' . $id,
            'type_name_bangla' => 'nullable|string|max:255|unique:emp_types,type_name_bangla,' . $id,
            'status' => 'nullable|boolean',
        ]);
        $cleanedData = array_map(fn($value) => is_string($value) ? Str::of($value)->stripTags()->trim() : $value, $validatedData);
        try {
            $empType = EmpType::findOrFail($id);
            $empType->update($cleanedData);

            return back()->with(['message' => 'Successfully updated!', 'alert-type' => 'info']);
        } catch (\Exception $e) {
            return back()->with(['message' => 'Failed to update. Please try again.', 'alert-type' => 'danger']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $empType = EmpType::findOrFail($id);
            $empType->delete();
            return back()->with(['message' => 'Successfully deleted!', 'alert-type' => 'success']);
        } catch (\Exception $e) {
            return back()->with(['message' => 'Failed to delete. Please try again.', 'alert-type' => 'danger']);
        }
    }

}