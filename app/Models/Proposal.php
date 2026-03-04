<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Proposal extends Model
{
    protected $fillable = [
        'proposal_title',
        'client_id',
        'client_email',
        'client_phone',
        'intro_text',
        'terms_text',
        'proposal_number',
        'notes_text',
        'status',
        'subtotal',
        'discount',
        'tax',
        'total',
        'created_by',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(ProposalItem::class);
    }

    public function itemsOf(string $type)
    {
        return $this->items()->where('item_type', $type);
    }

     public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'client_id');
    }
}
