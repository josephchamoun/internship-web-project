<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'price', 'quantity'];

    public function suppliers()
    {
        return $this->belongsToMany(Supplier::class, 'item_supplier');
        
    }
    public function orders()
        {
            return $this->belongsToMany(Order::class, 'itemorder')->withPivot('quantity')->withTimestamps();
        }

}