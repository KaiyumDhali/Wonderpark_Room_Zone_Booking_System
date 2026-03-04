<?php
namespace App\Http\Controllers\Payroll;
use App\Http\Controllers\Controller;

use App\Models\Overtime;
use Illuminate\Http\Request;
use Auth;

class OvertimeController extends Controller
{
    public function index()
    {
        // $Overtimes = Overtime::get();
        // $Overtimes = Overtime::join('users', 'users.id', '=', 'allowance_options.created_by')
        //                    ->select('allowance_options.*', 'user.name')
        //                    ->get();
        // return view('pages.payroll.allowance_option.index', compact('Overtimes'));
    }

    public function create()
    {
        // return view('pages.payroll.allowance_option.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:100',
            'rate' => 'required',
        ]);

        $created_by = Auth::user()->id;
        $employee_id = $request->input('employee_id');

        $overtime = new Overtime();

        $overtime->employee_id = $employee_id;
        $overtime->title = $request->input('title');
        $overtime->number_of_days = $request->input('number_of_days');
        $overtime->hours = $request->input('hours');
        $overtime->rate = $request->input('rate');
        $overtime->created_by = $created_by;

        // dd($overtime);
        $overtime->save();

        return redirect()->route('set_salaries.set_salaries_create', $employee_id )->with([
            'message' => 'successfully created !',
            'alert-type' => 'success'
        ]);
    }

    public function show(Overtime $overtime)
    {
        
    }

    public function edit(Overtime $overtime)
    {
        
    }

    public function update(Request $request, Overtime $overtime)
    {
        $request->validate([
            'title' => 'required|max:100',
            'rate' => 'required',
        ]);

        $created_by = Auth::user()->id;
        $employee_id = $request->input('employee_id');

        $overtime->employee_id = $employee_id;
        $overtime->title = $request->input('title');
        $overtime->number_of_days = $request->input('number_of_days');
        $overtime->hours = $request->input('hours');
        $overtime->rate = $request->input('rate');
        $overtime->created_by = $created_by;

        $overtime->update();

        return redirect()->route('set_salaries.set_salaries_create', $employee_id )->with([
            'message' => 'successfully created !',
            'alert-type' => 'success'
        ]);
    }

    public function destroy(Overtime $overtime)
    {
        $overtime->delete();
        return back()->with([
            'message' => 'successfully deleted !',
            'alert-type' => 'danger'
        ]);
    }

}
