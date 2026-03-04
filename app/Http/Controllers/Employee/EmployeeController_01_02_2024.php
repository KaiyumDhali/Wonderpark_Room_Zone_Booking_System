<?php

namespace App\Http\Controllers\Employee;

use App\Models\Employee;
use App\Models\EmpBranch;
use App\Models\Promotion;
use App\Models\EmpDepartment;
use App\Models\EmpDesignation;
use App\Models\EmployeeEducation;
use App\Models\EmployeeJobHistory;
use App\Models\EmployeeJobResponsibility;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Auth;
use validator;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;
use Spatie\Permission\Models\Role;
use DB;

class EmployeeController extends Controller {

    function __construct() {
        $this->middleware('permission:read employee|write employee|create employee', ['only' => ['index', 'show']]);
        $this->middleware('permission:create employee', ['only' => ['create', 'store']]);
        $this->middleware('permission:write employee', ['only' => ['edit', 'update', 'destroy']]);
    }

    public function employeeProfile() {
        $id = Auth::user()->id;

        $employee = Employee::leftJoin('emp_branches', 'emp_branches.id', '=', 'employees.branch_id')
                        ->leftJoin('emp_departments', 'emp_departments.id', '=', 'employees.department_id')
                        ->leftJoin('emp_designations', 'emp_designations.id', '=', 'employees.designation_id')
                        ->select('employees.*', 'emp_branches.branch_name', 'emp_departments.department_name', 'emp_designations.designation_name')
                        ->where('user_id', $id)->first();
        $employee_educations = Employee::join('employee_education', 'employees.id', '=', 'employee_education.employee_id')
                                ->select('employees.*', 'employee_education.*')->where('employees.user_id', $id)
                                ->get();

        $employee_job_histories = Employee::join('employee_job_histories', 'employees.id', '=', 'employee_job_histories.employee_id')
                                ->select('employees.*', 'employee_job_histories.*')->where('employees.user_id', $id)
                                ->get();

        $employee_job_responsibilities = Employee::join('employee_job_responsibilities', 'employees.id', '=', 'employee_job_responsibilities.employee_id')
                                ->select('employees.*', 'employee_job_responsibilities.*')->where('employees.user_id', $id)
                                ->get();
        //dd($employee_job_responsibilities);
        return view('pages.employee.employees.profile', compact('employee', 'employee_educations', 'employee_job_histories', 'employee_job_responsibilities'));
    }

    public function branchDepartment($id) {
        $departments = EmpDepartment::where('status', 1)
                ->where('branch_id', $id)
                ->select('emp_departments.id', 'emp_departments.department_name')
                ->get();
        return response()->json($departments);
    }

    public function branchEmployees($id) {
        if ($id == 0) {
            $employees = Employee::leftJoin('emp_branches', 'emp_branches.id', '=', 'employees.branch_id')
                            ->leftJoin('emp_departments', 'emp_departments.id', '=', 'employees.department_id')
                            ->leftJoin('emp_designations', 'emp_designations.id', '=', 'employees.designation_id')
                            ->select('employees.*', 'emp_branches.branch_name', 'emp_departments.department_name', 'emp_designations.designation_name')
                            ->orderby('order', 'asc')->get();
        } else {
            $employees = Employee::leftJoin('emp_branches', 'emp_branches.id', '=', 'employees.branch_id')
                            ->leftJoin('emp_departments', 'emp_departments.id', '=', 'employees.department_id')
                            ->leftJoin('emp_designations', 'emp_designations.id', '=', 'employees.designation_id')
                            ->select('employees.*', 'emp_branches.branch_name', 'emp_departments.department_name', 'emp_designations.designation_name')
                            ->where('employees.branch_id', $id)
                            ->orderby('order', 'asc')->get();
        }
        return response()->json($employees);
    }

