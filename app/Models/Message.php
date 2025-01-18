<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Message extends Model
{
    use HasFactory;
    

    protected $fillable = ['sender_id', 'receiver_id', 'message', 'subject'];


    public function user()
    {
        return $this->belongsTo(User::class, 'sender_id'); // Assuming 'sender_id' is the foreign key
    }
    


public function receiver()
{
    return $this->belongsTo(User::class, 'receiver_id');
}

}
