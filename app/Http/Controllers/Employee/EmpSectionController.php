<?php
namespace App\Http\Controllers\Employee;
use App\Http\Controllers\Controller;
use App\Models\EmpSection;
use Illuminate\Http\Request;
use Carbon\Carbon;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class EmpSectionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() { }

    /**
     * Show the form for creating a new resource.
     */
    public function create() { }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {
        $validatedData  = $request->validate([
            'section_name' => 'required|string|max:255|unique:emp_sections,section_name',
            'section_name_bangla' => 'nullable|string|max:255|unique:emp_sections,section_name_bangla',
            'status' => 'nullable|boolean',
        ]);
        $cleanedData = array_map(fn($value) => is_string($value) ? Str::of($value)->stripTags()->trim() : $value, $validatedData);
        try {
            EmpSection::create($cleanedData);
            return back()->with(['message' => 'Successfully created!', 'alert-type' => 'success']);
        } catch (\Exception $e) {
            return back()->with(['message' => 'Failed to create. Please try again.', 'alert-type' => 'danger']);
        }
     }

    /**
     * Display the specified resource.
     */
    public function show(EmpSection $empSection) { }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EmpSection $empSection) { }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'section_name' => 'required|string|max:255|unique:emp_sections,section_name,' . $id,
            'section_name_bangla' => 'nullable|string|max:255|unique:emp_sections,section_name_bangla,' . $id,
            'status' => 'nullable|boolean',
        ]);
        $cleanedData = array_map(fn($value) => is_string($value) ? Str::of($value)->stripTags()->trim() : $value, $validatedData);
        try {
            $empSection = EmpSection::findOrFail($id);
            $empSection->update($cleanedData);

            return back()->with(['message' => 'Successfully updated!', 'alert-type' => 'info']);
        } catch (\Exception $e) {
            return back()->with(['message' => 'Failed to update. Please try again.', 'alert-type' => 'danger']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $empSection = EmpSection::findOrFail($id);
            $empSection->delete();
            return back()->with(['message' => 'Successfully deleted!', 'alert-type' => 'success']);
        } catch (\Exception $e) {
            return back()->with(['message' => 'Failed to delete. Please try again.', 'alert-type' => 'danger']);
        }
    }
}
