<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Employee;
use App\Models\DeductionHead;


class Deduction extends Model
{
//    use HasFactory;
        protected $fillable = [
        'employee_id',
        'deduction_head',
        'amount',
        'created_by',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
    public function deductionhead()
    {
        return $this->belongsTo(DeductionHead::class, 'deduction_head');
    }

    public static $DeductionHead =[
        'fixed'      => 'Fixed',
        'percentage' => 'Percentage',
    ];
}
