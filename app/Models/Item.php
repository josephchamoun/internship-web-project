<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'price', 'quantity','gender', 'age', 'category_id'];

    public function suppliers()
    {
        return $this->belongsToMany(Supplier::class, 'item_supplier');
        
    }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function orders()
    {
        return $this->belongsToMany(Order::class, 'itemorder')->withPivot('quantity')->withTimestamps();
    }

}