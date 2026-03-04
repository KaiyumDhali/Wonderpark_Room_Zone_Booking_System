<?php

namespace App\Http\Controllers\Payroll;
use App\Http\Controllers\Controller;
use App\Models\IncomeHead;
use Illuminate\Http\Request;
use Auth;

class IncomeHeadController extends Controller
{
    function __construct()
    {
         $this->middleware('permission:read income head|write income head|create income head', ['only' => ['index','show']]);
         $this->middleware('permission:create income head', ['only' => ['create','store']]);
         $this->middleware('permission:write income head', ['only' => ['edit','update','destroy']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $allIncomeHeads = IncomeHead::orderby('id', 'desc')->get();
        return view('pages.payroll.income_head.index', compact('allIncomeHeads'));
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
            'name' => 'required|max:20',
        ]);

        $created_by = Auth::user()->name;

        $incomeHead = new IncomeHead();

        $incomeHead->name = $request->input('name');
        $incomeHead->status = $request->input('status');
        $incomeHead->created_by = $created_by;
        // dd($incomeHead);
        $incomeHead->save();

        return redirect()->route('income_head.index')->with([
            'message' => 'successfully created !',
            'alert-type' => 'success'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(IncomeHead $incomeHead)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(IncomeHead $incomeHead)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, IncomeHead $incomeHead)
    {
        $request->validate([
            'name' => 'required|max:20',
        ]);

        $created_by = Auth::user()->name;

        $incomeHead->name = $request->input('name');
        $incomeHead->status = $request->input('status');
        $incomeHead->created_by = $created_by;
        $incomeHead->update();

        return redirect()->route('income_head.index')->with([
            'message' => 'successfully update !',
            'alert-type' => 'info'
        ]); 
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(IncomeHead $incomeHead)
    {
        //
    }
}
