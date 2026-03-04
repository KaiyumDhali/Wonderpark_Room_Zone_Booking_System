<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Employee;
use App\Models\LoanOption;

class Loan extends Model
{
    protected $fillable = [
        'employee_id',
        'loan_option',
        'title',
        'amount',
        'start_date',
        'end_date',
        'reason',
        'created_by',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
    public function loanoption()
    {
        return $this->belongsTo(LoanOption::class, 'loan_option');
    }
    public static $Loantypes=[
        'fixed'=>'Fixed',
        'percentage'=> 'Percentage',
    ];
}
