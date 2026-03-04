<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Commission extends Model
{
    protected $fillable = [
        'employee_id',
        'title',
        'amount',
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

    public static $commissiontype = [
        'fixed'=>'Fixed',
        'percentage'=> 'Percentage',
    ];
}
