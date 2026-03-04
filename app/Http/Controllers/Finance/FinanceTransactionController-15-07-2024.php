<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Models\CompanySetting;
use App\Models\FinanceAccount;
use App\Models\FinanceGroup;
use App\Models\FinanceTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\View;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Validator;
use Auth;
use App\Providers\AppServiceProvider;
use NumberFormatter;
use Rmunate\Utilities\SpellNumber;
use Illuminate\Support\Facades\Storage;
use Dompdf\Dompdf;

class FinanceTransactionController extends Controller
{

    function __construct()
    {
        $this->middleware('permission:read finance transaction|write finance transaction|create finance transaction', ['only' => ['index', 'show']]);
        $this->middleware('permission:create finance transaction', ['only' => ['create', 'store']]);
        $this->middleware('permission:write finance transaction', ['only' => ['edit', 'update', 'destroy']]);

        $this->middleware('permission:read received voucher|write received voucher|create received voucher', ['only' => ['receivedVoucher', 'receivedVoucherDateSearch']]);
        $this->middleware('permission:create received voucher', ['only' => ['receivedVoucherStore']]);

        $this->middleware('permission:read payment voucher|write payment voucher|create payment voucher', ['only' => ['paymentVoucher', 'paymentVoucherDateSearch']]);
        $this->middleware('permission:create payment voucher', ['only' => ['paymentVoucherStore']]);

        $this->middleware('permission:read general ledger', ['only' => ['generalLedger', 'generalLedgerSearch']]);
    }

    // accountNameList
    public function accountNameList()
    {
        $accountNameList = FinanceAccount::pluck('account_name', 'id')->all();
        $acList = FinanceAccount::where('account_status', 1)->where('account_group_code', 'like', '10002%')->whereNotIn('account_group_code', ['100020001'])->pluck('account_name', 'id');
        // return response()->json($accountNameList);
        return response()->json([
            'accountNameList' => $accountNameList,
            'acList' => $acList,
        ]);
    }
    // received voucher
    public function receivedVoucher()
    {
        $startDate = Carbon::now()->format('d-m-Y');
        $endDate = Carbon::now()->format('d-m-Y');

        return view('pages.finance.voucher.received_voucher', compact('startDate', 'endDate'));
    }
    // received voucher search list and PDF
    public function receivedVoucherDateSearch($startDate, $endDate, $pdf)
    {
        // Invoice Type
        $inv = '01CR';
        $SVInv = '01SV';
        // Base query for received vouchers
        $receivedVouchersQuery = FinanceTransaction::join('finance_accounts', 'finance_transactions.acid', '=', 'finance_accounts.id')
            ->select('finance_transactions.*', 'finance_accounts.account_name as acid_name')
            ->where('finance_transactions.balance_type', 'Cr') // Filter by balance_type 'Cr'
            ->when($startDate && $endDate && $startDate !== '0' && $endDate !== '0', function ($query) use ($startDate, $endDate) {
                $query->whereBetween(DB::raw('DATE(voucher_date)'), [$startDate, $endDate]);
            })
            ->where(function ($query) use ($inv, $SVInv) {
                $query->where('voucher_no', 'LIKE', '%' . $inv . '%')
                    ->orWhere(function ($query) use ($SVInv) {
                        $query->where('voucher_no', 'LIKE', '%' . $SVInv . '%')
                            ->where('acid', '!=', $GLOBALS['SalesAccountID'])
                            ->where('to_acc_name', '!=', $GLOBALS['SalesAccountName']);
                    });
            });
        // Handle JSON response
        if ($pdf == "list") {
            $receivedVouchers = $receivedVouchersQuery->orderBy('finance_transactions.voucher_date', 'DESC')->get();
            return response()->json($receivedVouchers);
        }
        // Handle PDF response
        if ($pdf == "pdfurl") {
            $receivedVouchers = $receivedVouchersQuery->orderBy('finance_transactions.voucher_date', 'ASC')->get();
            $companySetting = CompanySetting::where('status', 1)->latest()->first();
            $data = [
                'company_name' => $companySetting->company_name,
                'company_address' => $companySetting->company_address,
                'company_logo_one' => $companySetting->company_logo_one,
                'company_mobile' => $companySetting->company_mobile,
                'start_date' => $startDate,
                'end_date' => $endDate,
            ];

            $pdf = PDF::loadView('pages.pdf.received_voucher_date_search_pdf', ['receivedVouchers' => $receivedVouchers, 'data' => $data]);
            $pdf->setPaper('A4', 'portrait');
            return $pdf->stream(Carbon::now() . '-received_voucher_date_search_pdf.pdf');
        }

        return response()->json(['error' => 'Invalid PDF type specified'], 400); // Handle invalid pdf parameter
    }

