<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataEntry extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'phone_id', 'feedback', 'amount', 'task_name', 'exchange_id', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Each DataEntry belongs to one Exchange
    public function exchange()
    {
        return $this->belongsTo(Exchange::class, 'exchange_id');
    }

    // Each DataEntry belongs to one PhoneNumber
    public function phone()
    {
        return $this->belongsTo(PhoneNumber::class, 'phone_id');
    }
}
