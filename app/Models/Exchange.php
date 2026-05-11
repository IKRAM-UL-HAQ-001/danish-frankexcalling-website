<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exchange extends Model
{
    use HasFactory;
    
    public function user()
    {
        return $this->hasMany(User::class);
    }

    public function complaints()
    {
        return $this->hasMany(Complaint::class);
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
    public function dataentry(){
        return $this->hasMany(DataEntry::class, 'exchange_id');
    }
}
