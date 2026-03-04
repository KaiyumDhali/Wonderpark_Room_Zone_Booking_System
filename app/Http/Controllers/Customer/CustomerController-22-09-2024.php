<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\CustomerType;
use App\Models\CustomerLedger;
use Illuminate\Http\Request;
use App\Http\Requests\CustomerRequest;
use App\Models\FinanceAccount;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Auth;

class CustomerController extends Controller {

    function __construct()
    {
         $this->middleware('permission:read customer|write customer|create customer', ['only' => ['index','show']]);
         $this->middleware('permission:create customer', ['only' => ['create','store']]);
         $this->middleware('permission:write customer', ['only' => ['edit','update','destroy']]);
    }
    public function index() {
        // $customers = Customer::all();
        $customers = Customer::leftJoin('customer_types', 'customer_types.id', '=', 'customers.customer_type')
        ->select('customers.*', 'customer_types.type_name')
        ->orderby('id', 'desc')->get();

        return view('pages.customer.customers.index', compact('customers'));
    }

    public function create() {
        $customerTypes = CustomerType::where('status', 1)->pluck('type_name','id')->all();
        return view('pages.customer.customers._customer-add', compact('customerTypes'));
    }

    public function store(Request $request) {
        $request->validate([
            'customer_name' => [
                'required',
                'string',
                'max:255',
                'unique:customers,customer_name',
            ],
                ], [
            'customer_name.unique' => 'The customer name has already been taken.',
        ]);

        $type = $request->sumite_type ?? 0;

        DB::beginTransaction();

        try {
            $customer = new Customer();
            $customer->customer_type = $request->customer_type;
            $customer->customer_code = $request->customer_code;
            $customer->customer_name = $request->customer_name;
            $customer->customer_gender = $request->customer_gender;
            $customer->customer_DOB = $request->customer_DOB;
            $customer->customer_mobile = $request->customer_mobile;
            $customer->customer_email = $request->customer_email;
            $customer->nid_number = $request->nid_number;
            $customer->vat_reg_no = $request->vat_reg_no;
            $customer->tin_no = $request->tin_no;
            $customer->trade_license = $request->trade_license;
            $customer->discount_rate = $request->discount_rate;
            $customer->security_deposit = $request->security_deposit;
            $customer->credit_limit = $request->credit_limit;
            $customer->customer_area = $request->customer_area;
            $customer->customer_address = $request->customer_address;
            $customer->shipping_address = $request->shipping_address;
            $customer->shipping_contact = $request->shipping_contact;
            $customer->status = empty($request['status']) ? 1 : $request['status'];
            $customer->done_by = auth()->user()->name;
            $customer->save();

            $customer_ledger = new CustomerLedger();
            $customer_ledger->customer_id = $customer->id;
            $customer_ledger->is_previous_due = 1;
            $customer_ledger->debit = empty($request['is_previous_due']) ? 0 : $request['is_previous_due'];
            $customer_ledger->ledger_date = date('Y-m-d', strtotime($customer->created_at));
            $customer_ledger->invoice_no = empty($request['invoice_no']) ? 'Previous Due' : $request['invoice_no'];
            $customer_ledger->remarks = empty($request['remarks']) ? 'Previous Due' : $request['remarks'];

            $customer_ledger->status = empty($request['status']) ? 1 : $request['status'];
            $customer_ledger->done_by = auth()->user()->name;
            $customer_ledger->save();

            $finance_account = new FinanceAccount();
            $finance_account->financegroup_id = '7';
            $finance_account->account_group_code = '100020001';
            $finance_account->account_name = $request->customer_name;
            $finance_account->account_mobile = $request->customer_mobile;
            $finance_account->account_email = $request->customer_email;
            $finance_account->account_address = $request->customer_address;
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


        if ($type == 1) {
            return redirect()->back()->with([
            'message' => 'Successfully created!',
            'alert-type' => 'success'
            ]);
        }
        if ($type == 0) {
            return redirect()->route('customers.index')->with([
                'message' => 'successfully created !',
                'alert-type' => 'success'
            ]);
        }        
    }


    public function show($id) {
        $customer = Customer::leftJoin('customer_types', 'customer_types.id', '=', 'customers.customer_type')
        ->select('customers.*', 'customer_types.type_name')->find($id);

        return view('pages.customer.customers._customer-show', compact('customer'));
    }

    public function edit(Customer $customer) {
        $customerTypes = CustomerType::where('status', 1)->pluck('type_name','id')->all();
        return view('pages.customer.customers._customer-update', compact('customer','customerTypes'));
    }

    public function update(Request $request, Customer $customer) {

        $request->validate([
            'customer_name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('customers', 'customer_name')->ignore($customer->id),
            ],
                ], [
            'customer_name.unique' => 'The customer name has already been taken.',
        ]);
        DB::beginTransaction();
        try {
        $finance_account = FinanceAccount::where('account_name', $customer->customer_name)->first();
        $customer->update([
            'customer_type' => $request->customer_type,
            'customer_code' => $request->customer_code,
            'customer_name' => $request->customer_name,
            'customer_gender' => $request->customer_gender,
            'customer_DOB' => $request->customer_DOB,
            'customer_mobile' => $request->customer_mobile,
            'customer_email' => $request->customer_email,
            'nid_number' => $request->nid_number,
            'vat_reg_no' => $request->vat_reg_no,
            'tin_no' => $request->tin_no,
            'trade_license' => $request->trade_license,
            'discount_rate' => $request->discount_rate,
            'security_deposit' => $request->security_deposit,
            'credit_limit' => $request->credit_limit,
            'customer_area' => $request->customer_area,
            'customer_address' => $request->customer_address,
            'shipping_address' => $request->shipping_address,
            'shipping_contact' => $request->shipping_contact,
            'status' => $request->status,
        ]);
        $finance_account->financegroup_id = '7';
        $finance_account->account_group_code = '100020001';
        $finance_account->account_name = $request->customer_name;
        $finance_account->account_mobile = $request->customer_mobile;
        $finance_account->account_email = $request->customer_email;
        $finance_account->account_address = $request->customer_address;
        $finance_account->account_company_code = '01';
        $finance_account->account_status = 1;
        $finance_account->account_done_by = auth()->user()->name;
        $finance_account->save();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
        }
        return redirect()->route('customers.index')->with([
            'message' => 'successfully update !',
            'alert-type' => 'success'
        ]);
    }

    public function destroy(Customer $customer) {
        $customer->delete();
        return back()->with([
                    'message' => 'successfully deleted !',
                    'alert-type' => 'danger'
        ]);
    }

    // table select destroy
    public function massDestroy() {
        Customer::whereIn('id', request('ids'))->delete();
        return response()->noContent();
    }

}
