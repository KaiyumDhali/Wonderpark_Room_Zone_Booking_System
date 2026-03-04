<?php

namespace App\Http\Controllers\Payroll;
use App\Http\Controllers\Controller;

use App\Models\DeductionHead;
use Illuminate\Http\Request;
use Auth;

class DeductionHeadController extends Controller
{
    function __construct()
    {
         $this->middleware('permission:read deduction head|write deduction head|create deduction head', ['only' => ['index','show']]);
         $this->middleware('permission:create deduction head', ['only' => ['create','store']]);
         $this->middleware('permission:write deduction head', ['only' => ['edit','update','destroy']]);
    }

    public function index()
    {
        
        $deductionHeads = DeductionHead::join('users', 'users.id', '=', 'deduction_heads.created_by')
                           ->select('deduction_heads.*', 'users.name as created_by')
                           ->get();
        return view('pages.payroll.deduction_head.index', compact('deductionHeads'));
    }

    public function create()
    {
        return view('pages.payroll.deduction_head.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:20',
        ]);

        // $created_by = Auth::user()->first_name.' '.Auth::user()->last_name;
        $created_by = Auth::user()->id;

        $DeductionHead = new DeductionHead();

        $DeductionHead->name = $request->input('name');
        $DeductionHead->status = $request->input('status');
        $DeductionHead->created_by = $created_by;
        $DeductionHead->save();

        return redirect()->route('deduction_head.index')->with([
            'message' => 'successfully created!',
            'alert-type' => 'success'
        ]);
    }

    public function show(DeductionHead $DeductionHead)
    {
        return redirect()->route('deduction_head.index');
    }

    public function edit(DeductionHead $DeductionHead)
    {

        $deductionHeads = DeductionHead::join('users', 'users.id', '=', 'deduction_heads.created_by')
                           ->select('deduction_heads.*', 'users.name as created_by')
                           ->get();
        return view('pages.payroll.deduction_head.edit', compact('deductionHeads'));
    }

    public function update(Request $request, DeductionHead $DeductionHead)
    {
        $request->validate([
            'name' => 'required|max:20',
        ]);

        $created_by = Auth::user()->id;

        $DeductionHead->name = $request->input('name');
        $DeductionHead->status = $request->input('status');
        $DeductionHead->created_by = $created_by;
        // dd($DeductionHead);
        $DeductionHead->update();

        return redirect()->route('deduction_head.index')->with([
            'message' => 'successfully update !',
            'alert-type' => 'info'
        ]); 
    }

    public function destroy(LoanOption $loanOption)
    {
        $loanOption->delete();
        return back()->with([
            'message' => 'successfully deleted !',
            'alert-type' => 'danger'
        ]);

    }
}
