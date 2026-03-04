<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;



// class SpotPackage extends Model
// {
//    use HasFactory;
// }



class SpotPackage extends Model
{

    use HasFactory;

    protected $fillable = [
        'name',
        'persons',
        'price',
        'status',
    ];

    public function spot()
    {
        return $this->belongsTo(Spot::class);
    }
}
