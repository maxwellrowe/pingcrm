<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Inertia\Inertia;

class SkillsController extends Controller
{
    // Get Skills Function
    public function skills_array($type_id) {
        // Get from DB
        $skills = DB::table('skills')
            ->where('type_id', $type_id)
            ->orderBy('name')
            ->get(['id','name']);

        return $skills;
    }

    // Function to look up all IDs based on user input


    // Index
    public function index() {
        // TO DO: Have user enter this value
        $type_id = 'ST1';

        // Call global helper to get Skills based on Type ID
        $skills = $this->skills_array($type_id);

        // Version
        $version = global_current_skills_version();

        $props = [
            'skills' => $skills,
            'version' => $version
        ];

        return Inertia::render('LightcastAPI/Skills', $props);
    }
}
