<?php

namespace App\Http\Controllers\Product;
use App\Http\Controllers\Controller;

use App\Models\ProductFragrance;
use Illuminate\Http\Request;
use validator;

class ProductFragranceController extends Controller
{
    function __construct() {
        $this->middleware('permission:read product fragrance|write product fragrance|create product fragrance', ['only' => ['index', 'show']]);
        $this->middleware('permission:create product fragrance', ['only' => ['create', 'store']]);
        $this->middleware('permission:write product fragrance', ['only' => ['edit', 'update', 'destroy']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $productFragrances = ProductFragrance::all();
        return view('pages.product.product_fragrance.index', compact('productFragrances'));
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
            'fragrance_name' => 'required|string|max:255',
        ]);

        $input = $request->all();
        ProductFragrance::create($input);

        return redirect()->back()->with([
            'message' => 'Successfully created!',
            'alert-type' => 'success'
        ]);
        // return redirect()->route('product_fragrance.index')->with([
        //     'message' => 'successfully create !',
        //     'alert-type' => 'success'
        // ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(ProductFragrance $productFragrance)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProductFragrance $productFragrance)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ProductFragrance $productFragrance)
    {
        $request->validate([
            'fragrance_name' => 'required|string|max:255',
        ]);

        $productFragrance->fragrance_name = $request->fragrance_name;
        $productFragrance->status = $request->status;
        $productFragrance->update();

        return redirect()->route('product_fragrance.index')->with([
            'message' => 'successfully update !',
            'alert-type' => 'info'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductFragrance $productFragrance)
    {
        $productFragrance->delete();
        return back()->with([
            'message' => 'successfully deleted !',
            'alert-type' => 'danger'
        ]);
    }
}
