<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class payroll extends Model
{
    use HasFactory;
    protected $guarded = ['id', 'created_at', 'updated_at'];
    protected $fillable  = ['employee_id', 'payroll_head_id', 'remarks', 'effective_date', 'created_by'];

    public function payrollhead()
    {
        return $this->belongsTo(PayrollHead::class, 'payroll_head_id');
    }
}
