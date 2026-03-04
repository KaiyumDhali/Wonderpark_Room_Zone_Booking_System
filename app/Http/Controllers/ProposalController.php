<?php

namespace App\Http\Controllers;

use App\Models\AdditionalService;
use App\Models\CommonFacility;
use App\Models\Proposal;
use App\Models\ProposalItem;
use App\Models\CustomerType;
use App\Models\Room;
use App\Models\Customer;
use App\Models\CompanySetting;
use App\Models\Spot;
use App\Models\SpotPackage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

// PDF library: barryvdh/laravel-dompdf (recommended)
use Barryvdh\DomPDF\Facade\Pdf;

class ProposalController extends Controller
{

public function index()
{

    $proposals = Proposal::with('customer')
        ->latest()
        ->paginate(20);

    return view('pages.proposal.index', compact('proposals'));
}

    public function create()
    {
        $customerTypes=CustomerType::where('status', 1)
            ->pluck('type_name', 'id')->all();
        $customers = Customer::where('status', 1)->get();
        $rooms = Room::query()->where('status', 1)->get();
        $spots = Spot::query()->where('status', 1)->get();
        $packages = SpotPackage::query()->where('status', 1)->get();
        $services = AdditionalService::query()->where('status', 1)->get();
        $facilities = CommonFacility::query()->where('status', 1)->get();

        $defaultIntro = "Dear Sir/Madam,\n\nThank you for your interest in our resort and for giving us the opportunity to serve you. We are delighted to present this proposal, thoughtfully prepared to ensure a comfortable, relaxing, and memorable experience for you and your guests.Our resort offers a perfect blend of natural beauty, modern amenities, and personalized hospitality. Based on your requirements, we have curated the following arrangement to provide you with an enjoyable stay and a seamless experience from arrival to departure.
        
We look forward to welcoming you and making your visit truly special.";
        $defaultTerms = "1) A minimum of 50% advance payment is required to confirm the booking. The remaining balance must be paid before check-in / event commencement.
        \n2) All rates mentioned in this proposal are subject to availability at the time of confirmation.
        \n3) Any damage to resort property caused during the stay or event will be charged accordingly.
        \n4) Check-in and check-out times will be followed as per resort policy unless otherwise agreed in writing.
        \n5) Cancellation or date changes are subject to the resort’s cancellation policy. Any advance payment may be non-refundable depending on the cancellation timeline.
        \n6) The resort management reserves the right to make necessary changes to services due to unavoidable circumstances without prior notice.
        \n7) Guests are requested to follow all resort rules and regulations to ensure safety, security, and a pleasant environment for everyone.
        \n8) Outside food, beverages, or sound systems are not permitted unless prior approval is obtained from the management.)";

        return view('pages.proposal.create', compact('customerTypes',
            'rooms', 'spots', 'packages', 'services', 'facilities', 'defaultIntro', 'defaultTerms','customers'
        ));
    }

   public function store(Request $request)
{
    //dd($request);
    $validated = $request->validate([
        'proposal_title' => ['nullable','string','max:255'],
        'client_id'      => ['required','int'],
        'client_email'   => ['nullable','email','max:255'],
        'client_phone'   => ['nullable','string','max:50'],
        'intro_text'     => ['nullable','string'],
        'terms_text'     => ['nullable','string'],
        'notes_text'     => ['nullable','string'],

        'discount' => ['nullable','numeric','min:0'],
        'tax'      => ['nullable','numeric','min:0'],

        // Selected IDs (checkbox arrays)
        'rooms'    => ['nullable','array'],
        'rooms.*'  => ['integer','exists:rooms,id'],

        'spots'    => ['nullable','array'],
        'spots.*'  => ['integer','exists:spots,id'],

        'packages'   => ['nullable','array'],
        'packages.*' => ['integer','exists:spot_packages,id'],

        'services'   => ['nullable','array'],
        'services.*' => ['integer','exists:additional_services,id'],

        // Detail arrays (keyed by id)
        'room_qty'    => ['nullable','array'],
        'room_nights' => ['nullable','array'],
        'room_price'  => ['nullable','array'],

        'spot_qty'   => ['nullable','array'],
        'spot_price' => ['nullable','array'],

        'package_qty'   => ['nullable','array'],
        'package_price' => ['nullable','array'],

        'service_qty'   => ['nullable','array'],
        'service_price' => ['nullable','array'],

        // ✅ facilities validation বাদ (কারণ আর select হবে না)
    ]);

    return DB::transaction(function () use ($request) {

        $proposal = Proposal::create([
            'proposal_title' => $request->proposal_title,
            'client_id'      => $request->client_id,
            'client_email'   => $request->client_email,
            'client_phone'   => $request->client_phone,
            'intro_text'     => $request->intro_text,
            'terms_text'     => $request->terms_text,
            'notes_text'     => $request->notes_text,
            'status'         => 'draft',
            'discount'       => (float) ($request->discount ?? 0),
            'tax'            => (float) ($request->tax ?? 0),
            'created_by'     => auth()->id(),
        ]);

        // Proposal number generate
        $today = now()->format('Ymd');
        $countToday = Proposal::whereDate('created_at', now()->toDateString())
            ->lockForUpdate()
            ->count() + 1;

        $proposalNumber = 'PR-' . $today . '-' . str_pad((string)$countToday, 4, '0', STR_PAD_LEFT);
        $proposal->update(['proposal_number' => $proposalNumber]);

        $items = [];

        /** -------------------------
         *  Rooms (price_per_night × nights × qty)
         *  ------------------------- */
        foreach (($request->rooms ?? []) as $roomId) {
            $room = Room::findOrFail($roomId);

            $qty    = (int) ($request->room_qty[$roomId] ?? 1);
            $nights = (int) ($request->room_nights[$roomId] ?? 1);

            $override = $request->room_price[$roomId] ?? null;
            $unit = ($override !== null && $override !== '')
                ? (float) $override
                : (float) $room->price_per_night;

            $line = $unit * $qty * $nights;

            $items[] = new ProposalItem([
                'item_type'   => 'room',
                'item_id'     => $room->id,
                'title'       => $room->room_name ?: ('Room #' . $room->room_number),
                'description' => $room->description,
                'quantity'    => max(1, $qty),
                'nights'      => max(1, $nights),
                'unit_price'  => $unit,
                'line_total'  => $line,
                'meta_json'   => [
                    'room_number' => $room->room_number,
                    'capacity'    => $room->capacity,
                ],
            ]);
        }

        /** -------------------------
         *  Spots (price × qty) + Spot included facilities
         *  ------------------------- */
        foreach (($request->spots ?? []) as $spotId) {
            // ✅ facilities eager load যাতে query কম হয়
            $spot = Spot::with('facilities')->findOrFail($spotId);

            $qty = (int) ($request->spot_qty[$spotId] ?? 1);

            $override = $request->spot_price[$spotId] ?? null;
            $unit = ($override !== null && $override !== '')
                ? (float) $override
                : (float) $spot->price;

            $line = $unit * $qty;

            // Spot item
            $items[] = new ProposalItem([
                'item_type'   => 'spot',
                'item_id'     => $spot->id,
                'title'       => $spot->title,
                'description' => $spot->description,
                'quantity'    => max(1, $qty),
                'nights'      => null,
                'unit_price'  => $unit,
                'line_total'  => $line,
                'meta_json'   => [
                    'area_size'     => $spot->area_size,
                    'max_capacity'  => $spot->max_capacity,
                ],
            ]);

           foreach (($spot->facilities ?? collect()) as $sf) {

    // ✅ title never null
    $facilityName =
        $sf->facility_name
        ?? $sf->facility
        ?? $sf->title
        ?? ('Facility #' . ($sf->id ?? 'N/A'));

    $items[] = new ProposalItem([
        'item_type'   => 'spot_facility',
        'item_id'     => $sf->id,
        'title'       => $facilityName, // ✅ never null now
        'description' => 'Included with: ' . ($spot->title ?? 'Spot'),
        'quantity'    => 1,
        'nights'      => null,
        'unit_price'  => 0,
        'line_total'  => 0,
        'meta_json'   => [
            'spot_id'          => $spot->id,
            'spot_facility_id' => $sf->id,
            'source'           => 'spot_included',
        ],
    ]);
}
        }

        /** -------------------------
         *  Packages (price × qty)
         *  ------------------------- */
        foreach (($request->packages ?? []) as $pkgId) {
            $pkg = SpotPackage::findOrFail($pkgId);

            $qty = (int) ($request->package_qty[$pkgId] ?? 1);

            $override = $request->package_price[$pkgId] ?? null;
            $unit = ($override !== null && $override !== '')
                ? (float) $override
                : (float) $pkg->price;

            $line = $unit * $qty;

            $items[] = new ProposalItem([
                'item_type'   => 'spot_package',
                'item_id'     => $pkg->id,
                'title'       => $pkg->name,
                'description' => 'Persons: ' . $pkg->persons,
                'quantity'    => max(1, $qty),
                'nights'      => null,
                'unit_price'  => $unit,
                'line_total'  => $line,
                'meta_json'   => [
                    'persons' => $pkg->persons,
                ],
            ]);
        }

        /** -------------------------
         *  Additional Services (price × qty)
         *  ------------------------- */
        foreach (($request->services ?? []) as $serviceId) {
            $service = AdditionalService::findOrFail($serviceId);

            $qty = (int) ($request->service_qty[$serviceId] ?? 1);

            $override = $request->service_price[$serviceId] ?? null;
            $unit = ($override !== null && $override !== '')
                ? (float) $override
                : (float) $service->price;

            $line = $unit * $qty;

            $items[] = new ProposalItem([
                'item_type'   => 'additional_service',
                'item_id'     => $service->id,
                'title'       => $service->title,
                'description' => $service->description,
                'quantity'    => max(1, $qty),
                'nights'      => null,
                'unit_price'  => $unit,
                'line_total'  => $line,
                'meta_json'   => [
                    'editable_status' => $service->editable_status ?? null,
                ],
            ]);
        }

        /** -------------------------
         *  ✅ Common Facilities (AUTO for everyone)
         *  ------------------------- */
        $facilityRows = CommonFacility::all();
        foreach ($facilityRows as $f) {
            $items[] = new ProposalItem([
                'item_type'   => 'common_facility',
                'item_id'     => $f->id,
                'title'       => $f->facility_name,
                'description' => null,
                'quantity'    => 1,
                'nights'      => null,
                'unit_price'  => 0,
                'line_total'  => 0,
                'meta_json'   => ['source' => 'auto_common'],
            ]);
        }

        // ✅ Duplicate avoid (same item_type + item_id একবারই থাকবে)
        $items = collect($items)->unique(function ($it) {
            return $it->item_type . ':' . $it->item_id;
        })->values()->all();

        // Save items
        if (!empty($items)) {
            $proposal->items()->saveMany($items);
        }

        // totals
        $subtotal = $proposal->items()->sum('line_total');
        $discount = (float) ($proposal->discount ?? 0);
        $tax      = (float) ($proposal->tax ?? 0);

        $total = max(0, ($subtotal - $discount) + $tax);

        $proposal->update([
            'subtotal' => $subtotal,
            'total'    => $total,
        ]);

        return redirect()->route('proposals.index');
    });
}



