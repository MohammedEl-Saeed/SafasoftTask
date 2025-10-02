<?php

namespace App\Http\Controllers;

use App\Models\Stadium;

class StadiumController extends Controller
{
    public function index()
    {
        return response()->json(['stadiums' => Stadium::all()]);
    }

    public function pitches(Stadium $stadium)
    {
        return response()->json(['pitches' => $stadium->pitches]);
    }
}
