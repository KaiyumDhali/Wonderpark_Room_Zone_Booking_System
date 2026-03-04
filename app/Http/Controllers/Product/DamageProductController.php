<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Models\DamageProduct;
use Illuminate\Http\Request;
use App\Models\Supplier;
use App\Models\ProductType;
use App\Models\ProductCategory;
use App\Models\ProductSubCategory;
use App\Models\ProductBrand;
use App\Models\ProductSize;
use App\Models\ProductUnit;
use App\Models\Product;
use App\Http\Requests\ProductRequest;
use Illuminate\Support\Facades\Storage;
use Validator;
use App\Models\CustomerType;
use App\Models\CompanySetting;
use App\Models\Customer;
use App\Models\Warehouse;
use App\Models\Stock;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Date;
use Carbon\Carbon;
use Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\ProductService;
use App\Models\ProductServiceDetail;

use Illuminate\Support\Facades\View;
use App\Models\FinanceAccount;
use App\Models\FinanceTransaction;

use NumberFormatter;
use Rmunate\Utilities\SpellNumber;


use Illuminate\Pagination\LengthAwarePaginator;

class DamageProductController extends Controller
{
    public function index()
    {

        $warehouses = Warehouse::where('status', 1)->get();
        $products = Product::where('status', 1)->get();
        $startDate = Carbon::now()->format('d-m-Y');
        $endDate = Carbon::now()->format('d-m-Y');

        $damage_lists = DamageProduct::all()->groupBy('damage_no');

        return view('pages.product.damage_product.index', compact('damage_lists', 'products', 'warehouses', 'startDate', 'endDate'));
    }

    public function create()
    {
        $warehouses = Warehouse::where('status', 1)->get();
        $products = Product::where('status', 1)->get();
        $units = ProductUnit::get();
        $customers = Customer::where('status', 1)->get();
        $supplierAccounts = FinanceAccount::where('account_status', 1)->where('account_group_code', '400010001')->get();
        // $customerAccounts = FinanceAccount::where('account_status', 1)->where('account_group_code', '100020001')->get();
        $toAccounts = FinanceAccount::where('account_status', 1)->where('account_group_code', '100020002')->get();
        $customerTypes = CustomerType::where('status', 1)->pluck('type_name', 'id')->all();


        return view('pages.product.damage_product.damage_product-add', compact('products', 'warehouses', 'units', 'customers', 'supplierAccounts', 'toAccounts', 'customerTypes'));
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'damage_date' => 'required',
            'supplier_id' => 'required',
            'table_product_id' => 'required',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // get damage_no
        $damageNo = DB::table('invoiceno')->first('damage_no');
        $getDamageNo = $damageNo->damage_no;
        $damageNumber = 'DAM' . str_pad($getDamageNo, 6, '0', STR_PAD_LEFT);
        DB::table('invoiceno')->update([
            'damage_no' => $getDamageNo + 1,
        ]);



        foreach ($request->get('table_product_id') as $key => $product_id) {

            $damageProduct = new DamageProduct();


            $damageProduct->damage_date = $request->input('damage_date');
            $damageProduct->supplier_id = $request->input('supplier_id');
            $damageProduct->warehouse_id = $request->input('warehouse_id');
            $damageProduct->damage_reason = $request->input('damage_reason');
            $damageProduct->damage_no = $damageNumber;

            $damageProduct->is_exchangeable = $request->input('is_exchangeable');
            $damageProduct->is_repairable = $request->input('is_repairable');
            $damageProduct->is_resaleable = $request->input('is_resaleable');

            $damageProduct->product_id = $request->input('table_product_id')[$key];
            $damageProduct->damage_quantity = $request->input('table_product_quantity')[$key];
            $damageProduct->salePrice = $request->input('table_product_price')[$key];
            $damageProduct->purchasePrice = $request->input('table_product_pur_price')[$key];
            $damageProduct->status = 1;
            $damageProduct->done_by = Auth::user()->name;

            // $damageProduct = $request->input('table_product_discount')[$key];
            // $damageProduct = $request->input('table_product_cart_amount')[$key];

            $damageProduct->save();
        }


        // return back()->with([
        //     'message' => 'Damage Product Added Successfully!',
        //     'alert-type' => 'success',
        // ]);

        return redirect()->route('damage.index')->with([
            'message' => 'Damage Product Added Successfully!',
            'alert-type' => 'success'
        ]);
    }


