<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Models\ProductColor;
use Illuminate\Http\Request;

class ColorController extends Controller {

    function __construct() {
        $this->middleware('permission:read color|write color|create color', ['only' => ['index', 'show']]);
        $this->middleware('permission:create color', ['only' => ['create', 'store']]);
        $this->middleware('permission:write color', ['only' => ['edit', 'update', 'destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $colors = ProductColor::all();
        return view('pages.product.color.index', compact('colors'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        return view('pages.product.color._color-add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $request->validate([
            'color_name' => 'required|string|max:255',
        ]);
        $input = $request->all();
        $type = $input['sumite_type'] ?? 0;

        ProductColor::create($input);

        if ($type == 1) {
            return redirect()->back()->with([
            'message' => 'Successfully created!',
            'alert-type' => 'success'
            ]);
        }
        if ($type == 0) {
            return redirect()->route('colors.index')->with([
                'message' => 'successfully create !',
                'alert-type' => 'success'
            ]);
        }

        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Color  $color
     * @return \Illuminate\Http\Response
     */
    public function show(ProductColor $color) {
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Color  $color
     * @return \Illuminate\Http\Response
     */
    public function edit(ProductColor $color) {
        return view('pages.product.color._color-update', compact('color'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Color  $color
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ProductColor $color) {

        $request->validate([
            'color_name' => 'required|string|max:255',
        ]);

        $color->color_name = $request->color_name;
        $color->status = $request->status;
        $color->update();

        return redirect()->route('colors.index')->with([
                    'message' => 'successfully update !',
                    'alert-type' => 'info'
        ]);
    }

    public function destroy(ProductColor $color) {
        $color->delete();

        return redirect()->route('colors.index')->with([
                    'message' => 'successfully deleted !',
                    'alert-type' => 'danger'
        ]);
    }

}
