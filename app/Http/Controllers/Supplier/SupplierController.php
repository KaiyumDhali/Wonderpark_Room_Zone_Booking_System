<?php

namespace App\Http\Controllers\Supplier;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use App\Models\SupplierLedger;
use Illuminate\Http\Request;
use App\Http\Requests\SupplierRequest;
use Auth;
use App\Models\FinanceAccount;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class SupplierController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
         $this->middleware('permission:read supplier|write supplier|create supplier', ['only' => ['index','show']]);
         $this->middleware('permission:create supplier', ['only' => ['create','store']]);
         $this->middleware('permission:write supplier', ['only' => ['edit','update','destroy']]);
        //  $this->middleware('permission:write supplier', ['only' => ['destroy']]);
    }

    public function index() {
        // $suppliers = Supplier::all();
        $suppliers = FinanceAccount::select('finance_accounts.id as id', 'finance_accounts.account_group_code', 'finance_accounts.account_name as supplier_name', 'finance_accounts.account_mobile as supplier_mobile', 'finance_accounts.account_email as supplier_email', 'finance_accounts.account_address as supplier_address', 'finance_accounts.account_status as status', 'suppliers.ac_id', 'suppliers.supplier_code', 'suppliers.supplier_proprietor_name')
            ->leftJoin('suppliers', 'finance_accounts.id', '=', 'suppliers.ac_id')
            ->where('finance_accounts.account_group_code', $GLOBALS['SupplierGroupCode'])
            ->where('finance_accounts.account_status', 1)
            ->orderby('finance_accounts.id', 'desc')->get();
        return view('pages.supplier.suppliers.index', compact('suppliers'));
    }

    public function create() {
        return view('pages.supplier.suppliers._supplier-add');
    }

    public function store(Request $request)
    {
        $request->validate([
            'supplier_name' => [
                'required',
                'string',
                'max:255',
                'unique:finance_accounts,account_name',
            ]
        ], [
            'account_name.unique' => 'The account name has already been taken.',
        ]);
        $type = $request->sumite_type ?? 0;

        DB::beginTransaction();

        try {
            $finance_account = new FinanceAccount();
            $finance_account->account_company_code = '01';
            $finance_account->financegroup_id = '11';
            $finance_account->account_group_code = $GLOBALS['SupplierGroupCode'];
            $finance_account->account_name = $request->supplier_name;
            $finance_account->account_mobile = $request->supplier_mobile;
            $finance_account->account_email = $request->supplier_email;
            $finance_account->account_address = $request->supplier_address;
            $finance_account->account_status = empty($request['status']) ? 1 : $request['status'];
            $finance_account->account_done_by = Auth::user()->name;
            $finance_account->save();

            $supplier = new Supplier();
            $supplier->ac_id = $finance_account->id;
            $supplier->supplier_code = $request->supplier_code;
            $supplier->supplier_name = $request->supplier_name;
            $supplier->supplier_mobile = $request->supplier_mobile;
            $supplier->supplier_email = $request->supplier_email;
            $supplier->supplier_address = $request->supplier_address;
            $supplier->supplier_proprietor_name = $request->supplier_proprietor_name;
            $supplier->representative_name = $request->representative_name;
            $supplier->representative_mobile = $request->representative_mobile;
            $supplier->start_date = $request->start_date;
            $supplier->status = empty($request['status']) ? 1 : $request['status'];
            $supplier->done_by = Auth::user()->name;
            $supplier->save();

            DB::commit();
        } catch (\Exception $e) {
            // Something went wrong, rollback the transaction
            DB::rollback();
            // Optionally handle the exception or log it
        }

        if ($type == 1) {
            return redirect()->back()->with([
            'message' => 'Successfully created!',
            'alert-type' => 'success'
            ]);
        }
        if ($type == 0) {
            return redirect()->route('suppliers.index')->with([
                'message' => 'successfully created !',
                'alert-type' => 'success'
            ]);
        }

    }

    public function show($id) {
        $supplier = FinanceAccount::select('finance_accounts.id as id', 'finance_accounts.account_group_code', 'finance_accounts.account_name as supplier_name', 'finance_accounts.account_mobile as supplier_mobile', 'finance_accounts.account_email as supplier_email', 'finance_accounts.account_address as supplier_address', 'finance_accounts.account_status as status', 'suppliers.ac_id', 'suppliers.supplier_code', 'suppliers.supplier_proprietor_name')
        ->leftJoin('suppliers', 'finance_accounts.id', '=', 'suppliers.ac_id')
        ->find($id);
        return view('pages.supplier.suppliers._supplier-show', compact('supplier'));
    }

    public function edit($id) {
        $supplier = FinanceAccount::select('finance_accounts.id as id', 'finance_accounts.account_group_code', 'finance_accounts.account_name as supplier_name', 'finance_accounts.account_mobile as supplier_mobile', 'finance_accounts.account_email as supplier_email', 'finance_accounts.account_address as supplier_address', 'finance_accounts.account_status as status', 'suppliers.ac_id', 'suppliers.supplier_code', 'suppliers.supplier_proprietor_name')
        ->leftJoin('suppliers', 'finance_accounts.id', '=', 'suppliers.ac_id')
        ->find($id);
        return view('pages.supplier.suppliers._supplier-update', compact('supplier'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'supplier_name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('finance_accounts', 'account_name')->ignore($id),
            ]
        ], [
            'account_name.unique' => 'The account name has already been taken.',
        ]);
        DB::beginTransaction();

        try {
            $finance_account = FinanceAccount::find($id);
            $finance_account->account_name = $request->supplier_name;
            $finance_account->account_mobile = $request->supplier_mobile;
            $finance_account->account_email = $request->supplier_email;
            $finance_account->account_address = $request->supplier_address;
            $finance_account->account_status = empty($request['status']) ? 1 : $request['status'];
            $finance_account->account_updated_by = auth()->user()->name;
            $finance_account->save();
            //dd($finance_account);
            $supplier = Supplier::where('ac_id', $id)->first();
            $supplier->update([
                'supplier_code' => $request->supplier_code,
                'supplier_name' => $request->supplier_name,
                'supplier_mobile' => $request->supplier_mobile,
                'supplier_email' => $request->supplier_email,
                'supplier_address' => $request->supplier_address,
                'supplier_proprietor_name' => $request->supplier_proprietor_name,
                'representative_name' => $request->representative_name,
                'representative_mobile' => $request->representative_mobile,
                'status' => $request->status,
            ]);

            DB::commit();
        } catch (\Exception $e) {
            // Something went wrong, rollback the transaction
            DB::rollback();
            // Optionally handle the exception or log it
        }

        return redirect()->route('suppliers.index')->with(['message' => 'successfully update !', 'alert-type' => 'info']);
    }

    public function destroy(Supplier $supplier) {
        $supplier->delete();
        return back()->with([
                    'message' => 'successfully deleted !',
                    'alert-type' => 'danger'
        ]);
    }

    // table select destroy
    public function massDestroy() {
        Supplier::whereIn('id', request('ids'))->delete();
        return response()->noContent();
    }

}
