<?php
namespace App\Http\Controllers\Payroll;
use App\Http\Controllers\Controller;

use App\Models\AllowanceOption;
use Illuminate\Http\Request;
use Auth;

class AllowanceOptionController extends Controller
{
    function __construct()
    {
         $this->middleware('permission:read allowance option|write allowance option|create allowance option', ['only' => ['index','show']]);
         $this->middleware('permission:create allowance option', ['only' => ['create','store']]);
         $this->middleware('permission:write allowance option', ['only' => ['edit','update','destroy']]);
    }

    public function index()
    {
        // $allowanceOptions = AllowanceOption::get();
        $allowanceOptions = AllowanceOption::join('users', 'users.id', '=', 'allowance_options.created_by')
                           ->select('allowance_options.*', 'users.name as  created_by')
                           ->get();
        return view('pages.payroll.allowance_option.index', compact('allowanceOptions'));
    }

    public function create()
    {
        return view('pages.payroll.allowance_option.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:20',
        ]);

        // $created_by = Auth::user()->first_name.' '.Auth::user()->last_name;
        $created_by = Auth::user()->id;

        $allowanceOption = new AllowanceOption();

        $allowanceOption->name = $request->input('name');
        $allowanceOption->status = $request->input('status');
        $allowanceOption->created_by = $created_by;
        $allowanceOption->save();

        return redirect()->route('allowance_option.index')->with([
            'message' => 'successfully created !',
            'alert-type' => 'success'
        ]);
    }

    public function show(AllowanceOption $AllowanceOption)
    {
        return redirect()->route('AllowanceOption.index');
    }

    public function edit(AllowanceOption $AllowanceOption)
    {

        $AllowanceOptions = AllowanceOption::join('users', 'users.id', '=', 'payslip_types.created_by')
                           ->select('payslip_types.*', 'users.name')
                           ->get();
        return view('pages.payroll.payslip_type.edit', compact('AllowanceOption', 'AllowanceOptions'));
    }

    public function update(Request $request, AllowanceOption $allowanceOption)
    {
        $request->validate([
            'name' => 'required|max:20',
        ]);

        $created_by = Auth::user()->id;

        $allowanceOption->name = $request->input('name');
        $allowanceOption->status = $request->input('status');
        $allowanceOption->created_by = $created_by;
        // dd($allowanceOption);
        $allowanceOption->update();

        return redirect()->route('allowance_option.index')->with([
            'message' => 'successfully created !',
            'alert-type' => 'success'
        ]); 
    }

    public function destroy(AllowanceOption $allowanceOption)
    {
        $allowanceOption->delete();
        return back()->with([
            'message' => 'successfully deleted !',
            'alert-type' => 'danger'
        ]);
    }


}
