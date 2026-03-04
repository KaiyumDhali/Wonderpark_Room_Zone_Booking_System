<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Models\ProductUnit;
use Illuminate\Http\Request;

class UnitController extends Controller {


    function __construct() {
        $this->middleware('permission:read unit|write unit|create unit', ['only' => ['index', 'show']]);
        $this->middleware('permission:create unit', ['only' => ['create', 'store']]);
        $this->middleware('permission:write unit', ['only' => ['edit', 'update', 'destroy']]);
    }

    public function index() {
        $units = ProductUnit::all();
        return view('pages.product.unit.index', compact('units'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        return view('pages.product.unit._unit-add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $request->validate([
            'unit_name' => 'required|string|max:255',
        ]);

        $type = $request->sumite_type ?? 0;

        // dd($type);
        $unit = new ProductUnit();
        $unit->unit_name = $request->unit_name;
//        $unit->unit_slug = $request->unit_slug;
        $unit->unit_value = $request->unit_value;
        $unit->save();

        if ($type == 1) {
            return redirect()->back()->with([
            'message' => 'Successfully created!',
            'alert-type' => 'success'
            ]);
        }
        if ($type == 0) {
            return redirect()->route('units.index')->with([
                'message' => 'Unit successfully create !',
                'alert-type' => 'success'
            ]);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Unit  $unit
     * @return \Illuminate\Http\Response
     */
    public function show(ProductUnit $unit) {
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Unit  $unit
     * @return \Illuminate\Http\Response
     */
    public function edit(ProductUnit $unit) {
        return view('pages.product.unit._unit-update', compact('unit'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Unit  $unit
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        $request->validate([
            'unit_name' => 'required|string|max:255',
        ]);
        $unit = ProductUnit::find($id);
        $unit->unit_name = $request->unit_name;
//        $unit->unit_slug = $request->unit_slug;
        $unit->unit_value = $request->unit_value;
        $unit->save();

        return redirect()->route('units.index')->with([
            'message' => 'Unit successfully update !',
            'alert-type' => 'info'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Unit  $unit
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProductUnit $unit) {
        $unit->delete();
        return back()->with([
            'message' => 'successfully deleted !',
            'alert-type' => 'danger'
        ]);
    }

    // table select destroy
    public function massDestroy() {
        ProductUnit::whereIn('id', request('ids'))->delete();
        return response()->noContent();
    }

}
