<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Spot extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'area_size',
        'max_capacity',
        'description',
        'price',
        'regular_price',
        'image',
        'status',
        'spot_order',
    ];

    public function spot_detail()
    {
        return $this->hasMany(SpotDetail::class, 'spot_id');
    }
    public function facilities()
{
    return $this->hasMany(SpotFacility::class);
}


}
