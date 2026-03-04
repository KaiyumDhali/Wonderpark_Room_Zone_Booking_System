<?php
namespace App\Http\Controllers\Employee;
use App\Http\Controllers\Controller;

use App\Models\PerformanceType;
use Illuminate\Http\Request;

class PerformanceTypeController extends Controller
{
    function __construct()
    {
         $this->middleware('permission:read performance type|write performance type|create performance type', ['only' => ['index','show']]);
         $this->middleware('permission:create performance type', ['only' => ['create','store']]);
         $this->middleware('permission:write performance type', ['only' => ['edit','update','destroy']]);
    }

    public function index()
    {
        $performance_types = PerformanceType::get();

        return view('pages.employee.performance_type.index', compact('performance_types'));
    }

    public function create()
    {
        return view('pages.employee.performance_type.performance_add');
    }

    public function store(Request $request)
    {
        $request->validate([
            'performance_name' => 'required',
         ]);
  
        $input = $request->all();
        PerformanceType::create($input);

        return redirect()->route('performance_type.index')->with([
            'message' => 'successfully created !',
            'alert-type' => 'success'
        ]);
    }

    public function show(PerformanceType $performanceType)
    {
        //
    }

    public function edit(PerformanceType $performanceType)
    {
        return view('pages.employee.performance_type.performance_update', compact('performanceType'));
    }

    public function update(Request $request, PerformanceType $performanceType)
    {
        $request->validate([
            'performance_name' => 'required|string|max:255',
        ]);

        $performanceType->performance_name = $request->performance_name;
        $performanceType->status = $request->status;
        $performanceType->update();

        return redirect()->route('performance_type.index')->with([
            'message' => 'successfully updated !',
            'alert-type' => 'info'
        ]);
    }

    public function destroy(PerformanceType $performanceType)
    {
        //
    }
}
