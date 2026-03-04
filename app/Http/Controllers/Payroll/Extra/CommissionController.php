<?php
namespace App\Http\Controllers\Payroll;
use App\Http\Controllers\Controller;

use App\Models\Commission;
use Illuminate\Http\Request;
use Auth;

class CommissionController extends Controller
{
    public function index()
    {
        // $Commissions = Commission::get();
        // $Commissions = Commission::join('users', 'users.id', '=', 'allowance_options.created_by')
        //                    ->select('allowance_options.*', 'users.first_name', 'users.last_name')
        //                    ->get();
        // return view('pages.payroll.allowance_option.index', compact('Commissions'));
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

        $commission = new Commission();

        $type = $request->input('type');
        if($type == 2){
            $commission->percentage = $request->input('percentage');
        }else{
            $commission->percentage = 0.00;
        }
        $commission->employee_id = $employee_id;
        $commission->title = $request->input('title');
        $commission->amount = $request->input('amount');
        $commission->type = $type;
        $commission->created_by = $created_by;

        // dd($commission);
        $commission->save();

        return redirect()->route('set_salaries.set_salaries_create', $employee_id )->with([
            'message' => 'successfully created !',
            'alert-type' => 'success'
        ]);
    }

    public function show(Commission $commission)
    {
        
    }

    public function edit(Commission $commission)
    {
        
    }

    public function update(Request $request, Commission $commission)
    {
        $request->validate([
            'title' => 'required|max:100',
            'amount' => 'required',
        ]);

        $created_by = Auth::user()->id;
        $employee_id = $request->input('employee_id');
        $type = $request->input('type');
        if($type == 2){
            $commission->percentage = $request->input('percentage');
        }else{
            $commission->percentage = 0.00;
        }
        $commission->employee_id = $employee_id;
        $commission->title = $request->input('title');
        $commission->amount = $request->input('amount');
        $commission->type = $type;
        $commission->created_by = $created_by;

        $commission->update();

        return redirect()->route('set_salaries.set_salaries_create', $employee_id )->with([
            'message' => 'successfully created !',
            'alert-type' => 'success'
        ]);
    }

    public function destroy(Commission $commission)
    {
        $commission->delete();
        return back()->with([
            'message' => 'successfully deleted !',
            'alert-type' => 'danger'
        ]);
    }


}
