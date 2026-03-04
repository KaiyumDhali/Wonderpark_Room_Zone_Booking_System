<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class payrollFormula extends Model
{
    use HasFactory;
    protected $guarded = ['id', 'created_at', 'updated_at'];
    protected $fillable  = ['payroll_head', 'formula', 'remarks', 'status', 'created_by'];

    public function payrollhead()
    {
        return $this->belongsTo(PayrollHead::class, 'payroll_head');
    }
}
