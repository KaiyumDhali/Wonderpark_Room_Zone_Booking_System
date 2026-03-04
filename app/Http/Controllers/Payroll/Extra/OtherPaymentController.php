<?php
namespace App\Http\Controllers\Payroll;
use App\Http\Controllers\Controller;

use App\Models\OtherPayment;
use Illuminate\Http\Request;
use Auth;

class OtherPaymentController extends Controller
{
    public function index()
    {
        // $OtherPayments = OtherPayment::get();
        // $OtherPayments = OtherPayment::join('users', 'users.id', '=', 'allowance_options.created_by')
        //                    ->select('allowance_options.*', 'user.name')
        //                    ->get();
        // return view('pages.payroll.allowance_option.index', compact('OtherPayments'));
    }

    public function create()
    {
        // return view('pages.payroll.allowance_option.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:100',
            'amount' => 'required',
        ]);

        $created_by = Auth::user()->id;
        $employee_id = $request->input('employee_id');

        $otherpayment = new OtherPayment();

        $type = $request->input('type');
        if($type == 2){
            $otherpayment->percentage = $request->input('percentage');
        }else{
            $otherpayment->percentage = 0.00;
        }
        $otherpayment->employee_id = $employee_id;
        $otherpayment->title = $request->input('title');
        $otherpayment->amount = $request->input('amount');
        $otherpayment->type = $type;
        $otherpayment->created_by = $created_by;

        // dd($otherpayment);
        $otherpayment->save();

        return redirect()->route('set_salaries.set_salaries_create', $employee_id )->with([
            'message' => 'successfully created !',
            'alert-type' => 'success'
        ]);
    }

    public function show(OtherPayment $otherPayment)
    {
        
    }

    public function edit(OtherPayment $otherPayment)
    {
        
    }

    public function update(Request $request, OtherPayment $otherPayment)
    {
        $request->validate([
            'title' => 'required|max:100',
            'amount' => 'required',
        ]);

        $created_by = Auth::user()->id;
        $employee_id = $request->input('employee_id');
        $type = $request->input('type');
        if($type == 2){
            $otherPayment->percentage = $request->input('percentage');
        }else{
            $otherPayment->percentage = 0.00;
        }
        $otherPayment->employee_id = $employee_id;
        $otherPayment->title = $request->input('title');
        $otherPayment->amount = $request->input('amount');
        $otherPayment->type = $type;
        $otherPayment->created_by = $created_by;

        // dd($otherPayment);
        $otherPayment->update();

        return redirect()->route('set_salaries.set_salaries_create', $employee_id )->with([
            'message' => 'successfully created !',
            'alert-type' => 'success'
        ]);
    }

    public function destroy(OtherPayment $otherPayment)
    {
        $otherPayment->delete();
        return back()->with([
            'message' => 'successfully deleted !',
            'alert-type' => 'danger'
        ]);
    }

}
