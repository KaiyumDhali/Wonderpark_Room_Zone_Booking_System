<?php

namespace App\Http\Controllers\Spot;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use App\Models\TermsCondition;
use Illuminate\Http\Request;
use App\Models\SpotBooking;
use App\Models\SpotPackage;
use App\Models\Spot;
use App\Models\Room;
use App\Models\AdditionalService;
use App\Models\SpotDetail;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Customer;
use App\Models\CustomerType;
use App\Models\FinanceGroup;
use App\Models\FinanceAccount;
use App\Models\FinanceTransaction;
use App\Models\CompanySetting;
use App\Models\Warehouse;
use App\Models\Product;
use App\Models\ProductUnit;
use App\Models\Stock;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Date;
use Validator;
use Carbon\Carbon;
use Auth;
use App\Models\ProductService;
use App\Models\ProductServiceDetail;
use Illuminate\Support\Facades\View;
use NumberFormatter;
use Rmunate\Utilities\SpellNumber;
use Illuminate\Pagination\LengthAwarePaginator;






class TermsConditionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // public function index()
    // {
    //     $terms = DB::table('terms_conditions')
    //         ->orderBy('sort_order')
    //         ->orderBy('id', 'desc')
    //         ->get();


    //     return view('pages.terms_condition.index', compact('terms'));
    // }

public function index(Request $request)
{
    $query = TermsCondition::query();

    // Filter: Term Type (included/common)
    if ($request->filled('term_type')) {
        $query->where('term_type', $request->term_type);
    }

    // Filter: Term For (spot/service/room/common)
    if ($request->filled('term_type1')) {
        $query->where('term_type1', $request->term_type1);
    }

    // Filter: Status
    if ($request->filled('is_active')) {
        $query->where('is_active', $request->is_active);
    }

    // Search (Title / Description)
    if ($request->filled('search')) {
        $query->where(function ($q) use ($request) {
            $q->where('term_title', 'like', '%' . $request->search . '%')
              ->orWhere('term_description', 'like', '%' . $request->search . '%');
        });
    }

    $terms = $query->orderBy('sort_order', 'asc')
                   ->latest()
                   ->paginate(20)
                   ->withQueryString();

    return view('pages.terms_condition.index', compact('terms'));
}



    /**
     * Show the form for creating a new resource.
     */




    // public function create()
    // {
    //     return view('pages.terms_condition.create');
    // }

    public function create()
    {
        return view('pages.terms_condition.create', [
            'spots' => Spot::orderBy('title')->get(),
            'services' => AdditionalService::orderBy('title')->get(),
            'rooms' => Room::orderBy('room_number')->get(),
        ]);
    }



    /**
     * Store a newly created resource in storage.
     */



