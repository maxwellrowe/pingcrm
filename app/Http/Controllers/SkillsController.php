<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Inertia\Inertia;

class SkillsController extends Controller
{
    // Index
    public function index() {

        // Skill Types
        $skill_types = global_skill_types();

        // Version
        $version = global_current_skills_version();

        $props = [
            'skills' => '',
            'skill_types' => $skill_types,
            'version' => $version
        ];

        return Inertia::render('LightcastAPI/Skills', $props);
    }

    // Update
    public function update(Request $request) {

        $this->validate($request, [
           'skill_type' => 'required',
        ]);

        // Skill Types
        $skill_types = global_skill_types();

        // Version
        $version = global_current_skills_version();

        $type_id = $request->skill_type;

        // Call global helper to get Skills based on Type ID
        $skills_array = global_skills_array($type_id);

        // Push to this array for datatables
        $skills_datatables = array();

        foreach($skills_array as $skill) {
            $id = $skill->id;
            $name = $skill->name;
            $description = $skill->description;
            $category = $skill->category_name;
            $subcategory = $skill->subcategory_name;
            $type_id = $skill->type_id;
            $type_name = $skill->type_name;

            $skill_array = array($id, $name, $description, $category, $subcategory, $type_id, $type_name);

            array_push($skills_datatables, $skill_array);

        }

        $props = [
            'skills' => $skills_datatables,
            'skill_types' => $skill_types,
            'version' => $version
        ];

        return Inertia::render('LightcastAPI/Skills', $props);

    }
}
