<?php

namespace App\Http\Controllers\Spot;

use App\Http\Controllers\Controller;
use App\Models\AdditionalService;
use Illuminate\Http\Request;
use App\Models\SpotBooking;
use App\Models\SpotPackage;
use App\Models\Spot;
use App\Models\SpotDetail;
use Illuminate\Support\Facades\Storage;





class AdditionalServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $services = AdditionalService::latest()->paginate(20);
        return view('pages.additional_service.index', compact('services'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.additional_service.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'status' => 'required|boolean',
        ]);

        AdditionalService::create([
            'title' => $request->title,
            'description' => $request->description,
            'price' => $request->price,
            'status' => $request->status,
        ]);

        return redirect()->route('additional-services.index')
            ->with('message', 'Additional service created successfully!');
    }


    /**
     * Display the specified resource.
     */
    public function show(AdditionalService $additionalService)
    {
        return view('pages.additional_service.show', compact('additionalService'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AdditionalService $additionalService)
    {
        return view('pages.additional_service.edit', compact('additionalService'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AdditionalService $additionalService)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0',
            'status'      => 'required|boolean',
        ]);

        $additionalService->update($request->only([
            'title',
            'description',
            'price',
            'status'
        ]));

        return redirect()
            ->route('additional-services.index')
            ->with([
                'message' => 'Additional service updated successfully!',
                'alert-type' => 'success'
            ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AdditionalService $additionalService)
    {
        $additionalService->delete();

        return redirect()
            ->route('additional-services.index')
            ->with([
                'message' => 'Additional service deleted successfully!',
                'alert-type' => 'success'
            ]);
    }

    public function toggleEditableAjax(Request $request)
{
    $service = AdditionalService::findOrFail($request->service_id);
    $service->editable_status = $request->status;
    $service->save();

    return response()->json(['success' => true]);
}

}
