<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\CustomerLedger;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Auth;

class CustomerLedgerController extends Controller {

    function __construct()
    {
         $this->middleware('permission:read customer ledger|create customer ledger', ['only' => ['index','show']]);
         $this->middleware('permission:create customer ledger', ['only' => ['create','store']]);
         //$this->middleware('permission:write customer ledger', ['only' => ['edit','update','destroy']]);
    }

    public function customerDetails($id) {
        $customerDetails = CustomerLedger::with('customer')->where('customer_id',$id)->get();
        return response()->json($customerDetails);
    }
        
    public function index() {
        $customer_ledgers = CustomerLedger::leftJoin('customers', 'customers.id', '=', 'customer_ledgers.customer_id')
                        ->select('customer_ledgers.*', 'customers.customer_name')
                        ->orderby('id', 'desc')
                        ->get();

        $customers = Customer::where('status', 1)->get();
        return view('pages.customer.customer_ledgers.index', compact('customer_ledgers', 'customers'));
    }

    public function create() {       
        return view('admin.customer_ledgers.create', compact('allCustomers'));
    }

    public function store(Request $request) {


        $input = $request->all();

//        $latestInvoice = CustomerLedger::latest()->first();
//        if ($latestInvoice) {
//            $invoiceNumber = 'INV' . str_pad((int) substr($latestInvoice->invoice_no, 4) + 1, 5, '0', STR_PAD_LEFT);
//        } else {
//            $invoiceNumber = 'INV00001';
//        }
//        $input['customer_id'] = $request->customer_id;
//        $input['invoice_no'] = $invoiceNumber;
        $input['ledger_date'] = Date::now();
        $input['status'] = empty($input['status']) ? 1 : $input['status'];
        $input['done_by'] =Auth::user()->first_name.' '.Auth::user()->last_name;

        CustomerLedger::create($input);
        return redirect()->route('customer_ledgers.index')->with([
                    'message' => 'successfully created !',
                    'alert-type' => 'success'
        ]);

//        $request->validate([
//            'customer_id' => 'required',
//            'ledger_date' => 'required',
//        ]);
//
//        dd($request);
//        die();
//
//        $customer_ledger = new CustomerLedger();
//        $customer_ledger->customer_id = $request->customer_id;
//        $customer_ledger->ledger_date = Date::now();
//        $customer_ledger->invoice_no = 'INV0001';
//        $customer_ledger->debit = empty($request['debit']) ? 0 : $request['debit'];
////        $customer_ledger->credit = empty($request['credit']) ? 0 : $request['credit'];
//        $customer_ledger->payment_type = 'cash';
////        $customer_ledger->bank_name = $request->bank_name;
////        $customer_ledger->chaque_no = $request->chaque_no;
////        $customer_ledger->chaque_date = $request->chaque_date;
////        $customer_ledger->card_no = $request->card_no;
////        $customer_ledger->bkash_merchant_number = empty($request['bkash_merchant_number']) ? 0 : $request['bkash_merchant_number'];
////        $customer_ledger->bkash_payment_number = empty($request['bkash_payment_number']) ? 0 : $request['bkash_payment_number'];
////        $customer_ledger->bkash_trx_id = empty($request['bkash_trx_id']) ? 0 : $request['bkash_trx_id'];
//        $customer_ledger->remarks = empty($request['remarks']) ? 'Previous Due' : $request['remarks'];
////        $customer_ledger->is_previous_due = empty($request['is_previous_due']) ? 0 : $request['is_previous_due'];
//        $customer_ledger->status = empty($request['status']) ? 1 : $request['status'];
//
////        dd($customer_ledger);
//        $customer_ledger->save();
//
//        return redirect()->route('pages.customer.customer_ledgers.index')->with([
//                    'message' => 'successfully created !',
//                    'alert-type' => 'success'
//        ]);
    }


    public function show(CustomerLedger $customerLedger) {
        
    }

    public function edit(CustomerLedger $customerLedger) {
        
    }

    public function update(Request $request, CustomerLedger $customerLedger) {
        
    }

    public function destroy(CustomerLedger $customerLedger) {
        
    }
    
    // table select destroy
    public function massDestroy() {
        CustomerLedger::whereIn('id', request('ids'))->delete();
        return response()->noContent();
    }

}