    public function index() {
        $allEmpBranch = EmpBranch::where('status', 1)->pluck('branch_name', 'id')->all();
        // $employees = Employee::leftJoin('emp_branches', 'emp_branches.id', '=', 'employees.branch_id')
        //                 ->leftJoin('emp_departments', 'emp_departments.id', '=', 'employees.department_id')
        //                 ->leftJoin('emp_designations', 'emp_designations.id', '=', 'employees.designation_id')
        //                 ->select('employees.*', 'emp_branches.branch_name', 'emp_departments.department_name', 'emp_designations.designation_name')
        //                 ->orderby('order', 'asc')->get();
        $employees = Employee::leftJoin('emp_branches', 'emp_branches.id', '=', 'employees.branch_id')
                        ->leftJoin('emp_departments', 'emp_departments.id', '=', 'employees.department_id')
                        ->leftJoin('emp_designations', 'emp_designations.id', '=', 'employees.designation_id')
                        ->select('employees.*', 'emp_branches.branch_name', 'emp_departments.department_name', 'emp_designations.designation_name')
                        ->where('employees.branch_id', 1)
                        ->orderby('order', 'asc')->get();
        return view('pages.employee.employees.index', compact('employees', 'allEmpBranch'));
    }

    public function create() {
        $allEmpBranch = EmpBranch::where('status', 1)->pluck('branch_name', 'id')->all();
        $allEmpDepartment = EmpDepartment::pluck('department_name', 'id')->all();
        $allEmpDesignation = EmpDesignation::pluck('designation_name', 'id')->all();
        return view('pages.employee.employees._employee-add', compact('allEmpBranch', 'allEmpDepartment', 'allEmpDesignation'));
    }

    public function store(Request $request) {
        $request->validate([
            'employee_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required'],
        ]);

        DB::beginTransaction();

        try {

            $user = User::create([
                        'name' => strip_tags($request->employee_name),
                        'email' => strip_tags($request->email),
                        'password' => Hash::make($request->password),
                        'last_login_at' => \Illuminate\Support\Carbon::now()->toDateTimeString(),
                        'last_login_ip' => $request->getClientIp()
            ]);

            // Assign a role to the newly created user
            $role = Role::where('name', 'employee')->first(); // Replace 'employee' with the desired role name
            $user->assignRole($role);

            $created_by = Auth::user()->id;
            $input = $request->all();

            if ($request->hasfile('photo')) {
                $image = $request->file('photo');
                $name = date('d-m-Y-H-i-s') . '_' . $image->getClientOriginalName();
                $image_path = $image->storeAs('public/images/products', $name);
                $input['photo'] = $image_path;
            }
            if ($request->hasfile('signature')) {
                $image = $request->file('signature');
                $name = date('d-m-Y-H-i-s') . '-' . $image->getClientOriginalName();
                $image_path = $image->storeAs('public/images/products', $name);
                $input['signature'] = $image_path;
            }

            $input['user_id'] = $user->id;
            $input['created_by'] = $created_by;

            // dd($input);
            $empdata = Employee::create($input);

            $empdataId = $empdata->id;

            $currentdate = Carbon::now()->format('Y-m-d');

            $promotion = Promotion::create([
                        'employee_id' => $empdataId,
                        'branch_id' => $request->branch_id,
                        'department_id' => $request->department_id,
                        'designation_id' => $request->department_id,
                        'start_date' => $request->joining_date ?? $currentdate,
                        'description' => 'First Joining',
                        'created_by' => $created_by,
            ]);
            DB::commit();
            // all good
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with([
                        'message' => 'Employee cound not created ! Please Check Again',
                        'alert-type' => 'danger'
            ]);
            // something went wrong
        }
        //dd($empdataId);  
        // $oldloc='/public/images/employees/' . $name1;
        // $newloc='/public/images/employees/' . $empdataId . $name1;
        // $oldloc2='/public/images/employees/' . $name2;
        // $newloc2='/public/images/employees/' . $empdataId . $name2;
        // Employee::where('id', $empdataId)->update([
        //     'photo' => $empdataId . $name1,
        //     'signature' => $empdataId . $name2,
        // ]);
        // Storage::move($oldloc, $newloc);
        // Storage::move($oldloc2, $newloc2);

