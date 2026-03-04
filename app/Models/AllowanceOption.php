<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AllowanceOption extends Model
{
    protected $fillable = [
        'name',
        'status',
        'created_by',
    ];
    protected $table = 'allowance_options';
    protected $primaryKey = 'id';
}
