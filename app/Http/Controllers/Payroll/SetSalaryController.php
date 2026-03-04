<?php
namespace App\Http\Controllers\Payroll;
use App\Http\Controllers\Controller;

use App\Models\SetSalary;
use App\Models\Employee;
use App\Models\EmpPosting;

use App\Models\PayslipType;
use App\Models\EmployeeSalary;
use App\Models\IncomeHead;
use App\Models\Income;
use App\Models\DeductionHead;
use App\Models\Deduction;

use App\Models\AllowanceOption;
use App\Models\Allowance;
use App\Models\Commission;
use App\Models\LoanOption;
use App\Models\Loan;
use App\Models\OtherPayment;
use App\Models\Overtime;

use Illuminate\Http\Request;
use Auth;

class SetSalaryController extends Controller
{
    function __construct()
    {
         $this->middleware('permission:read set salaries|write set salaries|create set salaries', ['only' => ['index','show']]);
         $this->middleware('permission:create set salaries', ['only' => ['create','store', 'setSalariesCreate']]);
         $this->middleware('permission:write set salaries', ['only' => ['edit','update','destroy']]);
    }

    public function index()
    {
        $setSalaries = Employee::get();
        // $setSalaries = Employee::join('users', 'users.id', '=', 'set_salaries.created_by')
        //                    ->select('set_salaries.*', 'user.name')
        //                    ->get();
        return view('pages.payroll.set_salaries.index', compact('setSalaries'));
    }

    // public function create()
    // {
    //     $allowanceOptions = AllowanceOption::where('status', 1)->pluck('name', 'id')->all();
    //     return view('pages.payroll.set_salaries.create', compact('allowanceOptions'));
    // }

    public function setSalariesCreate($employee_id)
    {
        $payslipTypes = PayslipType::where('status', 1)->pluck('name', 'id')->all();
        $employeeSalary = EmpPosting::with(['employee:id,employee_name'])
                        ->where('employee_id', $employee_id)        
                        ->latest()->first();

        $incomeHeads = IncomeHead::where('status', 1)->pluck('name', 'id')->all();
        $incomes = Income::with(['employee:id,employee_name', 'incomehead:id,name'])
                    ->where('employee_id', $employee_id)        
                    ->get();

//        $loanOptions = LoanOption::where('status', 1)->pluck('name', 'id')->all();
//        $loans = Loan::with(['user:id,name','employee:id,employee_name', 'loanoption:id,name'])
//        ->where('employee_id', $employee_id)        
//        ->get();
        
        $deductionHeads = DeductionHead::where('status', 1)->pluck('name', 'id')->all();
        $deductions = Deduction::with(['employee:id,employee_name', 'deductionhead:id,name'])
        ->where('employee_id', $employee_id)        
        ->get();

        return view('pages.payroll.set_salaries.create', compact('employee_id', 'payslipTypes', 'employeeSalary', 'incomeHeads', 'incomes', 'deductionHeads', 'deductions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:20',
        ]);

        // $created_by = Auth::user()->first_name.' '.Auth::user()->last_name;
        $created_by = Auth::user()->id;

        $setSalary = new SetSalary();

        $setSalary->name = $request->input('name');
        $setSalary->status = $request->input('status');
        $setSalary->created_by = $created_by;
        $setSalary->save();

        return redirect()->route('set_salaries.index')->with([
            'message' => 'successfully created !',
            'alert-type' => 'success'
        ]);
    }

    public function show(SetSalary $setSalary)
    {
        return redirect()->route('set_salaries.index');
    }

    public function edit(SetSalary $setSalary)
    {

        $setSalaries = SetSalary::join('users', 'users.id', '=', 'set_salaries.created_by')
                           ->select('set_salaries.*', 'user.name')
                           ->get();
        return view('pages.payroll.set_salaries.edit', compact('setSalary', 'setSalaries'));
    }

    public function update(Request $request, SetSalary $setSalary)
    {
        dd($setSalary);
        if(\Auth::user()->can('edit payslip type'))
        {
            if($setSalary->created_by == \Auth::user()->creatorId())
            {
                $validator = \Validator::make(
                    $request->all(), [
                                       'name' => 'required|max:20',
                                   ]
                );

                if($validator->fails())
                {
                    $messages = $validator->getMessageBag();

                    return redirect()->back()->with('error', $messages->first());
                }

                $setSalary->name = $request->name;
                $setSalary->save();

                return redirect()->route('set_salaries.index')->with('success', __('Loan Option successfully updated.'));
            }
            else
            {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function destroy(SetSalary $setSalary)
    {
        if(\Auth::user()->can('delete payslip type'))
        {
            if($setSalary->created_by == \Auth::user()->creatorId())
            {
                $setSalary->delete();

                return redirect()->route('set_salaries.index')->with('success', __('Loan Option successfully deleted.'));
            }
            else
            {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


}
