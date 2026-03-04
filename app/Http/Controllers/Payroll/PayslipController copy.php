<?php
namespace App\Http\Controllers\Payroll;
use App\Http\Controllers\Controller;

use App\Models\Payslip;
use App\Models\Employee;
use App\Models\EmployeeSalary;
use App\Models\Allowance;
use App\Models\Commission;
use App\Models\Loan;
use App\Models\OtherPayment;
use App\Models\Overtime;

use Illuminate\Http\Request;
use Auth;

class PayslipController extends Controller
{
    function __construct()
    {
         $this->middleware('permission:read payslips|write payslips|create payslips', ['only' => ['index','show']]);
         $this->middleware('permission:create payslips', ['only' => ['create','store', 'setSalariesCreate']]);
         $this->middleware('permission:write payslips', ['only' => ['edit','update','destroy']]);
    }

    public function getAllPayslipData($employeeId)
    {

        $employeeSalary = EmployeeSalary::with(['user:id,name','employee:id,employee_name', 'paysliptype:id,name'])
        ->where('employee_id', $employee_id)        
        ->latest()->first();

        $allowances = Allowance::with(['user:id,name','employee:id,employee_name', 'allowanceoption:id,name'])
                    ->where('employee_id', $employee_id)        
                    ->get();

        $commissions = Commission::with(['user:id,name','employee:id,employee_name'])
                    ->where('employee_id', $employee_id)            
                    ->get();

        $otherPayments = OtherPayment::with(['user:id,name','employee:id,employee_name'])
        ->where('employee_id', $employee_id)        
        ->get();

        $overtimes = Overtime::with(['user:id,name','employee:id,employee_name'])
        ->where('employee_id', $employee_id)        
        ->get();

        $loans = Loan::with(['user:id,name','employee:id,employee_name', 'loanoption:id,name'])
        ->where('employee_id', $employee_id)        
        ->get();

        return response()->json([
            'employeeSalary' => $employeeSalary,
            'allowances' => $allowances,
            'commissions' => $commissions,
            'otherPayments' => $otherPayments,
            'overtimes' => $overtimes,
            'loans' => $loans,
        ]);



        // $payslip = Payslip::where('employee_id', $employeeId)->first();
        // return response()->json($payslip);
    }

    public function getPayslipData($employeeId)
    {
        $payslip = Payslip::where('employee_id', $employeeId)->first();

        // Return payslip data as JSON
        return response()->json($payslip);
    }
    
    public function index()
    {
        // $employeeId = 1;
        // $payslip = Payslip::where('employee_id', $employeeId)->first();
        // dd($payslip);
        $payslips = Payslip::with('employees','employeesallary')->get();
        // dd($payslips);
        return view('pages.payroll.payslips.index', compact('payslips'));
    }

    public function create()
    {
        return view('pages.payroll.payslip_type.create');
    }

    public function store(Request $request)
    {
        $selectedmonth  = $request->selectedmonth;
        $created_by = Auth::user()->id;

        $formate_month_year = $selectedmonth;
        $validatePaysilp = PaySlip::where('salary_month', '=', $formate_month_year)->where('created_by', Auth::user()->id)->pluck('employee_id');
        // dd($validatePaysilp);
        $payslip_employee   = Employee::where('created_by', $created_by)->where('joining_date', '<=', date($selectedmonth . '-t'))->count();
        // dd($payslip_employee);
        if($payslip_employee > count($validatePaysilp))
        {
            $employees = Employee::where('created_by', $created_by)->where('joining_date', '<=', date($selectedmonth . '-t'))->whereNotIn('id', $validatePaysilp)->get();
            // dd($employees);
            $employeesSalary = EmployeeSalary::where('created_by', $created_by)->where('amount', '<=', 0)->first();
            // dd($employeesSalary);
            if(!empty($employeesSalary))
            {
                return redirect()->route('payslipss.index')->withErrors(['error' => 'Ops ! Please set employee salary.']);
            }
            foreach($employees as $employee)
            {

                $payslipEmployee                       = new PaySlip();
                $payslipEmployee->employee_id          = $employee->id;
                $payslipEmployee->net_payble           = $employee->get_net_salary();
                $payslipEmployee->salary_month         = $formate_month_year;
                $payslipEmployee->status               = 0;
                $payslipEmployee->basic_salary         = Employee::salaryamount($employee->id);
                $payslipEmployee->allowance            = Employee::allowance($employee->id);
                $payslipEmployee->commission           = Employee::commission($employee->id);
                $payslipEmployee->loan                 = Employee::loan($employee->id);
                $payslipEmployee->other_payment        = Employee::other_payment($employee->id);
                $payslipEmployee->overtime             = Employee::overtime($employee->id);
                $payslipEmployee->created_by           = $created_by;

                // dd($payslipEmployee);
                $payslipEmployee->save();

                //Slack Notification
                // $setting  = Utility::settings($created_by);
                // if(isset($setting['payslip_notification']) && $setting['payslip_notification'] ==1){
                //     $msg = __("Payslip generated of").' '.$formate_month_year.'.';
                //     Utility::send_slack_msg($msg);
                // }

                //Telegram Notification
                // $setting  = Utility::settings($created_by);
                // if(isset($setting['telegram_payslip_notification']) && $setting['telegram_payslip_notification'] ==1){
                //     $msg = __("Payslip generated of").' '.$formate_month_year.'.';
                //     Utility::send_telegram_msg($msg);
                // }

                //webhook
                // $module ='New Monthly Payslip';
                // $webhook=  Utility::webhookSetting($module);
                // if($webhook)
                // {
                //     $parameter = json_encode($payslipEmployee);
                //     $status = Utility::WebhookCall($webhook['url'],$parameter,$webhook['method']);

                //     if($status == true)
                //     {
                //         return redirect()->back()->with('success', __('Payslip successfully created.'));
                //     }
                //     else
                //     {
                //         return redirect()->back()->with('error', __('Webhook call failed.'));
                //     }
                // }

            }

            return redirect()->route('payslips.index')->with('success', __('Payslip successfully created.'));
        }
        else
        {
            return redirect()->route('payslips.index')->with('error', __('Payslip Already created.'));
        }

    }

    public function show(Payslip $payslip)
    {

    }

    public function edit(Payslip $payslip)
    {

    }

    public function update(Request $request, Payslip $payslip)
    {
        $payslip->status = 1;
        $payslip->update();

        return back()->with([
            'message' => 'successfully created !',
            'alert-type' => 'success'
        ]); 
    }

    public function destroy(Payslip $payslip)
    {
        $payslip->delete();
        return back()->with([
            'message' => 'successfully deleted !',
            'alert-type' => 'danger'
        ]);
    }


}
