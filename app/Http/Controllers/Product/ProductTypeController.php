<?php
namespace App\Http\Controllers\Product;
use App\Http\Controllers\Controller;

use App\Models\ProductType;
use Illuminate\Http\Request;
use validator;

class ProductTypeController extends Controller
{
    function __construct() {
        $this->middleware('permission:read product type|write product type|create product type', ['only' => ['index', 'show']]);
        $this->middleware('permission:create product type', ['only' => ['create', 'store']]);
        $this->middleware('permission:write product type', ['only' => ['edit', 'update', 'destroy']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $productTypes = ProductType::all();
        return view('pages.product.product_type.index', compact('productTypes'));
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

        $input = $request->all();
        ProductType::create($input);

        return redirect()->back()->with([
            'message' => 'Successfully created!',
            'alert-type' => 'success'
        ]);
        
        
        // return redirect()->route('product_type.index')->with([
        //     'message' => 'successfully create !',
        //     'alert-type' => 'success'
        // ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(ProductType $productType)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProductType $productType)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ProductType $productType)
    {
        $request->validate([
            'type_name' => 'required|string|max:255',
        ]);

        $productType->type_name = $request->type_name;
        $productType->status = $request->status;
        $productType->update();

        return redirect()->route('product_type.index')->with([
            'message' => 'successfully update !',
            'alert-type' => 'info'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductType $productType)
    {
        $productType->delete();
        return back()->with([
            'message' => 'successfully deleted !',
            'alert-type' => 'danger'
        ]);
    }
}
