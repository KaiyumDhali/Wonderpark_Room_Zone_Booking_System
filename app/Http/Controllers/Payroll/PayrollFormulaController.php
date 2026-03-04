<?php
namespace App\Http\Controllers\Payroll;
use App\Http\Controllers\Controller;

use App\Models\payrollFormula;
use App\Models\PayrollHead;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PayrollFormulaController extends Controller
{

    function __construct()
    {
         $this->middleware('permission:read payroll formula|write payroll formula|create payroll formula', ['only' => ['index','show']]);
         $this->middleware('permission:create payroll formula', ['only' => ['create','store']]);
         $this->middleware('permission:write payroll formula', ['only' => ['edit','update','destroy']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $payrollFormulas = payrollFormula::with('payrollhead:id,name')->orderby('id', 'desc')->get();
        $payrollHead = PayrollHead::where('status', 1)->pluck('name', 'id')->all();

        return view('pages.payroll.formula.payroll_formula', compact('payrollFormulas', 'payrollHead'));
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
        $validatedData  = $request->validate([
            'payroll_head' => 'required',
            'formula' => 'required|string|max:255',
            'remarks' => 'nullable|string',
            'status' => 'nullable|boolean',
            'created_by' => 'nullable|string',
        ]);
        $cleanedData = array_map(fn($value) => is_string($value) ? Str::of($value)->stripTags()->trim() : $value, $validatedData);
        $cleanedData['created_by'] = Auth::user()->name;
        try {
            payrollFormula::create($cleanedData);
            return back()->with(['message' => 'Successfully created!', 'alert-type' => 'success']);
        } catch (\Exception $e) {
            return back()->with(['message' => 'Failed to create. Please try again.', 'alert-type' => 'danger']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(payrollFormula $payrollFormula)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(payrollFormula $payrollFormula)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, payrollFormula $payrollFormula)
    {
        $validatedData = $request->validate([
            'payroll_head' => 'required',
            'formula' => 'required|string|max:255',
            'remarks' => 'nullable|string',
            'status' => 'nullable|boolean',
            'created_by' => 'nullable|string',
        ]);
        $cleanedData = array_map(fn($value) => is_string($value) ? Str::of($value)->stripTags()->trim() : $value, $validatedData);
        try {
            // $payrollFormula = payrollFormula::findOrFail($id);
            $payrollFormula->update($cleanedData);

            return back()->with(['message' => 'Successfully updated!', 'alert-type' => 'info']);
        } catch (\Exception $e) {
            return back()->with(['message' => 'Failed to update. Please try again.', 'alert-type' => 'danger']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(payrollFormula $payrollFormula)
    {
        try {
            $payrollFormula->delete();
            return back()->with(['message' => 'Successfully deleted!', 'alert-type' => 'success']);
        } catch (\Exception $e) {
            return back()->with(['message' => 'Failed to delete. Please try again.', 'alert-type' => 'danger']);
        }
    }
}
