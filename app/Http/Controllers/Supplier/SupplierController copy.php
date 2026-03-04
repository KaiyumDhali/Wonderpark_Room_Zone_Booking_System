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
        $suppliers = Supplier::all();
        return view('pages.supplier.suppliers.index', compact('suppliers'));
    }

    public function create() {
        return view('pages.supplier.suppliers._supplier-add');
    }

    public function store(Request $request)
    {

        //        $request->validate([
        //            'supplier_name' => 'required|string|max:255',
        //        ]);

        $request->validate([
            'supplier_name' => [
                'required',
                'string',
                'max:255',
                'unique:suppliers,supplier_name',
            ],
        ], [
            'supplier_name.unique' => 'The supplier name has already been taken.',
        ]);

        $type = $request->sumite_type ?? 0;

        DB::beginTransaction();

        try {

            $finance_account = new FinanceAccount();
            $finance_account->account_company_code = '01';
            $finance_account->financegroup_id = '11';
            $finance_account->account_group_code = '400010001';
            $finance_account->account_name = $request->supplier_name;
            $finance_account->account_mobile = $request->supplier_mobile;
            $finance_account->account_email = $request->supplier_email;
            $finance_account->account_address = $request->supplier_address;
            $finance_account->account_status = empty($request['status']) ? 1 : $request['status'];
            $finance_account->account_done_by = auth()->user()->name;
            $finance_account->save();

            $supplier = new Supplier();
            $supplier->ac_id = $finance_account->id;
            $supplier->supplier_code = $request->supplier_code;
            $supplier->supplier_name = $request->supplier_name;
            $supplier->supplier_proprietor_name = $request->supplier_proprietor_name;
            $supplier->supplier_mobile = $request->supplier_mobile;
            $supplier->supplier_email = $request->supplier_email;
            $supplier->supplier_address = $request->supplier_address;
            $supplier->representative_name = $request->representative_name;
            $supplier->representative_mobile = $request->representative_mobile;
            $supplier->start_date = $request->start_date;
            $supplier->status = empty($request['status']) ? 1 : $request['status'];
            $supplier->done_by = Auth::user()->name;
            $supplier->save();

            $supplier_ledger = new SupplierLedger();
            $supplier_ledger->supplier_id = $supplier->id;
            //  $supplier_ledger->is_previous_due = empty($request['is_previous_due']) ? 0 : $request['is_previous_due'];
            $supplier_ledger->is_previous_due = 1;
            $supplier_ledger->credit = empty($request['is_previous_due']) ? 0 : $request['is_previous_due'];
            $supplier_ledger->ledger_date = date('Y-m-d', strtotime($supplier->created_at));
            $supplier_ledger->invoice_no = empty($request['supplier_invoice_no']) ? 'Previous Due' : $request['supplier_invoice_no'];
            $supplier_ledger->remarks = empty($request['remarks']) ? 'Previous Due' : $request['remarks'];
            $supplier_ledger->status = empty($request['status']) ? 1 : $request['status'];
            $supplier_ledger->done_by = Auth::user()->first_name . ' ' . Auth::user()->last_name;
            $supplier_ledger->save();
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

    public function show(Supplier $supplier) {
        
        return view('pages.supplier.suppliers._supplier-show', compact('supplier'));
    }

    public function edit(Supplier $supplier) {
        return view('pages.supplier.suppliers._supplier-update', compact('supplier'));
    }

    public function update(Request $request, Supplier $supplier)
    {

        //        $request->validate([
        //            'supplier_name' => 'required|string|max:255',
        //        ]);

        //        dd($supplier);

        $request->validate([
            'supplier_name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('suppliers', 'supplier_name')->ignore($supplier->id),
            ],
        ], [
            'supplier_name.unique' => 'The supplier name has already been taken.',
        ]);

        DB::beginTransaction();

        try {

            $finance_account = FinanceAccount::where('account_name', $supplier->supplier_name)->first();
            //dd($finance_account);
            $supplier->update([
                'supplier_code' => $request->supplier_code,
                'supplier_name' => $request->supplier_name,
                'supplier_proprietor_name' => $request->supplier_proprietor_name,
                'supplier_mobile' => $request->supplier_mobile,
                'supplier_email' => $request->supplier_email,
                'supplier_address' => $request->supplier_address,
                'representative_name' => $request->representative_name,
                'representative_mobile' => $request->representative_mobile,
                'start_date' => $request->start_date,
                'status' => $request->status,
            ]);

            $finance_account->financegroup_id = '11';
            $finance_account->account_group_code = '400010001';
            $finance_account->account_name = $request->supplier_name;
            $finance_account->account_mobile = $request->supplier_mobile;
            $finance_account->account_email = $request->supplier_email;
            $finance_account->account_address = $request->supplier_address;
            $finance_account->account_company_code = '01';
            $finance_account->account_status = 1;
            $finance_account->account_done_by = auth()->user()->name;


            $finance_account->save();

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
