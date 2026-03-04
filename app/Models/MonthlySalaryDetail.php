<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonthlySalaryDetail extends Model
{
    use HasFactory;
    protected $fillable = [
        'monthly_salary_id',
        'head_id',
        'head_name',
        'head_type',
        'amount',
        'created_by',
    ];
}
