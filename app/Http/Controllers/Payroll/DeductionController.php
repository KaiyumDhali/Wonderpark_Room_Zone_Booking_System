<?php
namespace App\Http\Controllers\Payroll;
use App\Http\Controllers\Controller;
use App\Models\Deduction;
use Illuminate\Http\Request;
use Auth;


class DeductionController extends Controller
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
            'deduction_head' => 'required',
            'amount' => 'required',
        ]);

        $created_by = Auth::user()->name;
        $employee_id = $request->input('employee_id');

        $deduction = new Deduction();

        $type = $request->input('type');
        if($type == 2){
           $deduction->percentage = $request->input('percentage');
        }else{
            $deduction->percentage = 0.00;
        }
        $deduction->employee_id = $employee_id;
        $deduction->deduction_head = $request->input('deduction_head');
        $deduction->type = $type;
        $deduction->amount = $request->input('amount');
        $deduction->created_by = $created_by;

        // dd($loan);
        $deduction->save();

        return redirect()->route('set_salaries.set_salaries_create', $employee_id )->with([
            'message' => 'successfully created !',
            'alert-type' => 'success'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(deduction $deduction)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(deduction $deduction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Deduction $deduction)
    {
        $request->validate([
            'deduction_head' => 'required',
            'amount' => 'required',
        ]);

        $created_by = Auth::user()->name;
        $employee_id = $request->input('employee_id');

//        $deduction = new Deduction();

        $type = $request->input('type');
        if($type == 2){
           $deduction->percentage = $request->input('percentage');
        }else{
            $deduction->percentage = 0.00;
        }
        $deduction->employee_id = $employee_id;
        $deduction->deduction_head = $request->input('deduction_head');
        $deduction->type = $type;
        $deduction->amount = $request->input('amount');
        $deduction->created_by = $created_by;

        $deduction->update();

        return redirect()->route('set_salaries.set_salaries_create', $employee_id )->with([
            'message' => 'successfully update !',
            'alert-type' => 'success'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Deduction $deduction)
    {
       $deduction->delete();
        return back()->with([
            'message' => 'successfully deleted !',
            'alert-type' => 'danger'
        ]);
    }
}
