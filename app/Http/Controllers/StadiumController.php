<?php

namespace App\Http\Controllers;

use App\Models\Stadium;

class StadiumController extends Controller
{
    public function index()
    {
        return Stadium::all();
    }

    public function pitches(Stadium $stadium)
    {
        return $stadium->pitches;
    }
}
