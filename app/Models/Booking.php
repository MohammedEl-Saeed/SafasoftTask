<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
     use HasFactory;

    protected $fillable = [
        'pitch_id','customer_name','customer_phone','start_at','end_at'
    ];

    protected $dates = ['start_at','end_at'];

    public function pitch()
    {
        return $this->belongsTo(Pitch::class);
    }
}
