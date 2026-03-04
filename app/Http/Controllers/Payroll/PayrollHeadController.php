<?php
namespace App\Http\Controllers\Payroll;
use App\Http\Controllers\Controller;
use App\Models\PayrollHead;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PayrollHeadController extends Controller
{
    function __construct()
    {
         $this->middleware('permission:read payroll head|write payroll head|create payroll head', ['only' => ['index','show']]);
         $this->middleware('permission:create payroll head', ['only' => ['create','store']]);
         $this->middleware('permission:write payroll head', ['only' => ['edit','update','destroy']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $payrollHeads = PayrollHead::orderby('id', 'desc')->get();
        return view('pages.payroll.payroll_head.index', compact('payrollHeads'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData  = $request->validate([
            'name' => 'required|string|max:255|unique:payroll_heads,name',
            'type' => 'nullable|string|max:255',
            'status' => 'nullable|boolean',
            'created_by' => 'nullable|string|max:255',
        ]);
        $cleanedData = array_map(fn($value) => is_string($value) ? Str::of($value)->stripTags()->trim() : $value, $validatedData);
        $cleanedData['created_by'] = Auth::user()->name;
        // dd($cleanedData);
        try {
            PayrollHead::create($cleanedData);
            return back()->with(['message' => 'Successfully created!', 'alert-type' => 'success']);
        } catch (\Exception $e) {
            return back()->with(['message' => 'Failed to create. Please try again.', 'alert-type' => 'danger']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(PayrollHead $payrollHead)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PayrollHead $payrollHead)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:payroll_heads,name,' . $id,
            'type' => 'nullable|string|max:255',
            'status' => 'nullable|boolean',
            'created_by' => 'nullable|string|max:255',
        ]);
        $cleanedData = array_map(fn($value) => is_string($value) ? Str::of($value)->stripTags()->trim() : $value, $validatedData);
        try {
            $empType = PayrollHead::findOrFail($id);
            $empType->update($cleanedData);

            return back()->with(['message' => 'Successfully updated!', 'alert-type' => 'info']);
        } catch (\Exception $e) {
            return back()->with(['message' => 'Failed to update. Please try again.', 'alert-type' => 'danger']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $empType = PayrollHead::findOrFail($id);
            $empType->delete();
            return back()->with(['message' => 'Successfully deleted!', 'alert-type' => 'success']);
        } catch (\Exception $e) {
            return back()->with(['message' => 'Failed to delete. Please try again.', 'alert-type' => 'danger']);
        }
    }
}