    // public function damageInvoiceDetails($invoiceNo)
    // {

    //     $damage_products = DamageProduct::where('damage_no', $invoiceNo)->with('product')->with('warehouse')->with('supplier')->get();

    //     // dd($damage_products);

    //     $stocks = Stock::with(['product', 'customer_finance_account'])->where('invoice_no', $invoiceNo)->get();
    //     $product_service_detail_id = $stocks[0]->product_service_detail_id;
    //     $productServiceDetail = ProductServiceDetail::select('product_service_details.id as product_service_detail_id', 'product_service_details.service_number', 'product_services.id as product_services_id', 'product_services.product_id', 'product_services.service_location', 'products.product_name')
    //         ->join('product_services', 'product_service_details.product_service_id', '=', 'product_services.id')
    //         ->join('products', 'product_services.product_id', '=', 'products.id')
    //         ->where('product_service_details.id', $product_service_detail_id)->first();

    //     $acid = $stocks[0]->customer_id;
    //     $customerPayment = FinanceTransaction::where('invoice_no', $invoiceNo)->where('acid', $acid)->where('balance_type', 'Cr')->pluck('amount')->first();
    //     $paymentNarration = FinanceTransaction::where('invoice_no', $invoiceNo)->where('acid', $acid)->where('balance_type', 'Cr')->pluck('narration')->first();

    //     return view('pages.product.damage_product.damage_product-show', compact('damage_products','stocks', 'customerPayment', 'paymentNarration', 'productServiceDetail'));
    // }

    public function damageInvoiceDetails($invoiceNo)
    {
        // $damage_products = DamageProduct::where('damage_no', $invoiceNo)->get();
        $damage_products = DamageProduct::where('damage_no', $invoiceNo)->with('product')->with('warehouse')->with('supplier')->get();
        return view('pages.product.damage_product.damage_product-show', compact('damage_products'));
    }

    public function damageInvoiceDetailsPdf($invoices)
    {

        // dd($invoices);

        $companySetting = CompanySetting::where('status', 1)->orderBy('id', 'desc')->first();
        $data['company_name'] = $companySetting->company_name;
        $data['company_address'] = $companySetting->company_address;
        $data['company_logo_one'] = $companySetting->company_logo_one;
        $data['company_mobile'] = $companySetting->company_mobile;

        $damage_products = DamageProduct::where('damage_no', $invoices)->with('product')->with('warehouse')->with('supplier')->get();


        $pdf = PDF::loadView('pages.pdf.damage_report_invoice_wise_pdf', array('damage_products' => $damage_products, 'data' => $data));
        $pdf->setPaper('A4', 'portrait');

        // return view('pages.pdf.damage_report_invoice_wise_pdf', array('damage_products' => $damage_products, 'data' => $data));

        return $pdf->stream(Carbon::now() . '-damage_report_invoice_wise_pdf.pdf');
    }

    public function damageProductListSearch($startDate, $endDate, $warehouseId, $productId, $pdf)
    {
        
        $damageProductDateWise = DB::connection()->select("CALL sp_DamageProductDateWise(?, ?, ?, ?)", array($startDate, $endDate, $warehouseId, $productId));
       
        if ($pdf == "list") {
            return response()->json($damageProductDateWise);
        }
        if ($pdf == "pdfurl") {
            $companySetting = CompanySetting::where('status', 1)->orderBy('id', 'desc')->first();
            $data['company_name'] = $companySetting->company_name;
            $data['company_address'] = $companySetting->company_address;
            $data['company_mobile'] = $companySetting->company_mobile;
            $data['company_logo_one'] = $companySetting->company_logo_one;
            $data['start_date'] = $startDate;
            $data['end_date'] = $endDate;
            $pdf = PDF::loadView('pages.pdf.damage_report_date_wise_pdf', array('damageProductDateWise' => $damageProductDateWise, 'data' => $data));
            // $pdf->setPaper('A4', 'landscape');
            return $pdf->stream(Carbon::now() . '-recentstat.pdf');
        }
    }

    public function show(DamageProduct $damageProduct)
    {
        //
    }

    public function edit(DamageProduct $damageProduct)
    {
        //
    }

    public function update(Request $request, DamageProduct $damageProduct)
    {
        //
    }

    public function destroy(DamageProduct $damageProduct)
    {
        //
    }
}