public function edit(Proposal $proposal)
{
    $proposal->load(['items', 'customer']);

    $customerTypes = CustomerType::where('status', 1)->pluck('type_name', 'id')->all();
    $customers     = Customer::where('status', 1)->get();
    $rooms         = Room::where('status', 1)->get();
    $spots         = Spot::where('status', 1)->get();
    $packages      = SpotPackage::where('status', 1)->get();
    $services      = AdditionalService::where('status', 1)->get();
    $facilities    = CommonFacility::where('status', 1)->get();

    // Existing selected items
    $selectedRooms    = $proposal->items->where('item_type','room');
    $selectedSpots    = $proposal->items->where('item_type','spot');
    $selectedPackages = $proposal->items->where('item_type','spot_package');
    $selectedServices = $proposal->items->where('item_type','additional_service');

    // default text fallback
    $defaultIntro  = $proposal->intro_text;
    $defaultTerms  = $proposal->terms_text;

    return view('pages.proposal.edit', compact(
        'proposal',
        'customerTypes','customers',
        'rooms','spots','packages','services','facilities',
        'selectedRooms','selectedSpots','selectedPackages','selectedServices',
        'defaultIntro','defaultTerms'
    ));
}



public function update(Request $request, Proposal $proposal)
{
    $validated = $request->validate([
        'proposal_title' => ['nullable','string','max:255'],
        'client_id'      => ['required','int'],
        'client_email'   => ['nullable','email','max:255'],
        'client_phone'   => ['nullable','string','max:50'],
        'intro_text'     => ['nullable','string'],
        'terms_text'     => ['nullable','string'],
        'notes_text'     => ['nullable','string'],

        'discount' => ['nullable','numeric','min:0'],
        'tax'      => ['nullable','numeric','min:0'],

        'rooms'    => ['nullable','array'],
        'rooms.*'  => ['integer','exists:rooms,id'],

        'spots'    => ['nullable','array'],
        'spots.*'  => ['integer','exists:spots,id'],

        'packages'   => ['nullable','array'],
        'packages.*' => ['integer','exists:spot_packages,id'],

        'services'   => ['nullable','array'],
        'services.*' => ['integer','exists:additional_services,id'],

        'room_qty'    => ['nullable','array'],
        'room_nights' => ['nullable','array'],
        'room_price'  => ['nullable','array'],

        'spot_qty'   => ['nullable','array'],
        'spot_price' => ['nullable','array'],

        'package_qty'   => ['nullable','array'],
        'package_price' => ['nullable','array'],

        'service_qty'   => ['nullable','array'],
        'service_price' => ['nullable','array'],
    ]);

    return DB::transaction(function () use ($request, $proposal) {

        // 1) update proposal basic fields
        $proposal->update([
            'proposal_title' => $request->proposal_title,
            'client_id'      => $request->client_id,
            'client_email'   => $request->client_email,
            'client_phone'   => $request->client_phone,
            'intro_text'     => $request->intro_text,
            'terms_text'     => $request->terms_text,
            'notes_text'     => $request->notes_text,
            'discount'       => (float) ($request->discount ?? 0),
            'tax'            => (float) ($request->tax ?? 0),
            'updated_by'     => auth()->id(),
        ]);

        // 2) delete old items
        $proposal->items()->delete();

        $items = [];

        /** Rooms */
        foreach (($request->rooms ?? []) as $roomId) {
            $room = Room::findOrFail($roomId);

            $qty    = (int) ($request->room_qty[$roomId] ?? 1);
            $nights = (int) ($request->room_nights[$roomId] ?? 1);

            $override = $request->room_price[$roomId] ?? null;
            $unit = ($override !== null && $override !== '')
                ? (float) $override
                : (float) $room->price_per_night;

            $line = $unit * max(1,$qty) * max(1,$nights);

            $items[] = new ProposalItem([
                'item_type'   => 'room',
                'item_id'     => $room->id,
                'title'       => $room->room_name ?: ('Room #' . $room->room_number),
                'description' => $room->description,
                'quantity'    => max(1, $qty),
                'nights'      => max(1, $nights),
                'unit_price'  => $unit,
                'line_total'  => $line,
                'meta_json'   => [
                    'room_number' => $room->room_number,
                    'capacity'    => $room->capacity,
                ],
            ]);
        }

        /** Spots + spot facilities */
        foreach (($request->spots ?? []) as $spotId) {
            $spot = Spot::with('facilities')->findOrFail($spotId);

            $qty = (int) ($request->spot_qty[$spotId] ?? 1);

            $override = $request->spot_price[$spotId] ?? null;
            $unit = ($override !== null && $override !== '')
                ? (float) $override
                : (float) $spot->price;

            $line = $unit * max(1,$qty);

            $items[] = new ProposalItem([
                'item_type'   => 'spot',
                'item_id'     => $spot->id,
                'title'       => $spot->title,
                'description' => $spot->description,
                'quantity'    => max(1, $qty),
                'nights'      => null,
                'unit_price'  => $unit,
                'line_total'  => $line,
                'meta_json'   => [
                    'area_size'    => $spot->area_size,
                    'max_capacity' => $spot->max_capacity,
                ],
            ]);

            foreach (($spot->facilities ?? collect()) as $sf) {
                $facilityName =
                    $sf->facility_name
                    ?? $sf->facility
                    ?? $sf->title
                    ?? ('Facility #' . ($sf->id ?? 'N/A'));

                $items[] = new ProposalItem([
                    'item_type'   => 'spot_facility',
                    'item_id'     => $sf->id,
                    'title'       => $facilityName,
                    'description' => 'Included with: ' . ($spot->title ?? 'Spot'),
                    'quantity'    => 1,
                    'nights'      => null,
                    'unit_price'  => 0,
                    'line_total'  => 0,
                    'meta_json'   => [
                        'spot_id'          => $spot->id,
                        'spot_facility_id' => $sf->id,
                        'source'           => 'spot_included',
                    ],
                ]);
            }
        }

        /** Packages */
        foreach (($request->packages ?? []) as $pkgId) {
            $pkg = SpotPackage::findOrFail($pkgId);

            $qty = (int) ($request->package_qty[$pkgId] ?? 1);

            $override = $request->package_price[$pkgId] ?? null;
            $unit = ($override !== null && $override !== '')
                ? (float) $override
                : (float) $pkg->price;

            $line = $unit * max(1,$qty);

            $items[] = new ProposalItem([
                'item_type'   => 'spot_package',
                'item_id'     => $pkg->id,
                'title'       => $pkg->name,
                'description' => 'Persons: ' . $pkg->persons,
                'quantity'    => max(1, $qty),
                'nights'      => null,
                'unit_price'  => $unit,
                'line_total'  => $line,
                'meta_json'   => ['persons' => $pkg->persons],
            ]);
        }

        /** Services */
        foreach (($request->services ?? []) as $serviceId) {
            $service = AdditionalService::findOrFail($serviceId);

            $qty = (int) ($request->service_qty[$serviceId] ?? 1);

            $override = $request->service_price[$serviceId] ?? null;
            $unit = ($override !== null && $override !== '')
                ? (float) $override
                : (float) $service->price;

            $line = $unit * max(1,$qty);

            $items[] = new ProposalItem([
                'item_type'   => 'additional_service',
                'item_id'     => $service->id,
                'title'       => $service->title,
                'description' => $service->description,
                'quantity'    => max(1, $qty),
                'nights'      => null,
                'unit_price'  => $unit,
                'line_total'  => $line,
                'meta_json'   => [
                    'editable_status' => $service->editable_status ?? null,
                ],
            ]);
        }

        /** Auto common facilities */
        $facilityRows = CommonFacility::all();
        foreach ($facilityRows as $f) {
            $items[] = new ProposalItem([
                'item_type'   => 'common_facility',
                'item_id'     => $f->id,
                'title'       => $f->facility_name,
                'description' => null,
                'quantity'    => 1,
                'nights'      => null,
                'unit_price'  => 0,
                'line_total'  => 0,
                'meta_json'   => ['source' => 'auto_common'],
            ]);
        }

        // unique
        $items = collect($items)->unique(fn($it) => $it->item_type . ':' . $it->item_id)->values()->all();

        if (!empty($items)) {
            $proposal->items()->saveMany($items);
        }

        // totals
        $subtotal = $proposal->items()->sum('line_total');
        $discount = (float) ($proposal->discount ?? 0);
        $tax      = (float) ($proposal->tax ?? 0);

        $total = max(0, ($subtotal - $discount) + $tax);

        $proposal->update([
            'subtotal' => $subtotal,
            'total'    => $total,
        ]);

        return redirect()->route('proposals.show', $proposal)->with('success', 'Proposal updated successfully.');
    });
}




