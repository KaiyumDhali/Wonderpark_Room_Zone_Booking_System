<?php
namespace App\Http\Controllers\Payroll;
use App\Http\Controllers\Controller;

use App\Models\payrollFormula;
use App\Models\PayrollHead;
use App\Models\payroll;

use App\Models\Employee;
use App\Models\EmpDesignation;
use App\Models\EmpDepartment;
use App\Models\EmpSection;
use App\Models\EmpLine;
use App\Models\EmpType;
use App\Models\EmpSalarySection;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PayrollController extends Controller
{
    function __construct()
    {
         $this->middleware('permission:read payroll add|write payroll add|create payroll add', ['only' => ['index','show']]);
         $this->middleware('permission:create payroll add', ['only' => ['create','store']]);
         $this->middleware('permission:write payroll add', ['only' => ['edit','update','destroy']]);
    }

    public function index()
    {
        $startDate = Carbon::now()->format('d-m-Y');
        $allEmployee = Employee::where('status', 1)->pluck('employee_name', 'employee_code')->all();
        $allEmpDesignation = EmpDesignation::where('status', 1)->pluck('designation_name', 'id')->all();
        $allEmpDepartment = EmpDepartment::where('status', 1)->pluck('department_name', 'id')->all();
        $allEmpSection = EmpSection::where('status', 1)->pluck('section_name', 'id')->all();
        $allEmpLine = EmpLine::where('status', 1)->pluck('line_name', 'id')->all();
        $allEmpType = EmpType::where('status', 1)->pluck('type_name', 'id')->all();
        $allEmpSalarySection = EmpSalarySection::where('status', 1)->pluck('salary_section_name', 'id')->all();
        $payrollHead = PayrollHead::where('status', 1)->pluck('name', 'id')->all();
        return view('pages.payroll.payroll_add.index', compact('payrollHead', 'startDate', 'allEmployee', 'allEmpDesignation', 'allEmpDepartment', 'allEmpSection', 'allEmpLine', 'allEmpType', 'allEmpSalarySection'));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $selectedData = $request->input('selectedData');

        try {
            foreach ($selectedData as $data) {
                foreach ($data['payroll_head_ids'] as $payrollHeadId) {
                    Payroll::create([
                        'employee_id' => $data['employee_id'],
                        'payroll_head_id' => $payrollHeadId,
                        'effective_date' => $data['effective_date'],
                        // 'remarks' => $data['remarks'],
                        'created_by' => Auth::user()->name,
                    ]);
                }
            }
            return response()->json(['message' => 'Data submitted successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function show(payroll $payroll)
    {
        //
    }

    public function payrollEmployeeEdit($employee_id)
    {
        $payrollHeadAdd = Payroll::where('employee_id', $employee_id)
            ->leftJoin('payroll_heads', 'payrolls.payroll_head_id', '=', 'payroll_heads.id')
            ->pluck('payroll_heads.name', 'payrolls.id')
            ->toArray();

        $payrollHead = PayrollHead::where('status', 1)->pluck('name', 'id')->all();

        // Combine both arrays and return as JSON response
        return response()->json([
            'payrollHeadAdd' => $payrollHeadAdd,
            'payrollHead' => $payrollHead
        ]);
    }

    public function payrollEmployeeUpdate(Request $request)
    {
        try {
            $uncheckedCheckboxes = $request->input('uncheckedCheckboxes');

            // Assuming 'id' is the key used for identifying Payroll records to delete
            $idsToDelete = $uncheckedCheckboxes; // Array of IDs to delete

            // Use whereIn to delete Payroll records based on the IDs received
            if (!empty($idsToDelete)) {
                Payroll::whereIn('id', $idsToDelete)->delete();
            }

            return response()->json(['message' => 'Payroll data updated successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }




    public function update(Request $request, payroll $payroll)
    {
        //
    }

    public function destroy(payroll $payroll)
    {
        //
    }
}
