<?php

namespace App\Http\Controllers\Payroll;
use App\Http\Controllers\Controller;

use App\Models\Income;
use Illuminate\Http\Request;
use Auth;

class IncomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'income_head' => 'required',
            // 'title' => 'required|max:100',
            'amount' => 'required',
        ]);

        $created_by = Auth::user()->name;
        $employee_id = $request->input('employee_id');
        $income = new Income();

        $type = $request->input('type');
        if($type == 2){
            $income->percentage = $request->input('percentage');
        }else{
            $income->percentage = 0.00;
        }
        $income->employee_id = $employee_id;
        $income->income_head = $request->input('income_head');
        // $income->title = $request->input('title');
        $income->amount = $request->input('amount');
        $income->type = $type;
        $income->created_by = $created_by;
        // dd($income);
        $income->save();

        return redirect()->route('set_salaries.set_salaries_create', $employee_id )->with([
            'message' => 'successfully created !',
            'alert-type' => 'success'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Income $income)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Income $income)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Income $income)
    {
        $request->validate([
            'income_head' => 'required',
            // 'title' => 'required|max:100',
            'amount' => 'required',
        ]);

        $created_by = Auth::user()->name;
        $employee_id = $request->input('employee_id');
        $type = $request->input('type');
        if($type == 2){
            $income->percentage = $request->input('percentage');
        }else{
            $income->percentage = 0.00;
        }
        $income->employee_id = $employee_id;
        $income->income_head = $request->input('income_head');
        // $income->title = $request->input('title');
        $income->type = $type;
        $income->amount = $request->input('amount');
        $income->created_by = $created_by;
        // dd($income);
        $income->update();

        return redirect()->route('set_salaries.set_salaries_create', $employee_id )->with([
            'message' => 'successfully update !',
            'alert-type' => 'info'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Income $income)
    {
        //
    }
}
