<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Employee;
use App\Models\AllowanceOption;

class Allowance extends Model
{
    protected $fillable = [
        'employee_id',
        'allowance_option',
        'title',
        'amount',
        'created_by',
    ];

    // public function employee()
    // {
    //     return $this->belongsTo('App\Models\Employee', 'id', 'employee_id')->first();
    // }

    // public function allowance_option()
    // {
    //     return $this->belongsTo('App\Models\AllowanceOption', 'id', 'allowance_option')->first();
    // }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
    public function allowanceoption()
    {
        return $this->belongsTo(AllowanceOption::class, 'allowance_option');
    }

    public static $Allowancetype =[
        'fixed'      => 'Fixed',
        'percentage' => 'Percentage',
    ];

}