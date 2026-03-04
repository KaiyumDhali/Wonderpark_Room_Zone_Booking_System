<?php

namespace App\Http\Controllers\Spot;


use App\Http\Controllers\Controller;
use App\Models\CommonFacility;
use Illuminate\Http\Request;




class CommonFacilityController extends Controller
{
    // List all facilities
    public function index()
    {
        $facilities = CommonFacility::all();
        return view('common_facilities.index', compact('facilities'));
    }

    // Show form to create
    public function create()
    {
        return view('common_facilities.create');
    }

    // Store new facility
    public function store(Request $request)
{
    // Validate that facility_name is an array or a string
    $request->validate([
        'facility_name.*' => 'required|string|max:255',
        'status.*' => 'required',
    ]);

    // Check if multiple entries
    if(is_array($request->facility_name)) {
        // Multiple facilities
        foreach($request->facility_name as $key => $name) {
            CommonFacility::create([
                'facility_name' => $name,
                'status' => $request->status[$key],
            ]);
        }
    } else {
        // Single facility
        CommonFacility::create([
            'facility_name' => $request->facility_name,
            'status' => $request->status,
        ]);
    }

    return redirect()->route('common-facilities.index')->with('success', 'Facility(s) added successfully');
}


    // Show single facility
    public function show($id)
    {
        $facility = CommonFacility::findOrFail($id);
        return view('common_facilities.show', compact('facility'));
    }

    // Show form to edit
    public function edit($id)
    {
        $facility = CommonFacility::findOrFail($id);
        return view('common_facilities.edit', compact('facility'));
    }

    // Update facility
    public function update(Request $request, $id)
    {
        $request->validate([
            'facility_name' => 'required|string|max:255',
            'status' => 'required',
        ]);

        $facility = CommonFacility::findOrFail($id);
        $facility->update($request->all());

        return redirect()->route('common-facilities.index')->with('success', 'Facility updated successfully');
    }

    // Delete facility
    public function destroy($id)
    {
        $facility = CommonFacility::findOrFail($id);
        $facility->delete();

        return redirect()->route('common-facilities.index')->with('success', 'Facility deleted successfully');
    }
}