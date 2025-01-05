<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Order extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','total_amount', 'status'];

    public function items()
    {
        return $this->belongsToMany(Item::class, 'itemorder')->withPivot('quantity')->withTimestamps();
    }
    
        public function user()
        {
            return $this->belongsTo(User::class);
        }

        public function getUpdatedAtAttribute($value)
            {
                return Carbon::parse($value)->format('Y-m-d H:i:s');
            }


}