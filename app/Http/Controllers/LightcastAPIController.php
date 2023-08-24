<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class LightcastAPIController extends Controller
{
    public function index()
    {
        return Inertia::render('LightcastAPI/Index');
    }

}
