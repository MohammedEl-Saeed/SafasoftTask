<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pitch extends Model
{
   use HasFactory;

    protected $fillable = ['stadium_id','name','notes'];

    public function stadium()
    {
        return $this->belongsTo(Stadium::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
