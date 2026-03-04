<?php
namespace App\Http\Controllers\Payroll;
use App\Http\Controllers\Controller;

use App\Models\EmployeeSalary;
use Illuminate\Http\Request;
use Auth;

class EmployeeSalaryController extends Controller
{
    public function index()
    {
        
    }

    public function create()
    {

    }

    public function store(Request $request)
    {
        $request->validate([
            'payslip_type' => 'required',
            'amount' => 'required',
            'start_date' => 'required',
        ]);

        $created_by = Auth::user()->id;
        $employee_id = $request->input('employee_id');

        $employeesalary = new EmployeeSalary();

        $employeesalary->employee_id = $employee_id;
        $employeesalary->payslip_type = $request->input('payslip_type');
        $employeesalary->amount = $request->input('amount');
        $employeesalary->start_date = $request->input('start_date');
        $employeesalary->created_by = $created_by;

        // dd($employeesalary);
        $employeesalary->save();

        return redirect()->route('set_salaries.set_salaries_create', $employee_id )->with([
            'message' => 'successfully created !',
            'alert-type' => 'success'
        ]);
    }

    public function show(EmployeeSalary $employeeSalary)
    {

    }

    public function edit(EmployeeSalary $employeeSalary)
    {

    }

    public function update(Request $request, EmployeeSalary $employeeSalary)
    {
        $request->validate([
            'payslip_type' => 'required',
            'amount' => 'required',
            'start_date' => 'required',
        ]);

        $created_by = Auth::user()->id;
        $employee_id = $request->input('employee_id');

        $employeeSalary->employee_id = $employee_id;
        $employeeSalary->payslip_type = $request->input('payslip_type');
        $employeeSalary->amount = $request->input('amount');
        $employeeSalary->start_date = $request->input('start_date');
        $employeeSalary->created_by = $created_by;

        // dd($employeeSalary->amount);

        $employeeSalary->update();

        return redirect()->route('set_salaries.set_salaries_create', $employee_id )->with([
            'message' => 'successfully created !',
            'alert-type' => 'success'
        ]); 
    }

    public function destroy(EmployeeSalary $employeeSalary)
    {
        
    }


}
