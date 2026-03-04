<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Models\FinanceAccount;
use App\Models\FinanceGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;


class FinanceAccountController extends Controller
{


    function __construct()
    {
        $this->middleware('permission:read finance account|write finance account|create finance account', ['only' => ['index', 'show']]);
        $this->middleware('permission:create finance account', ['only' => ['create', 'store']]);
        $this->middleware('permission:write finance account', ['only' => ['edit', 'update', 'destroy']]);
    }



    public function index()
    {
        $accounts = FinanceAccount::all();
        //        $financeMasterGroup = FinanceGroup::where('group_parents', 'ROOT')->pluck('group_name', 'group_code')->all();
        $financeGroupList = FinanceGroup::pluck('group_name', 'id')->all();
        //        dd($financeGroupList);
        return view('pages.finance.account.index', compact('accounts', 'financeGroupList'));
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
            'account_name' => [
                'required',
                'string',
                'max:255',
                'unique:finance_accounts,account_name',
            ],
                ], [
            'account_name.unique' => 'The account name has already been taken.',
        ]);
        //        dd($request->all());
        $financeAccount = new FinanceAccount();
        $financeAccount->account_group_code = FinanceGroup::where('id', $request->financegroup_id)->pluck('group_code')->first();
        $financeAccount->account_name = strip_tags($request->account_name);
        $financeAccount->financegroup_id = $request->financegroup_id;
        $financeAccount->account_company_code = '01';
        $financeAccount->account_status = 1;
        $financeAccount->account_done_by = auth()->user()->name;

        //        dd($financeAccount);

        $financeAccount->save();

        return redirect()->route('accounts.index')->with([
            'message' => 'successfully created !',
            'alert-type' => 'success'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(FinanceAccount $financeAccount)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {

        $accounts = FinanceAccount::where('id', $id)->first();
        //    dd($accounts);
        $financeGroupList = FinanceGroup::pluck('group_name', 'id')->all();

        return view('pages.finance.account.update', compact('accounts', 'financeGroupList'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {

        //    dd($id);
        // $group_code = FinanceGroup::where('id', $id)->pluck('group_code')->first();
        // $financeAccount->account_group_code = $group_code;
        // $financeAccount->account_company_code = '01';
        // $financeAccount->account_status = 1;

        $financeAccount = FinanceAccount::find($id);
        $financeAccount->account_name = strip_tags($request->account_name);
        $financeAccount->account_done_by = auth()->user()->name;

        //    dd($financeAccount);

        $financeAccount->save();

        return redirect()->route('accounts.index')->with([
            'message' => 'successfully updated !',
            'alert-type' => 'info'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FinanceAccount $financeAccount)
    {
        //
    }



}
