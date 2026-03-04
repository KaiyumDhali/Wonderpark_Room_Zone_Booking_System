<?php

namespace App\Http\Controllers\hradmin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\AnnouncementDetail;
use App\Models\Termination;
use App\Models\Resignation;
use App\Models\Employee;
use App\Models\EmpBranch;
use App\Models\EmpDepartment;
use App\Models\EmpDesignation;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Carbon\Carbon;
use Validator;
use Auth;
use DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Crypt;

class AnnouncementController extends Controller {

    function __construct() {
        $this->middleware('permission:read announcement|write announcement|create announcement', ['only' => ['index', 'show']]);
        $this->middleware('permission:create announcement', ['only' => ['create', 'store']]);
        $this->middleware('permission:write announcement', ['only' => ['edit', 'update', 'destroy']]);
    }

    public function allEmpBranch() {
//        $allEmpBranch = EmpBranch::pluck('branch_name')->all();
        $query = "select 'All Branch' as branch_name UNION ALL SELECT eb.branch_name FROM emp_branches eb";
        $allEmpBranch = DB::select($query);
        $branchNames = collect($allEmpBranch)->pluck('branch_name')->all();
        return response()->json($branchNames);
    }

    public function single_employee_Announcement($id) {

        $startdate = Carbon::now()->addDays(-30)->format('Y-m-d');
        $currentdate = Carbon::now()->format('Y-m-d');
//        $end_date = Carbon::now()->addDays(+30)->format('Y-m-d');

        $announcementdata = DB::table('announcements as t1')
                        ->join('announcement_details as t2', 't1.id', '=', 't2.announcement_id')
                        ->where('t2.employee_id', $id)
                        ->whereBetween('t1.start_date', [$startdate, $currentdate])
                        ->select('t1.*') // You may want to specify the columns you need instead of using '*'
                        ->orderBy('t1.id', 'desc')->get();
        
//        $announcementdata = DB::table('announcements as t1')
//                ->join('announcement_details as t2', 't1.id', '=', 't2.announcement_id')
//                ->where('t2.employee_id', $id)
//                ->where('t1.start_date', '<=', Carbon::now())
//                ->where('t1.end_date', '>=', Carbon::now())
//                ->select('t1.*') 
//                ->orderBy('t1.id', 'desc')
//                ->get();

//        $announcementdata = DB::table('announcements as t1')
//                ->join('announcement_details as t2', 't1.id', '=', 't2.announcement_id')
//                ->where('t2.employee_id', $id)
//                ->where(function ($query) use ($startdate, $enddate) {
//                    $query->whereDate('t1.start_date', '>=', $startdate)
//                    ->whereDate('t1.start_date', '<=', $enddate);
//                })
//                ->select('t1.*') // You may want to specify the columns you need instead of using '*'
//                ->orderBy('t1.id', 'desc')
//                ->get();

        return response()->json($announcementdata);
    }

    public function allEmpDepartment($id) {
        $departments = EmpDepartment::where('status', 1)
                ->where('branch_id', $id)
                ->select('emp_departments.id', 'emp_departments.department_name')
                ->get();
        return response()->json($departments);
    }

    public function allEmpDesignation() {
        $allDesignation = EmpDesignation::pluck('designation_name')->all();
        return response()->json($allDesignation);
    }

    public function allEmployee() {
        $allEmployee = Employee::pluck('employee_name')->all();
        return response()->json($allEmployee);
    }

    public function index() {

        $startdate = Carbon::now()->addDays(-30)->format('Y-m-d');
        $enddate = Carbon::now()->format('Y-m-d');
        $allEmployees = Employee::pluck('employee_name', 'id')->all();
        $allEmpBranch = EmpBranch::pluck('branch_name', 'id')->all();
        $allDepartment = EmpDepartment::pluck('department_name', 'id')->all();
        $allEmpDesignation = EmpDesignation::pluck('designation_name', 'id')->all();
        $announcements = Announcement::orderBy('id', 'desc')->get();

        return view('pages.hradmin.announcement.index', compact('allEmpBranch', 'allDepartment', 'allEmpDesignation', 'allEmployees', 'announcements'));
    }

    public function create() {

        //Reza
        // $employees = Employee::with(['empBranch:id,branch_name', 'empDepartment:id,department_name'])->with(['empDepartment:id,department_name'])->with(['empDesignation:id,designation_name'])->get();
//        dd($employees);
//        
//        echo '<pre>';
//        print_r($employees);
//        echo '</pre>';
//        die();

        $allEmpBranch = EmpBranch::pluck('branch_name', 'id')->all();
        //   dd($allEmpBranch);
        //   $allEmpDepartment = EmpDepartment::pluck('department_name', 'id')->all();
        $allEmpDesignation = EmpDesignation::pluck('designation_name', 'id')->all();
        return view('pages.hradmin.announcement.add', compact('allEmpBranch', 'allEmpDesignation'));
//        return view('pages.hradmin.announcement.add');
    }

    public function store(Request $request) {

        // dd($request);

        $validator = Validator::make($request->all(), [
                    'title' => 'required',
                    'start_date' => 'required|max:100',
                    'end_date' => 'required',
                    'branch_id' => 'required',
                    'file_path' => 'nullable|file|mimes:pdf|max:5000',
                        ],
                        [
                            'branch_id.required' => 'Company Not Select',
                        ]
        );

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }


