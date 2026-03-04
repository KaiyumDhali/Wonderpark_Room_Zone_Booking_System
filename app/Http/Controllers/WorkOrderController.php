<?php

namespace App\Http\Controllers;
use App\Models\WorkOrder;
use App\Models\Client;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Mpdf\Mpdf;

class WorkOrderController extends Controller
{

public function index()
{
    $workOrders = WorkOrder::with('client')->latest()->get();

    return view('pages.work_order.index', compact('workOrders'));
}
 
    public function create()
{
    return view('pages.work_order.add_workorder', [
        'clients' => Client::all(),
        'workOrderNo' => 'WO-' . date('Ymd') . '-' . rand(100,999),
    ]);
}
public function store(Request $request)
{
    $request->validate([
        'client_id' => 'required|exists:clients,id',
        'work_items' => 'required|array|min:1',
        'work_items.*.description' => 'required|string',
        'work_items.*.quantity' => 'required|string',
        'work_items.*.price' => 'required|numeric|min:0',
        'terms' => 'required|array|min:1',
        'terms.*' => 'required|string',
        'subject' => 'required|string',
        'reference' => 'required|string',
        'advance_percent' => 'required|numeric|min:0|max:100',
    ]);

    WorkOrder::create([
        'work_order_no' => $request->work_order_no,
        'client_id' => $request->client_id,
        'work_items' => $request->work_items, // auto JSON
        'terms' => $request->terms,           // auto JSON
        'subject' => $request->subject,           // auto JSON
        'delivery_date' => $request->delivery_date,           // auto JSON
        'advance_percent' => $request->advance_percent,
        'reference' => $request->reference,
    ]);

    return redirect()
        ->route('work-orders.index')
        ->with('success', 'Work Order created successfully');
}

 public function edit(WorkOrder $workOrder)
    {
        $clients = Client::orderBy('name')->get();

        return view('pages.work_order.edit', compact('workOrder', 'clients'));
    }
    public function update(Request $request, WorkOrder $workOrder)
    {
        $request->validate([
            'client_id'        => 'required',
            'delivery_date'    => 'required|date',
            'subject'          => 'required|string',
            'advance_percent'  => 'required|numeric|min:0|max:100',
            'work_items'       => 'required|array|min:1',
            'work_items.*.description' => 'required|string',
            'work_items.*.quantity'    => 'required|string',
            'work_items.*.price'       => 'required|numeric|min:0',
            'terms'            => 'required|array|min:1',
            'terms.*'          => 'required|string',
        ]);

        $workOrder->update([
            'client_id'       => $request->client_id,
            'delivery_date'   => $request->delivery_date,
            'subject'         => $request->subject,
            'reference'       => $request->reference,
            'advance_percent' => $request->advance_percent,
            'work_items'      => $request->work_items,
            'terms'           => $request->terms,
        ]);

        return redirect()
            ->route('work-orders.index')
            ->with('success', 'Work Order updated successfully');
    }
public function show(WorkOrder $workOrder)
{
    $workOrder->load('client');

    return view('pages.work_order.show', compact('workOrder'));
}

public function destroy(WorkOrder $workOrder)
    {
        $workOrder->delete();

        return redirect()
            ->route('work-orders.index')
            ->with('success', 'Work Order deleted successfully');
    }

public function createclients()
{
    $clients = Client::latest()->get(); // নতুন clients উপরে দেখাবে
    return view('pages.work_order.add_clients', compact('clients'));
}

    public function storeclients(Request $request)
    {
        //dd($request);
        $request->validate([
            'name'    => 'required|string|max:255',
            'mobile'  => 'required|string|max:20',
            'designation' => 'nullable|string',
            'company' => 'nullable|string',
            'address' => 'nullable|string',
        ]);

        Client::create([
            'name'    => $request->name,
            'designation'    => $request->designation,
            'company'    => $request->company,
            'mobile'  => $request->mobile,
            'address' => $request->address,
        ]);

        return redirect()->back()->with('success', 'Information added successfully!');
    }


public function updateclients(Request $request, Client $client)
{
    $request->validate([
        'name'    => 'required|string|max:255',
        'mobile'  => 'required|string|max:20',
        'designation' => 'nullable|string',
        'company' => 'nullable|string',
        'address' => 'nullable|string',
    ]);

    $client->update($request->only(['name', 'designation', 'company', 'mobile', 'address']));

    return redirect()->back()->with('success', 'Client updated successfully!');
}

// Delete Client
public function destroyclients(Client $client)
{
    $client->delete();
    return redirect()->back()->with('success', 'Client deleted successfully!');
}
    public function pdf(WorkOrder $workOrder)
    {
        $workOrder->load('client'); // Ensure relations loaded

        // mPDF setup with Kalpurush font
        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'margin_top' => 20,
            'margin_bottom' => 20,
            'margin_left' => 15,
            'margin_right' => 15,
            'fontDir' => array_merge([public_path('fonts')], (new \Mpdf\Config\ConfigVariables())->getDefaults()['fontDir']),
            'fontdata' => array_merge((new \Mpdf\Config\FontVariables())->getDefaults()['fontdata'], [
                'kalpurush' => [
                    'R' => 'Kalpurush.ttf',   // Kalpurush regular
                    'useOTL' => 0xFF,         // enable OpenType ligatures
                    'useKashida' => 75
                ]
            ]),
            'default_font' => 'kalpurush'
        ]);

        // Render Blade view
        $html = view('pages.work_order.pdf', compact('workOrder'))->render();

        // Write HTML to PDF
        $mpdf->WriteHTML($html);

        // Stream PDF in browser
        return $mpdf->Output('WorkOrder-' . $workOrder->work_order_no . '.pdf', 'I');
    }
}