        return redirect()->route('employees.index')->with([
                    'message' => 'successfully created !',
                    'alert-type' => 'success'
        ]);
    }

    public function show($id) {
        $employee = Employee::leftJoin('emp_branches', 'emp_branches.id', '=', 'employees.branch_id')
                        ->leftJoin('emp_departments', 'emp_departments.id', '=', 'employees.department_id')
                        ->leftJoin('emp_designations', 'emp_designations.id', '=', 'employees.designation_id')
                        ->select('employees.*', 'emp_branches.branch_name', 'emp_departments.department_name', 'emp_designations.designation_name')
                        ->orderby('id', 'desc')->find($id);

        $employee_educations = Employee::join('employee_education', 'employees.id', '=', 'employee_education.employee_id')
                ->select('employees.*', 'employee_education.*')->where('employees.id', $id)
                ->get();

        $employee__job_histories = Employee::join('employee_job_histories', 'employees.id', '=', 'employee_job_histories.employee_id')
                ->select('employees.*', 'employee_job_histories.*')->where('employees.id', $id)
                ->get();

        $employee_job_responsibilities = Employee::join('employee_job_responsibilities', 'employees.id', '=', 'employee_job_responsibilities.employee_id')
                ->select('employees.*', 'employee_job_responsibilities.*')->where('employees.id', $id)
                ->get();
        return view('pages.employee.employees._employee-show', compact('employee', 'employee_educations', 'employee__job_histories', 'employee_job_responsibilities'));
    }

    public function edit(Employee $employee) {

        $allEmpBranch = EmpBranch::pluck('branch_name', 'id')->all();

        $allEmpDepartment = EmpDepartment::where('branch_id', $employee->branch_id)->select('id', 'department_name')->get();

        $allEmpDesignation = EmpDesignation::pluck('designation_name', 'id')->all();

        $empEducations = EmployeeEducation::where('employee_id', $employee->id)->get();

        $employeeJobHistorys = EmployeeJobHistory::where('employee_id', $employee->id)->get();

        $employeeJobResponsibilities = EmployeeJobResponsibility::where('employee_id', $employee->id)->get();

        // dd($employeeJobResponsibilities);
        if (auth()->user()->hasRole('employee')) {
            return view('pages.employee.employees.profile_update', compact('employeeJobResponsibilities', 'employeeJobHistorys', 'employee', 'allEmpBranch', 'allEmpDepartment', 'allEmpDesignation', 'empEducations'));
        } else {
            return view('pages.employee.employees._employee-update', compact('employeeJobResponsibilities', 'employeeJobHistorys', 'employee', 'allEmpBranch', 'allEmpDepartment', 'allEmpDesignation', 'empEducations'));
        }
    }

    public function update(Request $request, Employee $employee) {

        $request->validate([
            'employee_name' => 'required|string|max:255',
            // 'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'email' => 'unique:users,email,' . $employee->user_id
        ]);

        $created_by = Auth::user()->id;

        // $user = User::where('id', $employee->user_id)->update([
        //     'name' => $request->employee_name,
        //     'email' => $request->email,
        //     'password' => Hash::make($request->password),
        //     'last_login_at' => now()->toDateTimeString(),
        //     'last_login_ip' => $request->getClientIp()
        // ]);

        $user = User::where('id', $employee->user_id)->first();

        if ($user) {
            $updateData = [
                'name' => strip_tags($request->employee_name),
                'email' => strip_tags($request->email),
                'last_login_at' => now()->toDateTimeString(),
                'last_login_ip' => $request->getClientIp()
            ];
            // Check if the password is provided and not null
            if ($request->filled('password')) {
                $updateData['password'] = Hash::make($request->password);
            }
            $user->update($updateData);
        }

        $input = $request->all();
        // $input->status = 1;
        if ($request->hasfile('photo')) {
            $image = $request->file('photo');
            $name = date('d-m-Y-H-i-s') . '_' . $image->getClientOriginalName();
            $image_path = $image->storeAs('public/images/products', $name);
            $input['photo'] = $image_path;
        }
        if ($request->hasfile('signature')) {
            $image = $request->file('signature');
            $name = date('d-m-Y-H-i-s') . '-' . $image->getClientOriginalName();
            $image_path = $image->storeAs('public/images/products', $name);
            $input['signature'] = $image_path;
        }

        $input['created_by'] = $created_by;
        $employee->update($input);
        if (auth()->user()->hasRole('employee')) {
            return redirect()->route('employee_profile')->with([
                        'message' => 'successfully updated !',
                        'alert-type' => 'info'
            ]);
        } else {
            return redirect()->route('employees.index')->with([
                        'message' => 'successfully updated !',
                        'alert-type' => 'info'
            ]);
        }
    }

    public function destroy(Employee $employee) {
        $employee->delete();
        return redirect()->route('employees.index')->with([
                    'message' => 'successfully deleted !',
                    'alert-type' => 'danger'
        ]);
    }

}