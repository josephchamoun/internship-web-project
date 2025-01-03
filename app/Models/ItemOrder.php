<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemOrder extends Model
{
    use HasFactory;

    // Define the table name (if it doesn't follow Laravel's naming convention)
    protected $table = 'itemorder';

    // Define the fillable fields for mass assignment
    protected $fillable = [
        'order_id',
        'item_id',
        'quantity',
    ];

    // Define relationships
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
