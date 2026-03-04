<?php
namespace App\Http\Controllers\Payroll;
use App\Http\Controllers\Controller;

use App\Models\Allowance;
use Illuminate\Http\Request;
use Auth;

class AllowanceController extends Controller
{
    public function index()
    {
        // $allowances = Allowance::get();
        // $allowances = Allowance::join('users', 'users.id', '=', 'allowance_options.created_by')
        //                    ->select('allowance_options.*', 'users.first_name', 'users.last_name')
        //                    ->get();
        // return view('pages.payroll.allowance_option.index', compact('allowances'));
    }

    public function create()
    {
        
    }

    public function store(Request $request)
    {
        $request->validate([
            'allowance_option' => 'required',
            'title' => 'required|max:100',
            'amount' => 'required',
        ]);

        $created_by = Auth::user()->id;
        $employee_id = $request->input('employee_id');
        $allowance = new Allowance();

        $type = $request->input('type');
        if($type == 2){
            $allowance->percentage = $request->input('percentage');
        }else{
            $allowance->percentage = 0.00;
        }
        $allowance->employee_id = $employee_id;
        $allowance->allowance_option = $request->input('allowance_option');
        $allowance->title = $request->input('title');
        $allowance->amount = $request->input('amount');
        $allowance->type = $type;
        $allowance->created_by = $created_by;

        // dd($allowance);
        $allowance->save();

        return redirect()->route('set_salaries.set_salaries_create', $employee_id )->with([
            'message' => 'successfully created !',
            'alert-type' => 'success'
        ]);
    }

    public function show(Allowance $allowance)
    {

    }

    public function edit(Allowance $allowance)
    {

    }

    public function update(Request $request, Allowance $allowance)
    {
        $request->validate([
            'allowance_option' => 'required',
            'title' => 'required|max:100',
            'amount' => 'required',
        ]);

        $created_by = Auth::user()->id;
        $employee_id = $request->input('employee_id');
        $type = $request->input('type');
        if($type == 2){
            $allowance->percentage = $request->input('percentage');
        }else{
            $allowance->percentage = 0.00;
        }
        $allowance->employee_id = $employee_id;
        $allowance->allowance_option = $request->input('allowance_option');
        $allowance->title = $request->input('title');
        $allowance->type = $type;
        $allowance->amount = $request->input('amount');
        $allowance->created_by = $created_by;
        // dd($allowance);
        $allowance->update();

        return redirect()->route('set_salaries.set_salaries_create', $employee_id )->with([
            'message' => 'successfully created !',
            'alert-type' => 'success'
        ]);
    }

    public function destroy(Allowance $allowance)
    {
        $allowance->delete();
        return back()->with([
            'message' => 'successfully deleted !',
            'alert-type' => 'danger'
        ]);
    }


}
