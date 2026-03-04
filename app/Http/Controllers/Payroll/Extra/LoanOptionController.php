<?php
namespace App\Http\Controllers\Payroll;
use App\Http\Controllers\Controller;

use App\Models\LoanOption;
use Illuminate\Http\Request;
use Auth;

class LoanOptionController extends Controller
{
    function __construct()
    {
         $this->middleware('permission:read loan option|write loan option|create loan option', ['only' => ['index','show']]);
         $this->middleware('permission:create loan option', ['only' => ['create','store']]);
         $this->middleware('permission:write loan option', ['only' => ['edit','update','destroy']]);
    }

    public function index()
    {
        // $LoanOptions = LoanOption::get();
        $loanOptions = LoanOption::join('users', 'users.id', '=', 'loan_options.created_by')
                           ->select('loan_options.*', 'users.name as created_by')
                           ->get();
        return view('pages.payroll.loan_option.index', compact('loanOptions'));
    }

    public function create()
    {
        return view('pages.payroll.loan_option.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:20',
        ]);

        // $created_by = Auth::user()->first_name.' '.Auth::user()->last_name;
        $created_by = Auth::user()->id;

        $loanOption = new LoanOption();

        $loanOption->name = $request->input('name');
        $loanOption->status = $request->input('status');
        $loanOption->created_by = $created_by;
        $loanOption->save();

        return redirect()->route('loan_option.index')->with([
            'message' => 'successfully created !',
            'alert-type' => 'success'
        ]);
    }

    public function show(LoanOption $loanOption)
    {
        return redirect()->route('loan_option.index');
    }

    public function edit(LoanOption $loanOption)
    {

        $loanOption = LoanOption::join('users', 'users.id', '=', 'loan_options.created_by')
                           ->select('loan_options.*', 'users.name')
                           ->get();
        return view('pages.payroll.loan_option.edit', compact('loanOption', 'loanOptions'));
    }

    public function update(Request $request, LoanOption $loanOption)
    {
        $request->validate([
            'name' => 'required|max:20',
        ]);

        $created_by = Auth::user()->id;

        $loanOption->name = $request->input('name');
        $loanOption->status = $request->input('status');
        $loanOption->created_by = $created_by;
        // dd($loanOption);
        $loanOption->update();

        return redirect()->route('loan_option.index')->with([
            'message' => 'successfully created !',
            'alert-type' => 'success'
        ]); 
    }

    public function destroy(LoanOption $loanOption)
    {
        $loanOption->delete();
        return back()->with([
            'message' => 'successfully deleted !',
            'alert-type' => 'danger'
        ]);
        // if(\Auth::user()->can('delete payslip type'))
        // {
        //     if($loanOption->created_by == \Auth::user()->creatorId())
        //     {
        //         $loanOption->delete();

        //         return redirect()->route('loan_option.index')->with('success', __('Loan Option successfully deleted.'));
        //     }
        //     else
        //     {
        //         return redirect()->back()->with('error', __('Permission denied.'));
        //     }
        // }
        // else
        // {
        //     return redirect()->back()->with('error', __('Permission denied.'));
        // }
    }


}