public function destroy(Proposal $proposal)
{
    return DB::transaction(function () use ($proposal) {
        $proposal->items()->delete();
        $proposal->delete();

        return redirect()->route('proposals.index')->with('success', 'Proposal deleted successfully.');
    });
}


    public function show(Proposal $proposal)
{
    $proposal->load(['items', 'customer']);
    $spotFacilities = $proposal->items->where('item_type', 'spot_facility');

//dd($proposal);
    $rooms = $proposal->items->where('item_type', 'room');
    $spots = $proposal->items->where('item_type', 'spot');
    $packages = $proposal->items->where('item_type', 'spot_package');
    $services = $proposal->items->where('item_type', 'additional_service');
    $facilities = $proposal->items->where('item_type', 'common_facility');

    return view('pages.proposal.show', compact(
        'proposal', 'rooms', 'spots', 'packages', 'services', 'facilities','spotFacilities'
    ));
}

public function pdf(Proposal $proposal)
{
    $company = CompanySetting::where('status',1)->first();
    $proposal->load(['items', 'customer']);

    $rooms         = $proposal->items->where('item_type', 'room');
    $spots         = $proposal->items->where('item_type', 'spot');
    $packages      = $proposal->items->where('item_type', 'spot_package');
    $services      = $proposal->items->where('item_type', 'additional_service');
    $facilities    = $proposal->items->where('item_type', 'common_facility');
    $spotFacilities= $proposal->items->where('item_type', 'spot_facility');

    // ✅ totals
    $roomsTotal    = $rooms->sum('line_total');
    $spotsTotal    = $spots->sum('line_total');
    $packagesTotal = $packages->sum('line_total');
    $servicesTotal = $services->sum('line_total');

    // ✅ subtotal (only paid items)
    $subtotal = $roomsTotal + $spotsTotal + $packagesTotal + $servicesTotal;

    $pdf = Pdf::loadView('pages.proposal.pdf', compact(
        'proposal',
        'rooms', 'spots', 'packages', 'services',
        'facilities','spotFacilities',
        'roomsTotal','spotsTotal','packagesTotal','servicesTotal',
        'subtotal','company'
    ))->setPaper('a4', 'portrait');

    return $pdf->stream('proposal-'.$proposal->id.'.pdf');
}

}