    // Voucher PDF
    public function voucherReportPdf($invoices, $voucherType)
    {
        $companySetting = CompanySetting::where('status', 1)->orderBy('id', 'desc')->first();
        $data['company_name'] = $companySetting->company_name;
        $data['company_address'] = $companySetting->company_address;
        // $data['title'] = 'Testing Page Number In Body';
        $data['company_logo_one'] = $companySetting->company_logo_one;

        $voucherNo = FinanceTransaction::where('voucher_no', $invoices)->orderBy('id', 'DESC')->get();
        // dd( $voucherNo);
        if ($voucherType == 'receivedVoucher') {
            $data['title'] = 'Money Receipt';
            $pdf = PDF::loadView('pages.pdf.received_voucher_report_pdf', array('voucherNo' => $voucherNo, 'data' => $data));
            $pdf->setPaper('A4', 'portrait');
            return $pdf->stream('-received_voucher_report_pdf.pdf');
        } else if ($voucherType == 'paymentVoucher') {
            $data['title'] = 'Payment Voucher';
            $pdf = PDF::loadView('pages.pdf.payment_voucher_report_pdf', array('voucherNo' => $voucherNo, 'data' => $data));
            $pdf->setPaper('A4', 'portrait');
            return $pdf->stream('-payment_voucher_report_pdf.pdf');
        } else if ($voucherType == 'debitVoucher') {
            $data['title'] = 'Debit Voucher';
            $pdf = PDF::loadView('pages.pdf.payment_voucher_debit_journal_report_pdf', array('voucherNo' => $voucherNo, 'data' => $data));
            $pdf->setPaper('A4', 'portrait');
            return $pdf->stream('-payment_voucher_debit_journal_report_pdf.pdf');
        } else if ($voucherType == 'paymentJournalVoucher') {
            $data['title'] = 'Journal Voucher';
            $pdf = PDF::loadView('pages.pdf.payment_voucher_debit_journal_report_pdf', array('voucherNo' => $voucherNo, 'data' => $data));
            $pdf->setPaper('A4', 'portrait');
            return $pdf->stream('-payment_voucher_debit_journal_report_pdf.pdf');
        } else if ($voucherType == 'creditVoucher') {
            $data['title'] = 'Credit Voucher';
            $pdf = PDF::loadView('pages.pdf.received_voucher_credit_journal_report_pdf', array('voucherNo' => $voucherNo, 'data' => $data));
            $pdf->setPaper('A4', 'portrait');
            return $pdf->stream('-received_voucher_credit_journal_report_pdf.pdf');
        } else if ($voucherType == 'receivedJournalVoucher') {
            $data['title'] = 'Journal Voucher';
            $pdf = PDF::loadView('pages.pdf.received_voucher_credit_journal_report_pdf', array('voucherNo' => $voucherNo, 'data' => $data));
            $pdf->setPaper('A4', 'portrait');
            return $pdf->stream('-received_voucher_credit_journal_report_pdf.pdf');
        } else {
        }
    }

