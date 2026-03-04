<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FrontendDetailRoom extends Model
{
    use HasFactory;

    protected $table = 'room_details'; // Specify the table name

    protected $fillable = ['room_id', 'image_path', 'description'];

    // Define the inverse relationship with the rooms table
    public function room()
    {
        return $this->belongsTo(FrontendModel::class, 'room_id', 'id');
    }
}