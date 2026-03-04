<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkOrder extends Model
{
    use HasFactory;
     protected $fillable = [
        'work_order_no',
        'client_id',
        'work_items',
        'terms',
        'subject',
        'delivery_date',
        'advance_percent',
        'reference',
    ];

    protected $casts = [
        'work_items' => 'array',
        'terms'      => 'array',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