    // receivedVoucher Store
    public function receivedVoucherStore(Request $request)
    {
        // $data = $request->all();
        // dd($data);

        $validator = Validator::make($request->all(), [
            'date' => 'required',
            'accounts_name' => 'required',
            'received_from' => 'required',
            'amount' => 'required',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        $done_by = Auth::user()->name;

        $date = $request->input('date');
        $acid = $request->input('accounts_name');
        $to_acc_id = $request->input('received_from');
        $amount = $request->input('amount');

        // $narration = $request->input('narration');
        $bank_name = $request->input('bank_name');
        $branch_name = $request->input('branch_name');
        $ac_no = $request->input('ac_no');
        $cheque_type = $request->input('cheque_type');
        $cheque_no = $request->input('cheque_no');
        $cheque_date = $request->input('cheque_date');

        $mobile_bank_name = $request->input('mobile_bank_name');
        $transaction_id = $request->input('transaction_id');

        $acid_name = FinanceAccount::where('id', $acid)->value('account_name');
        $to_acc_name = FinanceAccount::where('id', $to_acc_id)->value('account_name');
        // dd($to_acc_name);

        $formatAmount = formatCurrency($amount);
        if ($bank_name) {
            $narration[] = "$acid_name Received From $to_acc_name, Bank:$bank_name, Branch:$branch_name, A/C:$ac_no, Cheque No:$cheque_no, Date:$cheque_date, Amount:$formatAmount TK\n";
        } else if ($mobile_bank_name) {
            $narration[] = "$acid_name Received From $to_acc_name, Mobile Bank:$mobile_bank_name, Transaction ID:$transaction_id, Amount:$formatAmount TK\n";
        } else {
            $narration[] = "$acid_name Received From $to_acc_name, Cash Received Amount:$formatAmount TK\n";
        }
        $narration = implode("\n", $narration);
        echo $narration;

        DB::beginTransaction();
        try {
            $crVoucher = DB::table('invoiceno')->first('voucher_no');
            $getCrVoucherNo = $crVoucher->voucher_no;
            $crVoucherNo = '01CR' . str_pad($getCrVoucherNo, 6, '0', STR_PAD_LEFT);
            DB::table('invoiceno')->update([
                'voucher_no' => $getCrVoucherNo + 1,
            ]);
            // dd('done');

            $FinanceTransactionCr = FinanceTransaction::create([
                'company_code' => '01',
                'voucher_no' => $crVoucherNo,
                'voucher_date' => $date,
                'acid' => $to_acc_id,
                'to_acc_name' => $acid_name,
                'type' => 'CR',
                'amount' => $amount,
                'balance_type' => 'Cr',
                'narration' => $narration,
                'transaction_date' => $date,
                'transaction_by' => $done_by,
                'done_by' => $done_by,
                'updated_by' => $done_by,
            ]);

            $FinanceTransactionDr = FinanceTransaction::create([
                'company_code' => '01',
                'voucher_no' => $crVoucherNo,
                'voucher_date' => $date,
                'acid' => $acid,
                'to_acc_name' => $to_acc_name,
                'type' => 'CR',
                'amount' => $amount,
                'balance_type' => 'Dr',
                'narration' => $narration,
                'transaction_date' => $date,
                'transaction_by' => $done_by,
                'done_by' => $done_by,
                'updated_by' => $done_by,
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Ops ! Could Not add Received Voucher Entry. DB transaction lost']);
        }
        return back()->with([
            'message' => 'Received Voucher Entry Added Successfully!',
            'alert-type' => 'success'
        ]);
    }
    
    // paymentVoucher
    public function paymentVoucher()
    {
        // dd($GLOBALS['PurchaseAccount'].':'. $GLOBALS['PurchaseAccountID']);
        $startDate = Carbon::now()->format('d-m-Y');
        $endDate = Carbon::now()->format('d-m-Y');
        return view('pages.finance.voucher.payment_voucher', compact('startDate', 'endDate'));
    }

    // received voucher search list PDF
    public function paymentVoucherDateSearch($startDate, $endDate, $pdf)
    {
        // Invoice Type
        $inv = '01CP';
        $SVInv = '01PV';
        // Base query for received vouchers
        $paymentVouchersQuery = FinanceTransaction::join('finance_accounts', 'finance_transactions.acid', '=', 'finance_accounts.id')
            ->select('finance_transactions.*', 'finance_accounts.account_name as acid_name')
            ->where('finance_transactions.balance_type', 'Dr') // Filter by balance_type 'Dr'
            ->when($startDate && $endDate && $startDate !== '0' && $endDate !== '0', function ($query) use ($startDate, $endDate) {
                $query->whereBetween(DB::raw('DATE(voucher_date)'), [$startDate, $endDate]);
            })
            ->where(function ($query) use ($inv, $SVInv) {
                $query->where('voucher_no', 'LIKE', '%' . $inv . '%')
                    ->orWhere(function ($query) use ($SVInv) {
                        $query->where('voucher_no', 'LIKE', '%' . $SVInv . '%')
                            ->where('acid', '!=', $GLOBALS['PurchaseAccountID'])
                            ->where('to_acc_name', '!=', $GLOBALS['PurchaseAccountName']);
                    });
            });
        // Handle JSON response
        if ($pdf == "list") {
            $paymentVouchers = $paymentVouchersQuery->orderBy('finance_transactions.voucher_date', 'DESC')->get();
            return response()->json($paymentVouchers);
        }
        // Handle PDF response
        if ($pdf == "pdfurl") {
            $paymentVouchers = $paymentVouchersQuery->orderBy('finance_transactions.voucher_date', 'ASC')->get();
            $companySetting = CompanySetting::where('status', 1)->latest()->first();
            $data = [
                'company_name' => $companySetting->company_name,
                'company_address' => $companySetting->company_address,
                'company_logo_one' => $companySetting->company_logo_one,
                'company_mobile' => $companySetting->company_mobile,
                'start_date' => $startDate,
                'end_date' => $endDate,
            ];
            $pdf = PDF::loadView('pages.pdf.payment_voucher_date_search_pdf', array('paymentVouchers' => $paymentVouchers, 'data' => $data));
            $pdf->setPaper('A4', 'portrait '); // landscape
            return $pdf->stream(Carbon::now() . '-payment_voucher_date_search_pdf.pdf');
        }

        return response()->json(['error' => 'Invalid PDF type specified'], 400); // Handle invalid pdf parameter
    }

    // receivedVoucher PDF
    public function paymentVoucherReportPdf($invoices)
    {
        $companySetting = CompanySetting::where('status', 1)->orderBy('id', 'desc')->first();
        $data['company_name'] = $companySetting->company_name;
        $data['company_logo_one'] = $companySetting->company_logo_one;
        $data['title'] = 'Testing Page Number In Body';
        // voucherNo data
        $voucherNo = FinanceTransaction::where('voucher_no', $invoices)->orderBy('id', 'DESC')->get();
        $amount = $voucherNo[0]->amount;
        $data['amount'] = $amount;

        // $data['amount'] = $voucherNo[0]->amount;
        $pdf = PDF::loadView('pages.pdf.payment_voucher_report_pdf', array('voucherNo' => $voucherNo, 'data' => $data));
        $pdf->setPaper('A4', 'portrait');
        return $pdf->stream('-payment_voucher_report_pdf.pdf');
    }

    // paymentVoucher Store
    public function paymentVoucherStore(Request $request)
    {
        // $data = $request->all();
        // dd($data);

        $validator = Validator::make($request->all(), [
            'date' => 'required',
            'accounts_name' => 'required',
            'received_to' => 'required',
            'amount' => 'required',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        $done_by = Auth::user()->name;
        $date = $request->input('date');
        $acid = $request->input('accounts_name');
        $to_acc_id = $request->input('received_to');
        $amount = $request->input('amount');
        // $narration = $request->input('narration');
        $bank_name = $request->input('bank_name');
        $branch_name = $request->input('branch_name');
        $ac_no = $request->input('ac_no');
        $cheque_type = $request->input('cheque_type');
        $cheque_no = $request->input('cheque_no');
        $cheque_date = $request->input('cheque_date');
        $mobile_bank_name = $request->input('mobile_bank_name');
        $transaction_id = $request->input('transaction_id');

        $acid_name = FinanceAccount::where('id', $acid)->value('account_name');
        $to_acc_name = FinanceAccount::where('id', $to_acc_id)->value('account_name');
        // dd($acid_name);

        $formatAmount = formatCurrency($amount);
        if ($bank_name) {
            $narration[] = "$acid_name Payment To $to_acc_name, Bank:$bank_name, Branch:$branch_name, A/C:$ac_no, Cheque No:$cheque_no, Date:$cheque_date, Amount:$formatAmount TK\n";
        } else if ($mobile_bank_name) {
            $narration[] = "$acid_name Payment To $to_acc_name, Mobile Bank:$mobile_bank_name, Transaction ID:$transaction_id,  Amount:$formatAmount TK\n";
        } else {
            $narration[] = "$acid_name Payment To $to_acc_name, Cash Payment Amount:$formatAmount TK\n";
        }
        $narration = implode("\n", $narration);
        echo $narration;

        DB::beginTransaction();
        try {
            $cpVoucher = DB::table('invoiceno')->first('voucher_no');
            $getCpVoucherNo = $cpVoucher->voucher_no;
            $cpVoucherNo = '01CP' . str_pad($getCpVoucherNo, 6, '0', STR_PAD_LEFT);
            DB::table('invoiceno')->update([
                'voucher_no' => $getCpVoucherNo + 1,
            ]);
            // dd('done');

            $FinanceTransactionDr = FinanceTransaction::create([
                'company_code' => '01',
                'voucher_no' => $cpVoucherNo,
                'voucher_date' => $date,
                'acid' => $to_acc_id,
                'to_acc_name' => $acid_name,
                'type' => 'CP',
                'amount' => $amount,
                'balance_type' => 'Dr',
                'narration' => $narration,
                'transaction_date' => $date,
                'transaction_by' => $done_by,
                'done_by' => $done_by,
                'updated_by' => $done_by,
            ]);

            $FinanceTransactionCr = FinanceTransaction::create([
                'company_code' => '01',
                'voucher_no' => $cpVoucherNo,
                'voucher_date' => $date,
                'acid' => $acid,
                'to_acc_name' => $to_acc_name,
                'type' => 'CP',
                'amount' => $amount,
                'balance_type' => 'Cr',
                'narration' => $narration,
                'transaction_date' => $date,
                'transaction_by' => $done_by,
                'done_by' => $done_by,
                'updated_by' => $done_by,
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Ops ! Could Not add Payment Voucher Entry. DB transaction lost']);
        }
        return back()->with([
            'message' => 'Payment Voucher Entry Added Successfully!',
            'alert-type' => 'success'
        ]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
        // $data = $request->all();
        // dd($data);

        $validator = Validator::make($request->all(), [
            'date' => 'required',
            'accounts_name' => 'required',
            'received_from' => 'required',
            'amount' => 'required',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $date = $request->input('date');
        $accounts_name = $request->input('accounts_name');
        $received_from = $request->input('received_from');
        $amount = $request->input('amount');
        $narration = $request->input('narration');
        $done_by = Auth::user()->name;

        DB::beginTransaction();
        try {
            $crVoucher = DB::table('invoiceno')->first('cr_voucher_no');
            $getCrVoucherNo = $crVoucher->cr_voucher_no;
            $crVoucherNo = '01CR' . str_pad($getCrVoucherNo, 6, '0', STR_PAD_LEFT);
            DB::table('invoiceno')->update([
                'cr_voucher_no' => $getCrVoucherNo + 1,
            ]);
            // dd('done');

            $FinanceTransactionDr = FinanceTransaction::create([
                'company_code' => '01',
                'voucher_no' => $crVoucherNo,
                'voucher_date' => $date,
                'acid' => $accounts_name,
                'received_acid' => $received_from,
                'type' => 'CR',
                'amount' => $amount,
                'balance_type' => 'Dr',
                'narration' => $narration,
                'transaction_date' => $date,
                'transaction_by' => $done_by,
                'done_by' => $done_by,
                'updated_by' => $done_by,
            ]);

            $FinanceTransactionCr = FinanceTransaction::create([
                'company_code' => '01',
                'voucher_no' => $crVoucherNo,
                'voucher_date' => $date,
                'acid' => $accounts_name,
                'received_acid' => $received_from,
                'type' => 'CR',
                'amount' => $amount,
                'balance_type' => 'Cr',
                'narration' => $narration,
                'transaction_date' => $date,
                'transaction_by' => $done_by,
                'done_by' => $done_by,
                'updated_by' => $done_by,
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Ops ! Could Not add Received Voucher Entry. DB transaction lost']);
        }
        return back()->with([
            'message' => 'Received Voucher Entry Added Successfully!',
            'alert-type' => 'success'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(FinanceTransaction $financeTransaction)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FinanceTransaction $financeTransaction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, FinanceTransaction $financeTransaction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FinanceTransaction $financeTransaction)
    {
        //
    }

    public function generalLedger()
    {
        $accountList = FinanceAccount::pluck('account_name', 'id')->all();
        return view('pages.finance.ledger.index', compact('accountList'));
    }

    public function generalLedgerSearch($accountHead, $startDate, $endDate, $pdf)
    {
        //        dd($request->all());
        $ac_id = $accountHead;
        $start_date = $startDate;
        $end_date = $endDate;
        $start_date2 = '0001-01-01';
        $end_date2 = Carbon::parse($start_date)->subDay();
        $general_ledger = DB::connection()->select("CALL sp_GeneralLedgerByAccount(?, ?, ?, ?, ?)", array($ac_id, $start_date, $end_date, $start_date2, $end_date2));
        //        dd($general_ledger);
        if ($pdf == "list") {
            return response()->json($general_ledger);
        }
        if ($pdf == "pdfurl") {
            $companySetting = CompanySetting::where('status', 1)->orderBy('id', 'desc')->first();
            $data['company_name'] = $companySetting->company_name;
            $data['company_address'] = $companySetting->company_address;
            $data['company_logo_one'] = $companySetting->company_logo_one;
            $data['company_mobile'] = $companySetting->company_mobile;
            $data['start_date'] = $startDate;
            $data['end_date'] = $endDate;
            $data['customerInfo'] = FinanceAccount::where('id', $ac_id)->select('account_name', 'account_mobile', 'account_email', 'account_address')->first();

            $pdf = PDF::loadView('pages.pdf.generalLedger_pdf', array('generalLedger' => $general_ledger, 'data' => $data));
            // $pdf->setPaper('A4', 'landscape');
            return $pdf->stream(Carbon::now() . '-recentstat.pdf');
        }
    }
}