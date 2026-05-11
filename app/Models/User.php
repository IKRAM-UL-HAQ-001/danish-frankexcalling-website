<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['name', 'password', 'exchange_id'];

    public function exchange()
    {
        return $this->belongsTo(Exchange::class);
    }

    public function dataentry()
    {
        return $this->hasMany(DataEntry::class);
    }
    
    public function demosends()
    {
        return $this->hasMany(DemoSend::class);
    }

    public function followups()
    {
        return $this->hasMany(FollowUp::class);
    }
    
    public function referids()
    {
        return $this->hasMany(ReferId::class);
    }

    public function rejects()
    {
        return $this->hasMany(Reject::class);
    }

    public function walks()
    {
        return $this->hasMany(Walk::class);
    }
 
    public function phone()
    {
        return $this->hasMany(PhoneNumber::class);
    }
    public function ipAddress()
{
    return $this->hasOne(IpAddress::class, 'user_id', 'id');
}
}
