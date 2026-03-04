<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Casts\Attribute;

class FrontendModel extends Model
{
    use HasFactory;
    protected $table = 'rooms';
   
    
    public function details()
{
    return $this->hasMany(FrontendDetailRoom::class, 'room_id', 'id');
}

    public function booking(): HasMany {
        return $this->hasMany(FrontendBookings::class, 'room_id', 'id');
    }

    
    
}
