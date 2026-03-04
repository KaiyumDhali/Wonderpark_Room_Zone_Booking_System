<?php

namespace App\Http\Controllers\Employee;

use App\Models\EmployeeJobHistory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use validator;

class EmployeeJobHistoryController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $request->validate([
            'company_name' => 'required',
        ]);
        $input = $request->all();
        EmployeeJobHistory::create($input);
        return back()->with([
            'message' => 'successfully created!',
            'alert-type' => 'success'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show() {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit() {
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, EmployeeJobHistory $employeeJobHistory) {

        foreach ($request->get('job_history_id') as $key => $job_history_id) {
            $company_name = $request->input('company_name')[$key];
            $designation = $request->input('designation')[$key];
            $start_date = $request->input('start_date')[$key];
            $end_date = $request->input('end_date')[$key];
            EmployeeJobHistory::where('id', $job_history_id)->first()->update([
                'company_name' => $company_name,
                'designation' => $designation,
                'start_date' => $start_date,
                'end_date' => $end_date,
            ]);
        }
        return back()->with([
            'message' => 'successfully updated !',
            'alert-type' => 'info'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy() {
        
    }

}
