<?php
namespace App\Http\Controllers\Production;
use App\Http\Controllers\Controller;

use App\Models\CostSheet;
use Illuminate\Http\Request;

class CostSheetController extends Controller
{
    
    function __construct()
    {
         $this->middleware('permission:read cost sheet|write cost sheet|create cost sheet', ['only' => ['index','show']]);
         $this->middleware('permission:create cost sheet', ['only' => ['create','store']]);
         $this->middleware('permission:write cost sheet', ['only' => ['edit','update','destroy']]);
    }
    
    public function index()
    {
        // $costSheets = CostSheet::orderby('id', 'desc')->get();
        // return view('pages.production.cost_sheet.index',compact('costSheets'));
        return view('pages.production.cost_sheet.index');
    }

    public function create()
    {
        return view('pages.production.cost_sheet.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(CostSheet $costSheet)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CostSheet $costSheet)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CostSheet $costSheet)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CostSheet $costSheet)
    {
        //
    }
}
