<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TermsCondition extends Model
{
    protected $table = 'terms_conditions';

    protected $fillable = [
        'term_type',
        'term_type1',
        'spot_id',
        'additional_service_id',
        'room_id',
        'term_title',
        'term_description',
        'is_active',
        'sort_order',


    ];

    // TermsCondition.php
    public function spot()
    {
        return $this->belongsTo(Spot::class);
    }

    public function service()
    {
        return $this->belongsTo(AdditionalService::class, 'additional_service_id');
    }
    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    
}
