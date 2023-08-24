<?php

namespace App\Http\Controllers;

use App\Models\SkillCategories;
use App\Models\Skills;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProjectedSkillGrowthController extends Controller
{
    // Get Projected Skill Growth Details
    public function get_projected_skill_growth_bulk($apitoken, $skill_id) {

        // Make the post
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "https://emsiservices.com/projected-skill-growth/dimensions/skill",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "{ \"id\": \"$skill_id\" }",
            CURLOPT_HTTPHEADER => [
                "Authorization: Bearer $apitoken",
                "Content-Type: application/json"
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
          echo "cURL Error #:" . $err;
        } else {
          $array = json_decode($response);
          return $array->data;
        }
    }

    // Related Skills
    public function related_skills($skill_id) {

        // API Key
        $apitoken = app('open_api_access_key');

        $curl = curl_init();

        curl_setopt_array($curl, [
          CURLOPT_URL => "https://emsiservices.com/skills/versions/latest/related",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTFIELDS => "{ \"ids\": [ \"$skill_id\" ] }",
          CURLOPT_HTTPHEADER => [
            "Authorization: Bearer $apitoken",
            "Content-Type: application/json"
          ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
          return "cURL Error #:" . $err;
        } else {
          $array = json_decode($response);
          return $array->data;
        }
    }

    // Initial Display
    public function show() {

        $initial_skills_array = Skills::whereIn('type_id', ['ST1','ST2','ST3'])->when(request('term'), function ($query, $term){
            $query->where('name', 'like', "%$term%");
        })->limit(10)->get();


        $props= [
            'skills' => $initial_skills_array,
            'projected_skill_growth' => '',
            'related_skills_projected_growth' => '',
            'projected_skill_growth_id' => ''
        ];

        return Inertia::render('LightcastAPI/ProjectedSkillGrowth', $props);
    }

    // On form submission update
    public function update(Request $request) {

        // Validate the form submission
        $this->validate($request, [
           'skill_id' => 'required',
        ]);

        // Get skills ids from request
        $skill_id = $request->skill_id;

        // API Key
        $apitoken = app('skill_growth_access_key');

        // Growth for skills
        $skill_growth = $this->get_projected_skill_growth_bulk($apitoken, $skill_id);

        // Growth for skills Datatables
        $id = $skill_growth->id;
        $name = $skill_growth->name;
        $growth_category = $skill_growth->growthCategory;
        $one_year = $skill_growth->growthPercent->oneYear;
        $two_year = $skill_growth->growthPercent->twoYear;
        $three_year = $skill_growth->growthPercent->threeYear;
        $five_year = $skill_growth->growthPercent->fiveYear;
        $dimension = $skill_growth->dimension;



        $skill_growth_datatables = array(array($id, $name, $dimension, $growth_category, $one_year, $two_year, $three_year, $five_year ));

        // Then we'll get the related skills and their IDs
        $related_skills_ids = $this->related_skills($skill_id);

        // Then pass the related skills to same projected skill growth function
        $related_skills_projected_growth = array();

        foreach($related_skills_ids as $related_skill) {
            $related_skill_id = $related_skill->id;
            $related_skill_growth = $this->get_projected_skill_growth_bulk($apitoken, $related_skill_id);

            // Vars
            $id = $related_skill_growth->id;
            $name = $related_skill_growth->name;
            $growth_category = $related_skill_growth->growthCategory;
            $one_year = $related_skill_growth->growthPercent->oneYear;
            $two_year = $related_skill_growth->growthPercent->twoYear;
            $three_year = $related_skill_growth->growthPercent->threeYear;
            $five_year = $related_skill_growth->growthPercent->fiveYear;
            $dimension = $related_skill_growth->dimension;

            // Build array for Datatables
            $related_skill_array = array($id, $name, $dimension, $growth_category, $one_year, $two_year, $three_year, $five_year );

            // Push it up to our final array
            array_push($related_skills_projected_growth, $related_skill_array);
        }

        $props= [
            'projected_skill_growth' => $skill_growth,
            'projected_skill_growth_datatables' => $skill_growth_datatables,
            'related_skills_ids' => $related_skills_ids,
            'related_skills_projected_growth' => $related_skills_projected_growth,
            'projected_skill_growth_id' => $skill_id
        ];

        return Inertia::render('LightcastAPI/ProjectedSkillGrowth', $props);
    }
}
