<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemSupplier extends Model
{
    use HasFactory;

    protected $table = 'item_supplier';

    protected $fillable = ['item_id', 'supplier_id', 'quantity', 'price'];

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }

    
    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }
}