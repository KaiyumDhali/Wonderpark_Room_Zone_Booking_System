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

class CustomerController extends Controller
{

    function __construct()
    {
        $this->middleware('permission:read customer|write customer|create customer', ['only' => ['index', 'show']]);
        $this->middleware('permission:create customer', ['only' => ['create', 'store']]);
        $this->middleware('permission:write customer', ['only' => ['edit', 'update', 'destroy']]);
    }
    public function index()
    {
        // $customers = FinanceAccount::select('finance_accounts.id as id', 'finance_accounts.account_group_code', 'finance_accounts.account_name as customer_name', 'finance_accounts.account_mobile as customer_mobile', 'finance_accounts.account_email as customer_email', 'finance_accounts.account_address as customer_address', 'finance_accounts.account_status as status', 'customers.ac_id', 'customers.customer_code', 'customers.shipping_address', 'customers.customer_gender', 'customers.customer_type', 'customer_types.type_name')
        //     ->leftJoin('customers', 'finance_accounts.id', '=', 'customers.ac_id')
        //     ->leftJoin('customer_types', 'customers.customer_type', '=', 'customer_types.id')
        //     ->where('finance_accounts.account_group_code', $GLOBALS['CustomerGroupCode'])
        //     ->where('finance_accounts.account_status', 1)
        //     ->orderby('finance_accounts.id', 'desc')->get();

        $customers = Customer::leftJoin('customer_types', 'customer_types.id', '=', 'customers.customer_type')
            ->select('customers.*', 'customer_types.type_name')
            ->orderby('id', 'desc')->get();
        // dd($customers);
        return view('pages.customer.customers.index', compact('customers'));
    }

    public function create()
    {
        $customerTypes = CustomerType::where('status', 1)->pluck('type_name', 'id')->all();
        return view('pages.customer.customers._customer-add', compact('customerTypes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => [
                'required',
                'string',
                'max:255',
                'unique:finance_accounts,account_name',
            ],
        ], [
            'account_name.unique' => 'The account name has already been taken.',
        ]);
        $type = $request->sumite_type ?? 0;

        DB::beginTransaction();

