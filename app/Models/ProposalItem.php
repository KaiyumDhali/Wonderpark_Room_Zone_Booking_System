<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProposalItem extends Model
{
    protected $fillable = [
        'proposal_id',
        'item_type',
        'item_id',
        'title',
        'description',
        'quantity',
        'nights',
        'unit_price',
        'line_total',
        'meta_json',
    ];

    protected $casts = [
        'meta_json' => 'array',
    ];

    public function proposal(): BelongsTo
    {
        return $this->belongsTo(Proposal::class);
    }
}
