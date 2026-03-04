<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Models\ProductCategory;
use App\Models\ProductSubCategory;
use Illuminate\Http\Request;

class SubCategoryController extends Controller {

    function __construct() {
        $this->middleware('permission:read sub category|write sub category|create sub category', ['only' => ['index', 'show']]);
        $this->middleware('permission:create sub category', ['only' => ['create', 'store']]);
        $this->middleware('permission:write sub category', ['only' => ['edit', 'update', 'destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {

        $sub_categories = ProductSubCategory::join('product_categories', 'product_categories.id', '=', 'product_sub_categories.category_id')
                        ->select('product_sub_categories.*', 'product_categories.category_name')
                        ->orderby('id', 'desc')->get();

        // $sub_categories = ProductSubCategory::orderby('id', 'desc')->get();
        return view('pages.product.sub_category.index', compact('sub_categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $allCategories = ProductCategory::pluck('category_name', 'id')->all();
        return view('pages.product.sub_category._sub_category-add', compact('allCategories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $request->validate([
            'sub_category_name' => 'required|string|max:255',
        ]);

        $input = $request->all();
        $type = $input['sumite_type'] ?? 0;
        ProductSubCategory::create($input);

        if ($type == 1) {
            return redirect()->back()->with([
            'message' => 'Successfully created!',
            'alert-type' => 'success'
            ]);
        }
        if ($type == 0) {
            return redirect()->route('sub_category.index')->with([
                'message' => 'successfully create !',
                'alert-type' => 'success'
            ]);
        }

        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SubCategory  $subCategory
     * @return \Illuminate\Http\Response
     */
    public function show(ProductSubCategory $subCategory) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SubCategory  $subCategory
     * @return \Illuminate\Http\Response
     */
    public function edit(ProductSubCategory $subCategory) {
        $allCategories = ProductCategory::pluck('category_name', 'id')->all();
        return view('pages.product.sub_category._sub_category-update', compact('subCategory', 'allCategories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SubCategory  $subCategory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ProductSubCategory $subCategory) {
        $request->validate([
            'sub_category_name' => 'required|string|max:255',
        ]);

        $subCategory->category_id = $request->category_id;
        $subCategory->sub_category_name = $request->sub_category_name;
        $subCategory->status = $request->status;
        $subCategory->update();

        return redirect()->route('sub_category.index')->with([
                    'message' => 'successfully update !',
                    'alert-type' => 'info'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SubCategory  $subCategory
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProductSubCategory $subCategory) {
        $subCategory->delete();
        return back()->with([
                    'message' => 'successfully deleted !',
                    'alert-type' => 'danger'
        ]);
    }

    // table select destroy
    public function massDestroy() {
        ProductSubCategory::whereIn('id', request('ids'))->delete();
        return response()->noContent();
    }

}
