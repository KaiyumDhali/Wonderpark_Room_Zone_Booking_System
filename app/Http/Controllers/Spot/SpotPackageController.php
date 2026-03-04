<?php

namespace App\Http\Controllers\Spot;

use App\Models\SpotPackage;
use Illuminate\Http\Request;
use App\Models\Spot;
use App\Models\SpotDetail;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;




class SpotPackageController extends Controller
{

    public function index()
    {
        $packages = SpotPackage::all();
        return view('pages.spot_package.index', compact('packages'));
    }


    public function create()
    {
        $spots = Spot::where('status', 1)->get();
        return view('pages.spot_package.create', compact('spots'));
    }


    public function store(Request $request)
    {
        // ✅ Validation
        $request->validate([
            'name' => 'required',
            'persons' => 'required|integer|min:1',
            'price'   => 'required|numeric|min:0',
            'status'  => 'required|in:0,1',
        ]);

        // ✅ Store Data
        SpotPackage::create([
            'name' => $request->name,
            'persons' => $request->persons,
            'price'   => $request->price,
            'status'  => $request->status,
        ]);

        // ✅ Redirect with message
        return redirect()
            ->route('spot-packages.index')
            ->with([
                'message' => 'Spot package created successfully!',
                'alert-type' => 'success'
            ]);
    }



    public function show(SpotPackage $spotPackage)
    {
        //
    }


    // public function edit(SpotPackage $spotPackage)
    // {
    //     //
    // }

    public function edit($id)
    {
        $package = SpotPackage::findOrFail($id);
        $spots = Spot::where('status', 1)->get();

        return view('pages.spot_package.edit', compact('package', 'spots'));
    }



    // public function update(Request $request, SpotPackage $spotPackage)
    // {
    //     //
    // }


    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'persons' => 'required|integer|min:1',
            'price'   => 'required|numeric|min:0',
            'status'  => 'required|in:0,1',
        ]);

        $package = SpotPackage::findOrFail($id);

        $package->update([
            'name' => $request->name,
            'persons' => $request->persons,
            'price'   => $request->price,
            'status'  => $request->status,
        ]);

        return redirect()
            ->route('spot-packages.index')
            ->with([
                'message' => 'Spot package updated successfully!',
                'alert-type' => 'success'
            ]);
    }



    // public function destroy(SpotPackage $spotPackage)
    // {
    //     //
    // }

    public function destroy($id)
    {
        // Find the package
        $package = SpotPackage::findOrFail($id);

        // Delete the package
        $package->delete();

        // Redirect with success message
        return redirect()
            ->route('spot-packages.index')
            ->with([
                'message' => 'Spot package deleted successfully!',
                'alert-type' => 'success'
            ]);
    }
}
