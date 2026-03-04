<?php
namespace App\Http\Controllers\Payroll;
use App\Http\Controllers\Controller;

use App\Models\Loan;
use Illuminate\Http\Request;
use Auth;

class LoanController extends Controller
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
            'loan_option' => 'required',
            'title' => 'required|max:100',
            'amount' => 'required',
        ]);

        $created_by = Auth::user()->id;
        $employee_id = $request->input('employee_id');

        $loan = new Loan();

        $type = $request->input('type');
        if($type == 2){
            $loan->percentage = $request->input('percentage');
        }else{
            $loan->percentage = 0.00;
        }
        $loan->employee_id = $employee_id;
        $loan->loan_option = $request->input('loan_option');
        $loan->title = $request->input('title');
        $loan->type = $type;
        $loan->amount = $request->input('amount');
        $loan->start_date = $request->input('start_date');
        $loan->end_date = $request->input('end_date');
        $loan->reason = $request->input('reason');
        $loan->created_by = $created_by;

        // dd($loan);
        $loan->save();

        return redirect()->route('set_salaries.set_salaries_create', $employee_id )->with([
            'message' => 'successfully created !',
            'alert-type' => 'success'
        ]);
    }

    public function show(Loan $loan)
    {

    }

    public function edit(Loan $loan)
    {

    }

    public function update(Request $request, Loan $loan)
    {
        $request->validate([
            'loan_option' => 'required',
            'title' => 'required|max:100',
            'amount' => 'required',
        ]);

        $created_by = Auth::user()->id;
        $employee_id = $request->input('employee_id');

        $type = $request->input('type');
        if($type == 2){
            $loan->percentage = $request->input('percentage');
        }else{
            $loan->percentage = 0.00;
        }
        $loan->employee_id = $employee_id;
        $loan->loan_option = $request->input('loan_option');
        $loan->title = $request->input('title');
        $loan->type = $type;
        $loan->amount = $request->input('amount');
        $loan->start_date = $request->input('start_date');
        $loan->end_date = $request->input('end_date');
        $loan->reason = $request->input('reason');
        $loan->created_by = $created_by;

        $loan->update();

        return redirect()->route('set_salaries.set_salaries_create', $employee_id )->with([
            'message' => 'successfully update !',
            'alert-type' => 'success'
        ]);
    }

    public function destroy(Loan $loan)
    {
        $loan->delete();
        return back()->with([
            'message' => 'successfully deleted !',
            'alert-type' => 'danger'
        ]);
    }


}
