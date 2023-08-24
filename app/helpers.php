<?php
// Helper functions available globally

use App\Models\Settings;
use Illuminate\Support\Facades\DB;

// US States -- Get an Array of States
function global_states_array() {

    // Get states from DB
    $states = DB::table('states')->get();

    // Return all states as array
    return $states;
}


// Classifications -- LOT Occupations
function global_lot_occupations_array() {
    // Get LOT Occupations from DB
    $lot_occupations = DB::table('lot_occupations')->get();

    // Return all LOT Occupations as array
    return $lot_occupations;
}

// Classifications -- LOT Occupations and Specialized Occupations
function global_lot_and_special_occupations_array() {
    // Get LOT Occupations from DB
    $lot_occupations = DB::table('lot_occupations')
        ->where('level', '2')
        ->orWhere('level', '3')
        ->orderBy('name')
        ->get();

    // Return all LOT Occupations as array
    return $lot_occupations;
}

// Skills -- Where $type_id is a value of ST1, ST2, or ST3 dependent on need
// ST1 = Common Skill, ST2 = Specialized Skill, ST3 = Certification
function global_skills_array($type_id) {
    // Get from DB
    $skills = DB::table('skills')
        ->where('type_id', $type_id)
        ->orderBy('name')
        ->get();

    return $skills;
}

// Get the current version of LOT Occupations based on last import
function global_current_lot_occupation_version() {
    // Get from DB
    $setting = Settings::where('option_name', 'lot_occupation_version_current')->first();

    return $setting->option_value;
}

function global_current_skills_version() {
    // Get from DB
    $setting = Settings::where('option_name', 'skills_version_current')->first();

    return $setting->option_value;
}

function global_current_skill_categories_version() {
    // Get from DB
    $setting = Settings::where('option_name', 'skill_categories_version')->first();

    return $setting->option_value;
}
