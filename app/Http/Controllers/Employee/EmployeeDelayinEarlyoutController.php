<?php
namespace App\Http\Controllers\Employee;

use Illuminate\Support\Facades\DB;
use App\Models\Employee;
use App\Models\EmpBranch;
use App\Models\EmpDepartment;
use App\Models\EmpDesignation;
use App\Models\EmployeeEducation;
use App\Models\EmployeeJobHistory;
use App\Models\EmployeeDelayinEarlyout;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use validator;
use App\Models\User;
use Auth;

class EmployeeDelayinEarlyoutController extends Controller {

    function __construct()
    {
         $this->middleware('permission:read delayin earlyout|write delayin earlyout|create delayin earlyout', ['only' => ['index','show']]);
         $this->middleware('permission:create delayin earlyout', ['only' => ['create','store']]);
         $this->middleware('permission:write delayin earlyout', ['only' => ['edit','update','destroy']]);
    }

    public function index() {
        $employees_in_out = EmployeeDelayinEarlyout::leftJoin('employees', 'employees.id', '=', 'employee_delayin_earlyouts.employee_id')
                ->select('employee_delayin_earlyouts.*', 'employees.employee_name')
                ->get();
        // dd($employees_in_out);
        return view('pages.employee.emp_delayin_earlyout.index', compact('employees_in_out'));
    }

    public function create() {
        $allEmployees = Employee::pluck('employee_name','id')->all();
        return view('pages.employee.emp_delayin_earlyout._delayin_earlyout_add', compact('allEmployees'));
    }

    public function store(Request $request) {

        $request->validate([
            'employee_id' => 'required',
            'date' => 'required',
        ]);

        $input = $request->all();
        $employeeDelayinEarlyout = new EmployeeDelayinEarlyout();
        $employeeDelayinEarlyout->employee_id = $request->input('employee_id');
        $employeeDelayinEarlyout->date = $request->input('date');
        $employeeDelayinEarlyout->status = $request->input('status');
        $employeeDelayinEarlyout->remarks = $request->input('remarks');

        // $employeeDelayinEarlyout->delay_in_time = $request->input('delay_in_time');
        // $employeeDelayinEarlyout->early_out_time = $request->input('early_out_time');
        $employeeDelayinEarlyout->done_by =Auth::user()->name;

        // dd($employeeDelayinEarlyout);
        $employeeDelayinEarlyout->save();

        return redirect()->route('emp_delayin_earlyout.index')->with([
                    'message' => 'successfully created !',
                    'alert-type' => 'success'
        ]);
    }

    public function show() {
        //
    }

    public function edit() {
        
    }

    public function update(Request $request, EmployeeEducation $employeeEducation) {

//        $request->validate([
//            'exam' => 'required|string|max:255',
//        ]);
        // $input = $request->all();
        // $input->status = 1;
        // $empEducation->update($input);
        // dd('here');
        foreach ($request->get('employee_id') as $key => $employee_id) {
            $empExam = $request->input('exam')[$key];
            $empinstitution = $request->input('institution')[$key];
            $emppassingyear = $request->input('passingyear')[$key];
            $empresult = $request->input('result')[$key];
            EmployeeEducation::where('id', $employee_id)->first()->update([
                'exam' => $empExam,
                'institution' => $empinstitution,
                'passingyear' => $emppassingyear,
                'result' => $empresult,
            ]);
        }

        return back()->with([
                    'message' => 'successfully updated !',
                    'alert-type' => 'info'
        ]);
    }

    public function destroy() {
        
    }

}