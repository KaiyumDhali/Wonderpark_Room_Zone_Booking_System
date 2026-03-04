<?php
namespace App\Http\Controllers\Employee;

use App\Models\EmployeeJobResponsibility;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use validator;

class EmployeeJobResponsibilityController extends Controller
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
        $request->validate([
            'job_responsibility' => 'required',
        ]);
        $input = $request->all();
        EmployeeJobResponsibility::create($input);
        return back()->with([
            'message' => 'successfully created!',
            'alert-type' => 'success'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(EmployeeJobResponsibility $employeeJobResponsibility)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EmployeeJobResponsibility $employeeJobResponsibility)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    // public function update(Request $request, EmployeeJobResponsibility $employeeJobResponsibility)
    public function update(Request $request, $id)
    {
        foreach ($request->get('responsibility_id') as $key => $responsibility_id) {
            $job_responsibility = $request->input('job_responsibility')[$key];
            // $start_date = $request->input('start_date')[$key];
            // $end_datet = $request->input('end_date')[$key];

            EmployeeJobResponsibility::where('id', $responsibility_id)->first()->update([
                'job_responsibility' => $job_responsibility,
                // 'start_date' => $start_date,
                // 'end_date' => $end_datet,
            ]);
        }
        return back()->with([
            'message' => 'successfully updated !',
            'alert-type' => 'info'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EmployeeJobResponsibility $employeeJobResponsibility)
    {
        //
    }
}
