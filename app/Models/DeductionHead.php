<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeductionHead extends Model {

//    use HasFactory;

    protected $fillable = [
        'name',
        'status',
        'created_by',
    ];
    
    protected $table = 'deduction_heads';
    protected $primaryKey = 'id';

}