public function store(Request $request)
{
    //dd($request);
    // ✅ term_type = common হলে term_type1 কে common করে দাও
    // if ($request->term_type === 'common') {
    //     $request->merge([
    //         'term_type1' => 'common',
    //     ]);
    // }

    $request->validate([
        // ✅ এখন common allow
        'term_type1' => ['required', Rule::in(['spot', 'service', 'common','room'])],
        'term_type'  => ['required', Rule::in(['included', 'common'])],

        'spot_id' => [
            'nullable',
            'exists:spots,id',
            Rule::requiredIf(fn () =>
                $request->term_type === 'included' && $request->term_type1 === 'spot'
            ),
        ],

        'additional_service_id' => [
            'nullable',
            'exists:additional_services,id',
            Rule::requiredIf(fn () =>
                $request->term_type === 'included' && $request->term_type1 === 'service'
            ),
        ],
        'room_id' => [
            'nullable',
            'exists:rooms,id',
            Rule::requiredIf(fn () =>
                $request->term_type === 'included' && $request->term_type1 === 'room'
            ),
        ],

        'term_title'       => ['nullable','string','max:255'],
        'term_description' => ['required'],
        'sort_order'       => ['nullable','integer'],
        'is_active'        => ['required','boolean'],
    ]);

    $spotId = null;
    $serviceId = null;
    $roomId = null;

    // ✅ only included হলে এসব save হবে
    if ($request->term_type === 'included') {
        if ($request->term_type1 === 'spot') {
            $spotId = $request->spot_id;
        } elseif ($request->term_type1 === 'service') {
            $serviceId = $request->additional_service_id;
        }
         elseif ($request->term_type1 === 'room') {
            $roomId = $request->room_id;
        }
    }

    TermsCondition::create([
        'term_type'             => $request->term_type,   // included/common
        'term_type1'            => $request->term_type1,  // spot/service/common
        'spot_id'               => $spotId,
        'additional_service_id' => $serviceId,
        'room_id' => $roomId,
        'term_title'            => $request->term_title,
        'term_description'      => $request->term_description,
        'sort_order'            => $request->sort_order ?? 0,
        'is_active'             => $request->is_active,
    ]);

    return redirect()->route('terms-conditions.index')
        ->with('success', 'Terms & Conditions added successfully.');
}


    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $term = DB::table('terms_conditions')->where('id', $id)->first();

        if (!$term) {
            abort(404);
        }

        return view('pages.terms_condition.show', compact('term'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $term = DB::table('terms_conditions')->where('id', $id)->first();
        $spots = Spot::orderBy('title')->get();
        $services = AdditionalService::orderBy('title')->get();
        $rooms = Room::orderBy('room_number')->get();

        if (!$term) {
            abort(404);
        }

        return view('pages.terms_condition.edit', compact('term','spots', 'services','rooms'));
    }

    



    /**
     * Update the specified resource in storage.
     */


   public function update(Request $request, $id)
{
    // record check
    $term = DB::table('terms_conditions')->where('id', $id)->first();
    if (!$term) {
        return redirect()->route('terms-conditions.index')
            ->with('error', 'Terms & Conditions not found.');
    }

    // ✅ common হলে term_type1 = common force
    // if ($request->term_type === 'common') {
    //     $request->merge(['term_type1' => 'common']);
    // }

    $request->validate([
        'term_type1' => ['required', Rule::in(['spot','service','common','room'])],
        'term_type'  => ['required', Rule::in(['included','common'])],

        'spot_id' => [
            'nullable',
            'exists:spots,id',
            Rule::requiredIf(fn () =>
                $request->term_type === 'included' && $request->term_type1 === 'spot'
            ),
        ],

        'additional_service_id' => [
            'nullable',
            'exists:additional_services,id',
            Rule::requiredIf(fn () =>
                $request->term_type === 'included' && $request->term_type1 === 'service'
            ),
        ],
        'room_id' => [
            'nullable',
            'exists:rooms,id',
            Rule::requiredIf(fn () =>
                $request->term_type === 'included' && $request->term_type1 === 'room'
            ),
        ],

        'term_title'       => ['nullable','string','max:255'],
        'term_description' => ['required'],
        'sort_order'       => ['nullable','integer'],
        'is_active'        => ['required','boolean'],
    ]);

    $spotId = null;
    $serviceId = null;
    $roomId = null;

    if ($request->term_type === 'included') {
        if ($request->term_type1 === 'spot') $spotId = $request->spot_id;
        if ($request->term_type1 === 'service') $serviceId = $request->additional_service_id;
        if ($request->term_type1 === 'room') $roomId = $request->room_id;
    }

    DB::table('terms_conditions')
        ->where('id', $id)
        ->update([
            'term_type'             => $request->term_type,     // included/common
            'term_type1'            => $request->term_type1,    // spot/service/common
            'spot_id'               => $spotId,
            'room_id'               => $roomId,
            'additional_service_id' => $serviceId,
            'term_title'            => $request->term_title,
            'term_description'      => $request->term_description,
            'sort_order'            => $request->sort_order ?? 0,
            'is_active'             => $request->is_active,
            'updated_at'            => now(),
        ]);

    return redirect()->route('terms-conditions.index')
        ->with('success', 'Terms & Conditions updated successfully.');
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TermsCondition $termsCondition)
    {
        //
    }
}