        DB::beginTransaction();

        try {

            $created_by = Auth::user()->id;

            $announcement = new Announcement();

            if ($request->hasFile('announcement_file')) {
                $file = $request->file('announcement_file');
                $name = date('d-m-Y-H-i-s') . '_' . $file->getClientOriginalName();
                $file_path = $file->storeAs('public/announcements', $name);
                $announcement->file_path = $file_path;
            }

            $announcement->title = strip_tags($request->input('title'));
            $announcement->start_date = $request->input('start_date');
            $announcement->end_date = $request->input('end_date');
            $announcement->description = strip_tags($request->input('description'));
            $announcement->created_by = $created_by;

            $announcement->save();

            $announcementID = $announcement->id;

            $getEmployeesId = "";

            foreach ($request->get('branch_id') as $key => $branchID) {

                //  if(!empty($request->input('department_id')[$key])){
                $departmentId = $request->input('department_id')[$key];
                $designationId = $request->input('designation_id')[$key];

                // if($departmentId == 0){
                //     $getEmployeesId = Employee::where([['branch_id', $branchID],['designation_id', $designationId]])->get(['id']);
                // }
                // elseif($departmentId == 0 && $designationId == 0){
                //     $getEmployeesId = Employee::where([['branch_id', $branchID]])->get(['id']);
                // }else{
                //     $getEmployeesId = Employee::where([['branch_id', $branchID], ['department_id',$departmentId], ['designation_id', $designationId]])->get(['id']);
                // }

                $query = Employee::where('branch_id', $branchID);

                if ($departmentId != 0) {
                    $query->where('department_id', $departmentId);
                }

                if ($designationId != 0) {
                    $query->where('designation_id', $designationId);
                }

                $getEmployeesId = $query->get(['id']);

                foreach ($getEmployeesId as $eachEmployee) {
                    AnnouncementDetail::create([
                        'announcement_id' => $announcementID,
                        'employee_id' => $eachEmployee->id,
                            // 'status' => 'Traveling to Europe',
                    ]);
                }

                //  }
            }
            DB::commit();
            // all good
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with([
                        'message' => 'Announcement cound not created ! Please Check Again',
                        'alert-type' => 'danger'
            ]);
            // something went wrong
        }

        return redirect()->route('announcement.index')->with([
                    'message' => 'successfully created !',
                    'alert-type' => 'success'
        ]);
    }

    public function show($id) {
//        $employee = Employee::leftJoin('emp_branches', 'emp_branches.id', '=', 'employees.branch_id')
//                        ->leftJoin('emp_departments', 'emp_departments.id', '=', 'employees.department_id')
//                        ->leftJoin('emp_designations', 'emp_designations.id', '=', 'employees.designation_id')
//                        ->select('employees.*', 'emp_branches.branch_name', 'emp_departments.department_name', 'emp_designations.designation_name')
//                        ->orderby('id', 'desc')->find($id);
//
//        $employee_educations = Employee::join('employee_education', 'employees.id', '=', 'employee_education.employee_id')
//                ->select('employees.*', 'employee_education.*')->where('employees.id', $id)
//                ->get();
//
//        $employee__job_histories = Employee::join('employee_job_histories', 'employees.id', '=', 'employee_job_histories.employee_id')
//                ->select('employees.*', 'employee_job_histories.*')->where('employees.id', $id)
//                ->get();
//        return view('pages.employee.employees._employee-show', compact('employee', 'employee_educations', 'employee__job_histories'));
        $announcements = Announcement::find(Crypt::decrypt($id));
        return view('pages.hradmin.announcement.show', compact('announcements'));
    }

    public function edit(Announcement $announcement) {
        
    }

    public function update(Request $request, Announcement $announcement) {



        $request->validate([
            'title' => 'required',
            'start_date' => 'required|max:100',
            'end_date' => 'required',
        ]);

        $created_by = Auth::user()->id;

        $announcement->title = strip_tags($request->input('title'));
        $announcement->start_date = $request->input('start_date');
        $announcement->end_date = $request->input('end_date');
        $announcement->branch_id = $request->input('branch_id');
        $announcement->department_id = $request->input('department_id');
        $announcement->designation_id = $request->input('designation_id');
        $announcement->employee_id = $request->input('employee_id');
        $announcement->description = strip_tags($request->input('description'));
        $announcement->created_by = $created_by;

//        dd($announcement);

        $announcement->update();

        return redirect()->route('announcement.index')->with([
                    'message' => 'successfully created !',
                    'alert-type' => 'success'
        ]);
    }

    function downloadAnnouncement($file_path) {
        //dd('here');
        // $file = Storage::disk('laravel')->get($file_name);
        //dd($file);
        return Storage::disk($file_path)->download();
    }

    public function destroy(Announcement $announcement) {

        $announcement->announcement_details()->delete();

        if ($announcement->file_path != Null) {
            Storage::delete($announcement->file_path);
        }
        $announcement->delete();

        return back()->with([
                    'message' => 'successfully deleted !',
                    'alert-type' => 'success'
        ]);
    }

}
