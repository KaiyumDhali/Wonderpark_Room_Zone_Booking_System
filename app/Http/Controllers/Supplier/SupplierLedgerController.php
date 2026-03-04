<?php
namespace App\Http\Controllers\Supplier;
use App\Http\Controllers\Controller;
use App\Models\CompanySetting;
use App\Models\SupplierLedger;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class SupplierLedgerController extends Controller
{


    function __construct()
    {
         $this->middleware('permission:read supplier ledger|create supplier ledger', ['only' => ['index','show']]);
         $this->middleware('permission:create supplier ledger', ['only' => ['create','store']]);
         //$this->middleware('permission:write supplier ledger', ['only' => ['edit','update','destroy']]);
    }

    public function supplierDetails($id)
    {
        $supplier = Supplier::where('id', $id)->first();
        $supplierDetails = SupplierLedger::where('supplier_id', $id)->get();
        return response()->json([
            'supplier' => $supplier,
            'supplierDetails' => $supplierDetails,
        ]);

    }

    public function supplierLedgerSearch($startDate, $endDate, $supplierID, $pdf)
    {
        $query = "
            SELECT -1 as id,-1 as supplier_id, '$startDate' as ledger_date,'Opening Balance on : $startDate' AS invoice_no,IFNULL( SUM(credit),0) as credit ,IFNULL(SUM(debit),0) as debit,'Opening Balance' as remarks
            FROM `supplier_ledgers` 
            WHERE supplier_id = '$supplierID' AND ledger_date <'$startDate'
            UNION ALL 
            SELECT id,supplier_id,ledger_date,invoice_no,credit,debit,remarks 
            FROM `supplier_ledgers` 
            WHERE supplier_id = '$supplierID' AND ledger_date BETWEEN '$startDate' AND '$endDate'
            ";

        $result = DB::table(DB::raw("($query) AS subquery"))
            ->select('id','supplier_id', 'ledger_date', 'invoice_no','credit','debit','remarks',)
            ->get();

        if ($pdf == "list") {
            return response()->json($result);
        }
        if ($pdf == "pdfurl") {
            $data['start_date'] = $startDate;
            $data['end_date'] = $endDate;
            $companySetting = CompanySetting::where('status', 1)->orderBy('id', 'desc')->first();
            $data['company_name'] = $companySetting->company_name;
            $data['company_logo_one'] = $companySetting->company_logo_one;
            $data['supplierInfo'] = Supplier::where('id',$supplierID)->select('id','supplier_code', 'supplier_name', 'supplier_mobile', 'supplier_email', 'supplier_address')->first();

            $pdf = PDF::loadView('pages.pdf.supplier_ledger_report_pdf', array('supplierLedgers' => $result, 'data' => $data));
            return $pdf->stream(Carbon::now().'-recentstat.pdf');
        }
    }

    public function index()
    {
        $supplier_ledgers = SupplierLedger::leftJoin('suppliers', 'suppliers.id', '=', 'supplier_ledgers.supplier_id')
        ->select('supplier_ledgers.*', 'suppliers.supplier_name')
        ->orderby('id', 'desc')->get();

        $suppliers = Supplier::where('status', 1)->get();

        return view('pages.supplier.supplier_ledgers.index',compact('supplier_ledgers', 'suppliers'));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $input = $request->all();

        // $latestInvoice = SupplierLedger::latest()->first();
        // if ($latestInvoice) {
        //     $invoiceNumber = 'INV' . str_pad((int) substr($latestInvoice->invoice_no, 4) + 1, 5, '0', STR_PAD_LEFT);
        // } else {
        //     $invoiceNumber = 'INV00001';
        // }

        $input['invoice_no'] =  'Supplier Payment';
        $input['ledger_date'] =  Date::now();
        $input['status'] = empty($input['status']) ? 1 : $input['status'];
        $input['done_by'] =Auth::user()->first_name.' '.Auth::user()->last_name;

        SupplierLedger::create($input);
         return redirect()->route('supplier_ledgers.index')->with([
            'message' => 'successfully created !',
            'alert-type' => 'success'
        ]);
    }

    public function show(SupplierLedger $supplierLedger)
    {
        //
    }

    public function edit(SupplierLedger $supplierLedger)
    {
        //
    }

    public function update(Request $request, SupplierLedger $supplierLedger)
    {
        //
    }

    public function destroy(SupplierLedger $supplierLedger)
    {
        //
    }

    // table select destroy
    public function massDestroy()
    {
        SupplierLedger::whereIn('id', request('ids'))->delete();
        return response()->noContent();
    }
}
