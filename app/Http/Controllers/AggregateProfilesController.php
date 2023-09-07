<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class AggregateProfilesController extends Controller
{
    // Show of page function, connected to the Router
    public function show()
    {

        // API Token received from LightcastAuth.php middleware
        $apitoken = app('aggregate_profiles_access_key');

        // set the props for Vue
        $props = [
            'apitoken' => $apitoken,
        ];

        return Inertia::render('LightcastAPI/AggregateProfiles', $props);

    }
}
