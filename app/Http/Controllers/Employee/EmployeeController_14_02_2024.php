<?php

namespace App\Http\Controllers\Employee;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Employee;

use App\Models\EmpBranch;
use App\Models\Promotion;
use App\Models\EmployeeEducation;
use App\Models\EmployeeJobHistory;
use App\Models\EmployeeJobResponsibility;

use App\Models\EmpType;
use App\Models\EmpDepartment;
use App\Models\EmpSection;
use App\Models\EmpLine;
use App\Models\EmpDesignation;
use App\Models\EmpGrade;
use App\Models\EmpSalarySection;
use App\Models\EmpQuiteType;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Carbon\Carbon;
// use validator;
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
        $employees = Employee::select('employees.*')->orderby('order', 'asc')->get();
        // $employees = Employee::leftJoin('emp_branches', 'emp_branches.id', '=', 'employees.branch_id')
        //                 ->leftJoin('emp_departments', 'emp_departments.id', '=', 'employees.department_id')
        //                 ->leftJoin('emp_designations', 'emp_designations.id', '=', 'employees.designation_id')
        //                 ->select('employees.*', 'emp_branches.branch_name', 'emp_departments.department_name', 'emp_designations.designation_name')
        //                 ->where('employees.branch_id', 1)
        //                 ->orderby('order', 'asc')->get();
        return view('pages.employee.employees.index', compact('employees', 'allEmpBranch'));
    }

    public function create() {
        $allEmpBranch = EmpBranch::where('status', 1)->pluck('branch_name', 'id')->all();
        $allEmpDepartment = EmpDepartment::where('status', 1)->pluck('department_name', 'id')->all();
        $allEmpDesignation = EmpDesignation::where('status', 1)->pluck('designation_name', 'id')->all();
        $allEmpType = EmpType::where('status', 1)->pluck('type_name', 'id')->all();
        $allEmpSection = EmpSection::where('status', 1)->pluck('section_name', 'id')->all();
        $allEmpLine = EmpLine::where('status', 1)->pluck('line_name', 'id')->all();
        $allEmpGrade = EmpGrade::where('status', 1)->pluck('grade_name', 'id')->all();
        $allEmpSalarySection = EmpSalarySection::where('status', 1)->pluck('salary_section_name', 'id')->all();
        $allEmpQuiteType = EmpQuiteType::where('status', 1)->pluck('quite_type_name', 'id')->all();

        return view('pages.employee.employees._employee-add', compact('allEmpBranch', 'allEmpDepartment', 'allEmpDesignation', 
        'allEmpType', 'allEmpSection', 'allEmpLine', 'allEmpGrade', 'allEmpSalarySection', 'allEmpQuiteType'));
    }

    public function store(Request $request)
    {
        // $employees = $request->all();
        // dd($employees);
        $validatedData = $request->validate([
            // 'branch_id' => 'required|integer',
            'type_id' => 'required|integer',
            'department_id' => 'required|integer',
            'section_id' => 'required|integer',
            'line_id' => 'required|integer',
            'designation_id' => 'required|integer',
            'grade_id' => 'required|integer',
            'gross_salary' => 'required|numeric',
            'ac_number' => 'nullable|string|max:255',
            'salary_section_id' => 'required|integer',
            'joining_date' => 'required|date',

            // 'email' => 'nullable|email|unique:employees,email',
            'employee_code' => 'required|string|max:255|unique:employees,employee_code',
            'employee_name' => 'required|string|max:255',
            'gender' => 'required|string|in:Male,Female',
            'dob' => 'nullable|date',
            'nid' => 'nullable|string|max:255',
            'birth_id' => 'nullable|string|max:255',
            'blood_group' => 'nullable|string|max:255',
            'marital_status' => 'nullable|string|max:255',
            'spouse_name' => 'nullable|string|max:255',
            'religion' => 'nullable|string|max:255',
            'mobile' => 'nullable|string|max:255',
            'mobile_two' => 'nullable|string|max:255',
            'emergency_contact_person' => 'nullable|string|max:255',
            'emergency_contact_number' => 'nullable|string|max:255',
            'father_name' => 'nullable|string|max:255',
            'mother_name' => 'nullable|string|max:255',
            'present_address' => 'nullable|string',
            'permanent_address' => 'nullable|string',
            'highest_education' => 'nullable|string|max:255',
            'quite_date' => 'nullable|date',
            'quite_type' => 'nullable|string|max:255',
            'job_status' => 'nullable|string|max:255',
            'photo' => 'nullable|image|max:2048', // Adjust max file size as needed
            'signature' => 'nullable|image|max:2048', // Adjust max file size as needed
            'order' => 'nullable|integer',
            'status' => 'nullable|boolean',
        ]);
        $employeeData = array_map(function ($value) {
            return is_string($value) ? Str::of($value)->stripTags()->trim() : $value;
        }, $validatedData);
        $employeeData['created_by'] = Auth::user()->name;

        
        try {
           $employee = Employee::create([
                'employee_code' => $employeeData['employee_code'],
                'employee_name' => $employeeData['employee_name'],
                'gender' => $employeeData['gender'],
                'dob' => $employeeData['dob'],
                'birth_id' => $employeeData['birth_id'],
                'blood_group' => $employeeData['blood_group'],
                'marital_status' => $employeeData['marital_status'],
                'spouse_name' => $employeeData['spouse_name'],
                'religion' => $employeeData['religion'],
                'mobile' => $employeeData['mobile'],
                'mobile_two' => $employeeData['mobile_two'],
                'emergency_contact_person' => $employeeData['emergency_contact_person'],
                'emergency_contact_number' => $employeeData['emergency_contact_number'],
                'father_name' => $employeeData['father_name'],
                'mother_name' => $employeeData['mother_name'],
                'present_address' => $employeeData['present_address'],
                'permanent_address' => $employeeData['permanent_address'],
                'highest_education' => $employeeData['highest_education'],
                'quite_date' => $employeeData['quite_date'],
                'quite_type' => $employeeData['quite_type'],
                'job_status' => $employeeData['job_status'],
                'photo' => $employeeData['photo'],
                'signature' => $employeeData['signature'],
                'order' => $employeeData['order'],
                'status' => $employeeData['status'],
                'attendance_bonus' => $employeeData['attendance_bonus'],
                'bus_using' => $employeeData['bus_using'],
                'provident_fund' => $employeeData['provident_fund'],
                'income_tax' => $employeeData['income_tax'],
                'mobile_allowance' => $employeeData['mobile_allowance'],
                'over_time' => $employeeData['over_time'],
                'created_by' => $employeeData['created_by'],
            ]);
            // Create and store employee posting data
            EmpPosting::create([
                'employee_id' => $employee->id,
                'type_id' => $employeeData['type_id'],
                'department_id' => $employeeData['department_id'],
                'section_id' => $employeeData['section_id'],
                'line_id' => $employeeData['line_id'],
                'designation_id' => $employeeData['designation_id'],
                'grade_id' => $employeeData['grade_id'],
                'gross_salary' => $employeeData['gross_salary'],
                'ac_number' => $employeeData['ac_number'],
                'salary_section_id' => $employeeData['salary_section_id'],
                'joining_date' => $employeeData['joining_date'],
            ]);

            return back()->with(['message' => 'Employee and posting created successfully!', 'alert-type' => 'success']);
        } catch (\Exception $e) {
            return back()->with(['message' => 'Failed to create employee and posting. Please try again.', 'alert-type' => 'danger']);
        }
    }

    public function _store(Request $request) {
        $request->validate([
            'employee_name' => ['required', 'string', 'max:255'],
        ]);

        DB::beginTransaction();

        try {
            $created_by = Auth::user()->id;
            $input = $request->all();

            if ($request->hasfile('photo')) {
                $image = $request->file('photo');
                $name = date('d-m-Y-H-i-s') . '_' . $image->getClientOriginalName();
                $image_path = $image->storeAs('public/images/employee', $name);
                $input['photo'] = $image_path;
            }
            if ($request->hasfile('signature')) {
                $image = $request->file('signature');
                $name = date('d-m-Y-H-i-s') . '-' . $image->getClientOriginalName();
                $image_path = $image->storeAs('public/images/employee', $name);
                $input['signature'] = $image_path;
            }
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
        }
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