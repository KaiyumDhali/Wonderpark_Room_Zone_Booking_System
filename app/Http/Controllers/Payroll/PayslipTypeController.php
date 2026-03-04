<?php
namespace App\Http\Controllers\Payroll;
use App\Http\Controllers\Controller;

use App\Models\PayslipType;
use Illuminate\Http\Request;
use Auth;

class PayslipTypeController extends Controller
{
    function __construct()
    {
         $this->middleware('permission:read payslip type|write payslip type|create payslip type', ['only' => ['index','show']]);
         $this->middleware('permission:create payslip type', ['only' => ['create','store']]);
         $this->middleware('permission:write payslip type', ['only' => ['edit','update','destroy']]);
    }

    public function index()
    {
        $paysliptypes = PayslipType::join('users', 'users.id', '=', 'payslip_types.created_by')
                           ->select('payslip_types.*', 'users.name as created_by')
                           ->get();

        return view('pages.payroll.payslip_type.index', compact('paysliptypes'));
    }

    public function create()
    {
        return view('pages.payroll.payslip_type.create');
    }

    public function store(Request $request)
    {

        $request->validate([
            'name' => 'required|max:20',
        ]);

        // $created_by = Auth::user()->first_name.' '.Auth::user()->last_name;
        $created_by = Auth::user()->id;

        $payslipType = new PayslipType();

        $payslipType->name = $request->input('name');
        $payslipType->status = $request->input('status');
        $payslipType->created_by = $created_by;
        $payslipType->save();

        return redirect()->route('payslip_type.index')->with([
            'message' => 'successfully created !',
            'alert-type' => 'success'
        ]);
    }

    public function show(PayslipType $payslipType)
    {
        return redirect()->route('paysliptype.index');
    }

    public function edit(PayslipType $payslipType)
    {

        $paysliptypes = PayslipType::join('users', 'users.id', '=', 'payslip_types.created_by')
                           ->select('payslip_types.*', 'users.name')
                           ->get();
        return view('pages.payroll.payslip_type.edit', compact('paysliptype', 'paysliptypes'));
    }

    public function update(Request $request, PayslipType $payslipType)
    {
        $request->validate([
            'name' => 'required|max:20',
        ]);

        $created_by = Auth::user()->id;

        $payslipType->name = $request->input('name');
        $payslipType->status = $request->input('status');
        $payslipType->created_by = $created_by;
        // dd($payslipType);
        $payslipType->update();

        return redirect()->route('payslip_type.index')->with([
            'message' => 'successfully created !',
            'alert-type' => 'success'
        ]);        
    }
    
    public function destroy(PayslipType $payslipType)
    {
        $payslipType->delete();
        return back()->with([
            'message' => 'successfully deleted !',
            'alert-type' => 'danger'
        ]);
    }

}
