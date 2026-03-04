<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\CompanySetting;

use App\Models\User;
use App\Models\Employee;
use App\Models\EmpPosting;

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
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Carbon\Carbon;
// use validator;
use DB;
use Barryvdh\DomPDF\Facade\Pdf;



class EmployeeController extends Controller
{

    function __construct()
    {
        $this->middleware('permission:read employee|write employee|create employee', ['only' => ['index', 'show']]);
        $this->middleware('permission:create employee', ['only' => ['create', 'store']]);
        $this->middleware('permission:write employee', ['only' => ['edit', 'update', 'destroy']]);
    }

    public function employeeProfile()
    {
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

    public function branchDepartment($id)
    {
        $departments = EmpDepartment::where('status', 1)
            ->where('branch_id', $id)
            ->select('emp_departments.id', 'emp_departments.department_name')
            ->get();
        return response()->json($departments);
    }

    public function branchEmployees($id)
    {
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

    public function index()
    {
        $employees = Employee::where('status', 1)->pluck('employee_name', 'employee_code')->all();
        $allEmpDesignation = EmpDesignation::where('status', 1)->pluck('designation_name', 'id')->all();
        $allEmpDepartment = EmpDepartment::where('status', 1)->pluck('department_name', 'id')->all();
        // $allEmpSection = EmpSection::where('status', 1)->pluck('section_name', 'id')->all();
        // $allEmpLine = EmpLine::where('status', 1)->pluck('line_name', 'id')->all();
        $allEmpType = EmpType::where('status', 1)->pluck('type_name', 'id')->all();
        // $allEmpSalarySection = EmpSalarySection::where('status', 1)->pluck('salary_section_name', 'id')->all();

        return view('pages.employee.employees.index', compact('employees', 'allEmpDesignation', 'allEmpDepartment', 'allEmpType'));
    }

    public function employeesSearch($employeeCode, $typeId, $departmentId, $designationId, $pdf)
    {
        // $query = "
        // SELECT
        //     e.id,
        //     e.order,
        //     e.employee_code,
        //     e.employee_name,
        //     ep.type_id,
        //     et.type_name,
        //     ep.department_id,
        //     ed.department_name,
        //     ep.designation_id,
        //     eds.designation_name,
        // FROM
        //     employees e
        // JOIN emp_postings AS ep ON ep.employee_id = e.id
        // JOIN emp_types AS et ON et.id = ep.type_id
        // JOIN emp_departments AS ed ON ed.id = ep.department_id
        // JOIN emp_designations AS eds ON eds.id = ep.designation_id
        // WHERE 
        //     (e.employee_code = $employeeCode OR $employeeCode = '0')
        //     AND (ep.type_id = $typeId OR $typeId = '0') 
        //     AND (ep.department_id = $departmentId OR $departmentId = '0') 
        //     AND (ep.designation_id = $designationId OR $designationId = '0') 
        // ";
        // $employeesSearch = DB::table(DB::raw("($query) AS subquery"))
        //     ->select('id', 'order', 'employee_code', 'employee_name', 'type_name', 'department_name', 'designation_name')
        //     ->orderBy('employee_code')
        //     ->get();


        $employeesSearch = DB::table('employees as e')
            ->join('emp_postings as ep', 'ep.employee_id', '=', 'e.id')
            ->join('emp_types as et', 'et.id', '=', 'ep.type_id')
            ->join('emp_departments as ed', 'ed.id', '=', 'ep.department_id')
            ->join('emp_designations as eds', 'eds.id', '=', 'ep.designation_id')
            ->select(
                'e.id',
                DB::raw('e.`order` as emp_order'),
                'e.employee_code',
                'e.employee_name',
                'et.type_name',
                'ed.department_name',
                'eds.designation_name'
            )
            ->when($employeeCode != 0, fn($q) => $q->where('e.employee_code', $employeeCode))
            ->when($typeId != 0, fn($q) => $q->where('ep.type_id', $typeId))
            ->when($departmentId != 0, fn($q) => $q->where('ep.department_id', $departmentId))
            ->when($designationId != 0, fn($q) => $q->where('ep.designation_id', $designationId))
            ->orderBy('e.employee_code')
            ->get();


        if ($pdf == "list") {
            return response()->json($employeesSearch);
        }
        if ($pdf == "pdfurl") {
            $allEmpDepartment = EmpDepartment::where('status', 1)->pluck('department_name', 'id')->all();
            $companySetting = CompanySetting::where('status', 1)->orderBy('id', 'desc')->first();
            $data['company_name'] = $companySetting->company_name;
            $data['company_logo_one'] = $companySetting->company_logo_one;
            $pdf = PDF::loadView('pages.pdf.employees_pdf', array('employeesSearch' => $employeesSearch, 'allEmpDepartment' => $allEmpDepartment, 'data' => $data));
            return $pdf->stream(Carbon::now() . '-recentstat.pdf');
        }
    }

    public function _index()
    {
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

    public function create()
    {
        $allEmpBranch = EmpBranch::where('status', 1)->pluck('branch_name', 'id')->all();
        $allEmpDepartment = EmpDepartment::where('status', 1)->pluck('department_name', 'id')->all();
        $allEmpDesignation = EmpDesignation::where('status', 1)->pluck('designation_name', 'id')->all();
        $allEmpType = EmpType::where('status', 1)->pluck('type_name', 'id')->all();
        $allEmpSection = EmpSection::where('status', 1)->pluck('section_name', 'id')->all();
        $allEmpLine = EmpLine::where('status', 1)->pluck('line_name', 'id')->all();
        $allEmpGrade = EmpGrade::where('status', 1)->pluck('grade_name', 'id')->all();
        $allEmpSalarySection = EmpSalarySection::where('status', 1)->pluck('salary_section_name', 'id')->all();
        $allEmpQuiteType = EmpQuiteType::where('status', 1)->pluck('quite_type_name', 'id')->all();

        return view('pages.employee.employees._employee-add', compact(
            'allEmpBranch',
            'allEmpDepartment',
            'allEmpDesignation',
            'allEmpType',
            'allEmpSection',
            'allEmpLine',
            'allEmpGrade',
            'allEmpSalarySection',
            'allEmpQuiteType'
        ));
    }

    public function store(Request $request)
    {
        // $employees = $request->all();
        // dd($employees);

        $validatedData = $request->validate([
            // 'branch_id' => 'required|integer',
            'type_id' => 'required|integer',
            'department_id' => 'required|integer',
            // 'section_id' => 'required|integer',
            // 'line_id' => 'required|integer',
            'designation_id' => 'required|integer',
            // 'grade_id' => 'required|integer',
            'gross_salary' => 'required|numeric',
            'ac_number' => 'nullable|string|max:255',
            // 'salary_section_id' => 'required|integer',
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
            // 'quite_date' => 'nullable|date',
            // 'quite_type' => 'nullable|string|max:255',
            'job_status' => 'nullable|string|max:255',
            'photo' => 'nullable|image|max:2048', // Adjust max file size as needed
            'nid_image' => 'nullable|image|max:2048', // Adjust max file size as needed
            'signature' => 'nullable|image|max:2048', // Adjust max file size as needed
            'order' => 'nullable|integer',
            'status' => 'nullable|boolean',
            'salary_held_up' => 'nullable|boolean',
        ]);
        // Process file uploads
        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $photoName = date('d-m-Y-H-i-s') . '_' . $photo->getClientOriginalName();
            $photoPath = $photo->storeAs('public/images/employee', $photoName);
            $validatedData['photo'] = $photoPath;
        }
        if ($request->hasFile('nid_image')) {
            $nidImage = $request->file('nid_image');
            $nidImageName = date('d-m-Y-H-i-s') . '_' . $nidImage->getClientOriginalName();
            $nidImageNamePath = $nidImage->storeAs('public/images/employee', $nidImageName);
            $validatedData['nid_image'] = $nidImageNamePath;
        }
        if ($request->hasFile('signature')) {
            $signature = $request->file('signature');
            $signatureName = date('d-m-Y-H-i-s') . '_' . $signature->getClientOriginalName();
            $signaturePath = $signature->storeAs('public/images/employee', $signatureName);
            $validatedData['signature'] = $signaturePath;
        }
        $validatedData['created_by'] = Auth::user()->name;

        $employeeData = array_map(function ($value) {
            return is_string($value) ? Str::of($value)->stripTags()->trim() : $value;
        }, $validatedData);

        // $employeeData['status'] = empty($input['status']) ? 0 : $input['status'];
        // $employeeData['salary_held_up'] = empty($input['salary_held_up']) ? 0 : $input['salary_held_up'];

        $employeeData['status'] = !empty($input['status']) ? 1 : 0;
        $employeeData['salary_held_up'] = !empty($input['salary_held_up']) ? 1 : 0;
        $employeeData['posting_date'] = $employeeData['joining_date'] ?? Carbon::now()->format('Y-m-d');

        DB::beginTransaction();
        try {
            $empdata = Employee::create($employeeData);
            // Create and store employee posting data
            $empdataId = $empdata->id;
            $currentdate = Carbon::now()->format('Y-m-d');
            $empPosting = EmpPosting::create([
                'employee_id' => $empdataId,
                'type_id' => $employeeData['type_id'],
                'department_id' => $employeeData['department_id'],
                // 'section_id' => $employeeData['section_id'],
                // 'line_id' => $employeeData['line_id'],
                'designation_id' => $employeeData['designation_id'],
                // 'grade_id' => $employeeData['grade_id'],
                'gross_salary' => $employeeData['gross_salary'],
                'ac_number' => $employeeData['ac_number'],
                // 'salary_section_id' => $employeeData['salary_section_id'],
                'joining_date' => $employeeData['joining_date'] ?? $currentdate,
            ]);
            DB::commit();
            // all good
            return back()->with(['message' => 'Employee and posting created successfully!', 'alert-type' => 'success']);
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with(['message' => 'Employee cound not created ! Please Check Again', 'alert-type' => 'danger']);
        }
        return redirect()->route('employees.index')->with(['message' => 'successfully created !', 'alert-type' => 'success']);
    }

    public function show($id)
    {
        $employee = Employee::leftJoin('emp_postings', 'emp_postings.employee_id', '=', 'employees.id')
            ->leftJoin('emp_types', 'emp_types.id', '=', 'emp_postings.type_id')
            ->leftJoin('emp_sections', 'emp_sections.id', '=', 'emp_postings.section_id')
            ->leftJoin('emp_lines', 'emp_lines.id', '=', 'emp_postings.line_id')
            ->leftJoin('emp_grades', 'emp_grades.id', '=', 'emp_postings.grade_id')
            ->leftJoin('emp_salary_sections', 'emp_salary_sections.id', '=', 'emp_postings.salary_section_id')
            ->leftJoin('emp_departments', 'emp_departments.id', '=', 'emp_postings.department_id')
            ->leftJoin('emp_designations', 'emp_designations.id', '=', 'emp_postings.designation_id')
            ->select('employees.*', 'emp_postings.ac_number', 'emp_postings.gross_salary', 'emp_postings.joining_date', 'emp_types.type_name', 'emp_sections.section_name', 'emp_lines.line_name', 'emp_grades.grade_name', 'emp_salary_sections.salary_section_name', 'emp_departments.department_name', 'emp_designations.designation_name')
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
    public function _show($id)
    {
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


    public function employeeProfilePdf($id)
    {
        $employee = Employee::leftJoin('emp_postings', 'emp_postings.employee_id', '=', 'employees.id')
            ->leftJoin('emp_types', 'emp_types.id', '=', 'emp_postings.type_id')
            ->leftJoin('emp_sections', 'emp_sections.id', '=', 'emp_postings.section_id')
            ->leftJoin('emp_lines', 'emp_lines.id', '=', 'emp_postings.line_id')
            ->leftJoin('emp_grades', 'emp_grades.id', '=', 'emp_postings.grade_id')
            ->leftJoin('emp_salary_sections', 'emp_salary_sections.id', '=', 'emp_postings.salary_section_id')
            ->leftJoin('emp_departments', 'emp_departments.id', '=', 'emp_postings.department_id')
            ->leftJoin('emp_designations', 'emp_designations.id', '=', 'emp_postings.designation_id')
            ->select('employees.*', 'emp_postings.ac_number', 'emp_postings.gross_salary', 'emp_postings.joining_date', 'emp_types.type_name', 'emp_sections.section_name', 'emp_lines.line_name', 'emp_grades.grade_name', 'emp_salary_sections.salary_section_name', 'emp_departments.department_name', 'emp_designations.designation_name')
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
        // return view('pages.employee.employees._employee-show', compact('employee', 'employee_educations', 'employee__job_histories', 'employee_job_responsibilities'));

        $allEmpDepartment = EmpDepartment::where('status', 1)->pluck('department_name', 'id')->all();
        $companySetting = CompanySetting::where('status', 1)->orderBy('id', 'desc')->first();
        $data['company_name'] = $companySetting->company_name;
        $data['company_logo_one'] = $companySetting->company_logo_one;
        $pdf = PDF::loadView('pages.pdf.employee_profile_pdf', array('employee' => $employee, 'employee_educations' => $employee_educations, 'employee__job_histories' => $employee__job_histories, 'employee_job_responsibilities' => $employee_job_responsibilities, 'data' => $data));
        return $pdf->stream(Carbon::now() . '-recentstat.pdf');
    }

    // public function edit(Employee $employee) {
    public function edit(Employee $employee)
    {

        $allEmpBranch = EmpBranch::where('status', 1)->pluck('branch_name', 'id')->all();
        $allEmpDepartment = EmpDepartment::where('status', 1)->pluck('department_name', 'id')->all();
        $allEmpDesignation = EmpDesignation::where('status', 1)->pluck('designation_name', 'id')->all();
        $allEmpType = EmpType::where('status', 1)->pluck('type_name', 'id')->all();
        $allEmpSection = EmpSection::where('status', 1)->pluck('section_name', 'id')->all();
        $allEmpLine = EmpLine::where('status', 1)->pluck('line_name', 'id')->all();
        $allEmpGrade = EmpGrade::where('status', 1)->pluck('grade_name', 'id')->all();
        $allEmpSalarySection = EmpSalarySection::where('status', 1)->pluck('salary_section_name', 'id')->all();
        $allEmpQuiteType = EmpQuiteType::where('status', 1)->pluck('quite_type_name', 'id')->all();

        $pmpPosting = EmpPosting::where('employee_id', $employee->id)->first();
        $empEducations = EmployeeEducation::where('employee_id', $employee->id)->get();
        $employeeJobHistorys = EmployeeJobHistory::where('employee_id', $employee->id)->get();
        $employeeJobResponsibilities = EmployeeJobResponsibility::where('employee_id', $employee->id)->get();

        // dd($employeeJobResponsibilities);
        if (auth()->user()->hasRole('employee')) {
            return view('pages.employee.employees.profile_update', compact('employee', 'pmpPosting', 'allEmpBranch', 'allEmpDepartment', 'allEmpDesignation', 'allEmpType', 'allEmpSection', 'allEmpLine', 'allEmpGrade', 'allEmpSalarySection', 'allEmpQuiteType', 'employeeJobResponsibilities', 'employeeJobHistorys', 'empEducations'));
        } else {
            return view('pages.employee.employees._employee-update', compact('employee', 'pmpPosting', 'allEmpBranch', 'allEmpDepartment', 'allEmpDesignation', 'allEmpType', 'allEmpSection', 'allEmpLine', 'allEmpGrade', 'allEmpSalarySection', 'allEmpQuiteType', 'employeeJobResponsibilities', 'employeeJobHistorys', 'empEducations'));
        }
    }

    public function update(Request $request, Employee $employee)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'type_id' => 'required|integer',
            'department_id' => 'required|integer',
            // 'section_id' => 'required|integer',
            // 'line_id' => 'required|integer',
            'designation_id' => 'required|integer',
            // 'grade_id' => 'required|integer',
            'gross_salary' => 'required|numeric',
            'ac_number' => 'nullable|string|max:255',
            // 'salary_section_id' => 'required|integer',
            'joining_date' => 'required|date',
            'employee_code' => 'required|string|max:255',
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
            // 'quite_date' => 'nullable|date',
            // 'quite_type' => 'nullable|string|max:255',
            'job_status' => 'nullable|string|max:255',
            'photo' => 'nullable|image|max:2048', 
            'nid_image' => 'nullable|image|max:2048',
            'signature' => 'nullable|image|max:2048',
            'order' => 'nullable|integer',
            'status' => 'nullable|boolean',
            'salary_held_up' => 'nullable|boolean',
        ]);

        // Handle photo upload
        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $photoName = date('d-m-Y-H-i-s') . '_' . $photo->getClientOriginalName();
            $photoPath = $photo->storeAs('public/images/employee', $photoName);
            $validatedData['photo'] = $photoPath;
        }

        if ($request->hasFile('nid_image')) {
            $nidImage = $request->file('nid_image');
            $nidImageName = date('d-m-Y-H-i-s') . '_' . $nidImage->getClientOriginalName();
            $nidImageNamePath = $nidImage->storeAs('public/images/employee', $nidImageName);
            $validatedData['nid_image'] = $nidImageNamePath;
        }

        // Handle signature upload
        if ($request->hasFile('signature')) {
            $signature = $request->file('signature');
            $signatureName = date('d-m-Y-H-i-s') . '_' . $signature->getClientOriginalName();
            $signaturePath = $signature->storeAs('public/images/employee', $signatureName);
            $validatedData['signature'] = $signaturePath;
        }

        // Add the creator's name
        $validatedData['created_by'] = Auth::user()->name;

        // Clean the validated data
        $employeeData = array_map(function ($value) {
            return is_string($value) ? Str::of($value)->stripTags()->trim() : $value;
        }, $validatedData);

        $employeeData['status'] = empty($employeeData['status']) ? 0 : $employeeData['status'];
        $employeeData['salary_held_up'] = empty($employeeData['salary_held_up']) ? 0 : $employeeData['salary_held_up'];
        $employeeData['posting_date'] = $employeeData['joining_date'] ?? Carbon::now()->format('Y-m-d');

        // Begin a database transaction
        DB::beginTransaction();
        try {
            // Update employee record
            $employee->update($employeeData);

            /// Update associated posting details using Eloquent relationship
            $employee->posting()->update([
                'type_id' => $employeeData['type_id'],
                'department_id' => $employeeData['department_id'],
                // 'section_id' => $employeeData['section_id'],
                // 'line_id' => $employeeData['line_id'],
                'designation_id' => $employeeData['designation_id'],
                // 'grade_id' => $employeeData['grade_id'],
                'gross_salary' => $employeeData['gross_salary'],
                'ac_number' => $employeeData['ac_number'],
                // 'salary_section_id' => $employeeData['salary_section_id'],
                'joining_date' => $employeeData['joining_date'],
            ]);

            // Commit the transaction
            DB::commit();

            // Redirect back with success message
            return back()->with(['message' => 'Employee and posting updated successfully!', 'alert-type' => 'success']);
        } catch (\Exception $e) {
            // Rollback the transaction in case of an error
            DB::rollback();
            // Log the error
            Log::error('Error updating employee: ' . $e->getMessage());
            // Redirect back with error message
            return back()->with(['message' => 'An error occurred while updating the employee. Please try again later.', 'alert-type' => 'danger']);
        }
    }

    public function _update(Request $request, Employee $employee)
    {

        $request->validate([
            'employee_name' => 'required|string|max:255',
            // 'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            // 'email' => 'unique:users,email,' . $employee->user_id
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

        if ($request->hasFile('nid_image')) {
            $nidImage = $request->file('nid_image');
            $nidImageName = date('d-m-Y-H-i-s') . '_' . $nidImage->getClientOriginalName();
            $nidImageNamePath = $nidImage->storeAs('public/images/employee', $nidImageName);
            $validatedData['nid_image'] = $nidImageNamePath;
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

    public function destroy(Employee $employee)
    {
        $employee->delete();
        return redirect()->route('employees.index')->with([
            'message' => 'successfully deleted !',
            'alert-type' => 'danger'
        ]);
    }
}
