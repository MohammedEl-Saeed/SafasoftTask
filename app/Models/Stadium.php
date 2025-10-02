<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stadium extends Model
{
    use HasFactory;

    protected $fillable = ['name','location','open_at','close_at'];

    public function pitches()
    {
        return $this->hasMany(Pitch::class);
    }
}
