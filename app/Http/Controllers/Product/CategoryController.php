<?php

namespace App\Http\Controllers\Product;

use App\Models\ProductCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CategoryController extends Controller {

    function __construct() {
        $this->middleware('permission:read category|write category|create category', ['only' => ['index', 'show']]);
        $this->middleware('permission:create category', ['only' => ['create', 'store']]);
        $this->middleware('permission:write category', ['only' => ['edit', 'update', 'destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $categories = ProductCategory::orderby('id', 'desc')->get();
        return view('pages.product.category.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        return view('pages.product.category._category-add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $request->validate([
            'category_name' => 'required',
        ]);

        $input = $request->all();
        $type = $input['sumite_type'] ?? 0;
        ProductCategory::create($input);

        if ($type == 1) {
            return redirect()->back()->with([
            'message' => 'Successfully created!',
            'alert-type' => 'success'
            ]);
        }
        if ($type == 0) {
            return redirect()->route('categories.index')->with([
                'message' => 'successfully created !',
                'alert-type' => 'success'
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(ProductCategory $category) {
        // return view('admin.categories.show', compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        $category = ProductCategory::find($id);
        return view('pages.product.category._category-update', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        // $request->validate([
        //     'category_name' => 'required',
        // ]);

        $category = ProductCategory::find($id);
        $category->category_name = $request->category_name;
        $category->status = $request->status;
        $category->update();

        return redirect()->route('categories.index')->with([
                    'message' => 'successfully updated !',
                    'alert-type' => 'info'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $category = ProductCategory::find($id);
        $category->delete();
        return back()->with([
                    'message' => 'successfully deleted !',
                    'alert-type' => 'danger'
        ]);
    }

}