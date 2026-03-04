<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Employee;
use App\Models\IncomeHead;

class Income extends Model
{
    protected $fillable = [
        'employee_id',
        'income_head',
        'amount',
        'created_by',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
    public function incomehead()
    {
        return $this->belongsTo(IncomeHead::class, 'income_head');
    }

    public static $IncomeHead =[
        'fixed'      => 'Fixed',
        'percentage' => 'Percentage',
    ];

}