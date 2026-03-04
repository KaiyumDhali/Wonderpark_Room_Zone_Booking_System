<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Models\ProductSize;
use Illuminate\Http\Request;

class SizeController extends Controller {

    function __construct() {
        $this->middleware('permission:read size|write size|create size', ['only' => ['index', 'show']]);
        $this->middleware('permission:create size', ['only' => ['create', 'store']]);
        $this->middleware('permission:write size', ['only' => ['edit', 'update', 'destroy']]);
    }

    public function index() {
        $sizes = ProductSize::all();
        return view('pages.product.size.index', compact('sizes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        return view('pages.product.size._size-add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $request->validate([
            'size_name' => 'required|string|max:255',
        ]);

        $input = $request->all();
        $type = $input['sumite_type'] ?? 0;

        ProductSize::create($input);

        if ($type == 1) {
            return redirect()->back()->with([
            'message' => 'Successfully created!',
            'alert-type' => 'success'
            ]);
        }
        if ($type == 0) {
            return redirect()->route('sizes.index')->with([
                'message' => 'successfully create !',
                'alert-type' => 'success'
            ]);
        }

        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Size  $size
     * @return \Illuminate\Http\Response
     */
    public function show(ProductSize $size) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Size  $size
     * @return \Illuminate\Http\Response
     */
    public function edit(ProductSize $size) {
        return view('pages.product.size._size-update', compact('size'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Size  $size
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ProductSize $size) {
        $request->validate([
            'size_name' => 'required|string|max:255',
        ]);

        $size->size_name = $request->size_name;
        $size->status = $request->status;
        $size->update();

        return redirect()->route('sizes.index')->with([
                    'message' => 'successfully update !',
                    'alert-type' => 'info'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Size  $size
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProductSize $size) {
        $size->delete();
        return back()->with([
                    'message' => 'successfully deleted !',
                    'alert-type' => 'danger'
        ]);
    }

    // table select destroy
    public function massDestroy() {
        ProductSize::whereIn('id', request('ids'))->delete();
        return response()->noContent();
    }

}
