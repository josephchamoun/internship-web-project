<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = ['user', 'status'];

    public function items()
    {
        return $this->belongsToMany(Item::class, 'itemorder')->withPivot('quantity')->withTimestamps();
    }


}