<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Models\FinanceGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;

class FinanceGroupController extends Controller {

    function __construct() {
        $this->middleware('permission:read finance group|write finance group|create finance group', ['only' => ['index', 'show']]);
        $this->middleware('permission:create finance group', ['only' => ['create', 'store']]);
        $this->middleware('permission:write finance group', ['only' => ['edit', 'update', 'destroy']]);

    }

    public function getParentChild($group_code) {

//        $parentChild = FinanceGroup::where('category_id', $category_id)->pluck('sub_category_name', 'id')->toArray();
//        $parentChild = FinanceGroup::where('group_code', 'like', $group_code.'%')->get();

        $parentChild = FinanceGroup::where('group_code', 'like', $group_code . '%')->pluck('group_name', 'group_code')->toArray();
        return response()->json($parentChild);
    }


    public function index() {
//        $finances = FinanceGroup::all();

        $finances = FinanceGroup::select('finance_groups.*', 'f2.group_name as group_parent_name')
                ->join('finance_groups as f2', 'f2.group_code', '=', 'finance_groups.group_parents')
                ->get();

//        dd($finances);

        $financeMasterGroup = FinanceGroup::where('group_parents', 'ROOT')->pluck('group_name', 'group_code')->all();
//        $financeGroupList = FinanceGroup::pluck('group_name', 'group_code')->all();
        return view('pages.finance.group.index', compact('finances', 'financeMasterGroup'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {

        $financeGroupList = FinanceGroup::pluck('group_name', 'group_code')->all();
        return view('pages.finance.group.group-add', compact('financeGroupList'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {

        
        $request->validate([
            'group_name' => [
                'required',
                'string',
                'max:255',
                'unique:finance_groups,group_name',
            ],
                ], [
            'group_name.unique' => 'The account name has already been taken.',
        ]);

        $groupParents = $request->group_parents;
        $financeGroup = new FinanceGroup();
//        $last_id = $financeGroup->orderBy('id', 'desc')->value('group_parents');
        $count = $financeGroup::where('group_parents', $groupParents)->count();

//        $results = $financeGroup::where('group_code', '>', $last_id)->where($groupParents)->get();
//        dd($last_id);
//        $prefix_length = strlen($request->group_parents);
//        $last_prefix = substr($last_id, 0, $prefix_length);
//
//        $last_group_number = (int) substr($last_id, $prefix_length);
//
//        if ($last_prefix !== $request->group_parents) {
//            $new_group_number = 1;
//        } else {
//            $new_group_number = $last_group_number + 1;
//        }

        $count = $count + 1;
        $new_group_code = $request->group_parents . str_pad($count, 4, '0', STR_PAD_LEFT);

        $financeGroup->group_code = $new_group_code;
        $financeGroup->group_name = strip_tags($request->group_name);
        $financeGroup->group_parents = $request->group_parents;
        $financeGroup->group_company_code = '01';
        $financeGroup->group_status = 1;
        $financeGroup->group_done_by = auth()->user()->name;

//        dd($financeGroup);


        $financeGroup->save();

        return redirect()->route('finances.index')->with([
                    'message' => 'successfully created !',
                    'alert-type' => 'success'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(FinanceGroup $financeGroup) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FinanceGroup $financeGroup) {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, FinanceGroup $financeGroup) {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FinanceGroup $financeGroup) {
        //
    }

}
