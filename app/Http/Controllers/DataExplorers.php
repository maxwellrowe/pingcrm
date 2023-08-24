<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

class DataExplorers extends Controller
{
    public function index()
    {
        return Inertia::render('DataExplorers/Index');
    }
}