        try {
            $finance_account = new FinanceAccount();
            $finance_account->financegroup_id = '7';
            $finance_account->account_group_code = $GLOBALS['CustomerGroupCode'];
            $finance_account->account_name = $request->customer_name;
            $finance_account->account_mobile = $request->customer_mobile;
            $finance_account->account_email = $request->customer_email;
            $finance_account->account_address = $request->customer_address;
            $finance_account->account_company_code = '01';
            $finance_account->account_status = 1;
            $finance_account->account_done_by = auth()->user()->name;
            $finance_account->save();

            $customer = new Customer();
            $customer->customer_type = $request->customer_type;
            $customer->ac_id = $finance_account->id;
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


    public function show($id)
    {
        $customer = Customer::leftJoin('customer_types', 'customer_types.id', '=', 'customers.customer_type')
        ->select('customers.*', 'customer_types.type_name')->find($id);

        // $customer = FinanceAccount::select('finance_accounts.id as id', 'finance_accounts.account_group_code', 'finance_accounts.account_name as customer_name', 'finance_accounts.account_mobile as customer_mobile', 'finance_accounts.account_email as customer_email', 'finance_accounts.account_address as customer_address', 'finance_accounts.account_status as status', 'customers.ac_id', 'customers.customer_code', 'customers.shipping_address', 'customers.customer_gender', 'customers.customer_type', 'customer_types.type_name')
        //     ->leftJoin('customers', 'finance_accounts.id', '=', 'customers.ac_id')
        //     ->leftJoin('customer_types', 'customers.customer_type', '=', 'customer_types.id')
        //     ->find($id);

        return view('pages.customer.customers._customer-show', compact('customer'));
    }

    public function edit($id)
    {
        $customerTypes = CustomerType::where('status', 1)->pluck('type_name', 'id')->all();

        $customer = Customer::leftJoin('customer_types', 'customer_types.id', '=', 'customers.customer_type')
        ->select('customers.*', 'customer_types.type_name')->find($id);

        // $customer = FinanceAccount::select('finance_accounts.id as id', 'finance_accounts.account_group_code', 'finance_accounts.account_name as customer_name', 'finance_accounts.account_mobile as customer_mobile', 'finance_accounts.account_email as customer_email', 'finance_accounts.account_address as customer_address', 'finance_accounts.account_status as status', 'customers.ac_id', 'customers.customer_code', 'customers.shipping_address', 'customers.customer_gender', 'customers.customer_type', 'customer_types.type_name')
        //     ->leftJoin('customers', 'finance_accounts.id', '=', 'customers.ac_id')
        //     ->leftJoin('customer_types', 'customers.customer_type', '=', 'customer_types.id')
        //     ->find($id);
        return view('pages.customer.customers._customer-update', compact('customer', 'customerTypes'));
    }

    // public function update(Request $request, $id)
    // {

    //     $request->validate([
    //         'customer_name' => [
    //             'required',
    //             'string',
    //             'max:255',
    //             Rule::unique('finance_accounts', 'account_name')->ignore($id),
    //         ]
    //     ], [
    //         'account_name.unique' => 'The customer name has already been taken.',
    //     ]);
    //     DB::beginTransaction();
    //     try {
    //         $finance_account = FinanceAccount::find($id);
    //         $finance_account->account_name = $request->customer_name;
    //         $finance_account->account_mobile = $request->customer_mobile;
    //         $finance_account->account_email = $request->customer_email;
    //         $finance_account->account_address = $request->customer_address;
    //         $finance_account->account_status = empty($request['status']) ? 1 : $request['status'];
    //         $finance_account->account_updated_by = auth()->user()->name;
    //         $finance_account->save();

    //         $customer = Customer::where('ac_id', $id)->first();
    //         $customer->update([
    //             'customer_type' => $request->customer_type,
    //             'customer_code' => $request->customer_code,
    //             'customer_name' => $request->customer_name,
    //             'customer_gender' => $request->customer_gender,
    //             'customer_DOB' => $request->customer_DOB,
    //             'customer_mobile' => $request->customer_mobile,
    //             'customer_email' => $request->customer_email,
    //             'nid_number' => $request->nid_number,
    //             'vat_reg_no' => $request->vat_reg_no,
    //             'tin_no' => $request->tin_no,
    //             'trade_license' => $request->trade_license,
    //             'discount_rate' => $request->discount_rate,
    //             'security_deposit' => $request->security_deposit,
    //             'credit_limit' => $request->credit_limit,
    //             'customer_area' => $request->customer_area,
    //             'customer_address' => $request->customer_address,
    //             'shipping_address' => $request->shipping_address,
    //             'shipping_contact' => $request->shipping_contact,
    //             'status' => $request->status,
    //         ]);
    //         DB::commit();
    //     } catch (\Exception $e) {
    //         DB::rollback();
    //     }
    //     return redirect()->route('customers.index')->with([
    //         'message' => 'successfully update !',
    //         'alert-type' => 'success'
    //     ]);
    // }


    public function update(Request $request, $id)
    {

        // dd($request->all());


        $request->validate([
            'customer_name' => [
                'required',
                'string',
                'max:255',
            ]
        ]);
        DB::beginTransaction();
        try {


            $customer = Customer::where('id', $id)->first();
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
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
        }
        return redirect()->route('customers.index')->with([
            'message' => 'successfully update !',
            'alert-type' => 'success'
        ]);
    }
    

    public function destroy(Customer $customer)
    {
        $customer->delete();
        return back()->with([
            'message' => 'successfully deleted !',
            'alert-type' => 'danger'
        ]);
    }

    // table select destroy
    public function massDestroy()
    {
        Customer::whereIn('id', request('ids'))->delete();
        return response()->noContent();
    }
}
