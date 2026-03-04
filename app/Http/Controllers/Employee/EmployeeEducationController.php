<?php

namespace App\Http\Controllers\Employee;

use App\Models\EmployeeEducation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use validator;

class EmployeeEducationController extends Controller {

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
            'exam' => 'required',
        ]);
        $input = $request->all();
        EmployeeEducation::create($input);
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
    public function update(Request $request, EmployeeEducation $employeeEducation) {
        foreach ($request->get('education_id') as $key => $education_id) {
            $exam = $request->input('exam')[$key];
            $institution = $request->input('institution')[$key];
            $passingyear = $request->input('passingyear')[$key];
            $result = $request->input('result')[$key];
            EmployeeEducation::where('id', $education_id)->first()->update([
                'exam' => $exam,
                'institution' => $institution,
                'passingyear' => $passingyear,
                'result' => $result,
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
