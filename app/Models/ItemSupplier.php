<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use DateTimeInterface;
class ItemSupplier extends Model
{
    use HasFactory;

    protected $table = 'item_supplier';

    protected $fillable = ['item_id', 'supplier_id', 'quantity', 'buyprice'];

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }

    
    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    public function getUpdatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d H:i:s');
    }
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s'); // Customize the date format
    }

}