<?php
namespace App\Http\Controllers\Customer;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\CustomerType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;

class CustomerTypeController extends Controller
{
    
    function __construct()
    {
         $this->middleware('permission:read customer type|write customer type|create customer type', ['only' => ['index','show']]);
         $this->middleware('permission:create customer type', ['only' => ['create','store']]);
         $this->middleware('permission:write customer type', ['only' => ['edit','update']]);
         $this->middleware('permission:delete customer type', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $customerTypes = CustomerType::all();
        return view('pages.customer.customer_type.index', compact('customerTypes'));
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
            'type_name' => 'required|string|max:255',
        ]);

        $input = array_map('strip_tags', $request->all());
        CustomerType::create($input);

        return redirect()->route('customer_type.index')->with([
            'message' => 'successfully create !',
            'alert-type' => 'success'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(CustomerType $customerType)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CustomerType $customerType)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CustomerType $customerType)
    {
        $request->validate([
            'type_name' => 'required|string|max:255',
        ]);
        
        $input = array_map('strip_tags', $request->all());
        $customerType->type_name = $input['type_name'];
        $customerType->status = $input['status'];
        $customerType->update();

        return redirect()->route('customer_type.index')->with([
            'message' => 'successfully update !',
            'alert-type' => 'info'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CustomerType $customerType)
    {
        $customerType->delete();
        return back()->with([
            'message' => 'successfully deleted !',
            'alert-type' => 'danger'
        ]);
    }
}
