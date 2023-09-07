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
        $one_year = $skill_growth->growthPercent->oneYear * 100;
        $two_year = $skill_growth->growthPercent->twoYear * 100;
        $three_year = $skill_growth->growthPercent->threeYear * 100;
        $five_year = $skill_growth->growthPercent->fiveYear * 100;
        $dimension = $skill_growth->dimension;

        $skill_growth_datatables = array(array($id, $name, $dimension, $growth_category, $one_year, $two_year, $three_year, $five_year ));

        // Using same formatting as datatables for charts
        $skill_growth_chart_data_array = array($one_year, $two_year, $three_year, $five_year );
        $skill_growth_chart_labels = array('Year 1','Year 2','Year 3','Year 5');

        $skill_growth_chart_data = array(
          'label' => $name,
          'data' => $skill_growth_chart_data_array
        );

        $skill_growth_chart = [
          'labels' => $skill_growth_chart_labels,
          'datasets' => array($skill_growth_chart_data)
        ];

        // Then we'll get the related skills and their IDs
        $related_skills_ids = global_related_skills($skill_id);

        // Then pass the related skills to same projected skill growth function
        $related_skills_projected_growth = array();

        // Related Skills chart
        $related_skills_projected_growth_chart_data = array();

        foreach($related_skills_ids as $related_skill) {
            $related_skill_id = $related_skill->id;
            $related_skill_growth = $this->get_projected_skill_growth_bulk($apitoken, $related_skill_id);

            // Vars
            $id = $related_skill_growth->id;
            $name = $related_skill_growth->name;
            $growth_category = $related_skill_growth->growthCategory;
            $one_year = $related_skill_growth->growthPercent->oneYear * 100;
            $two_year = $related_skill_growth->growthPercent->twoYear * 100;
            $three_year = $related_skill_growth->growthPercent->threeYear * 100;
            $five_year = $related_skill_growth->growthPercent->fiveYear * 100;
            $dimension = $related_skill_growth->dimension;

            // Build array for Datatables
            $related_skill_array = array($id, $name, $dimension, $growth_category, $one_year, $two_year, $three_year, $five_year );

            // Push it up to our final array
            array_push($related_skills_projected_growth, $related_skill_array);

            // Build the array for the chart data and push to array
            $related_skill_growth_chart_data_array = array($one_year, $two_year, $three_year, $five_year );
            $related_skill_growth_chart_data = array(
              'label' => $name,
              'data' => $related_skill_growth_chart_data_array
            );

            array_push($related_skills_projected_growth_chart_data, $related_skill_growth_chart_data);
        }

        // Related skills chart
        $related_skills_projected_growth_chart = [
          'labels' => $skill_growth_chart_labels,
          'datasets' => $related_skills_projected_growth_chart_data
        ];

        $props= [
            'projected_skill_growth' => $skill_growth,
            'projected_skill_growth_datatables' => $skill_growth_datatables,
            'projected_skill_growth_chart' => $skill_growth_chart,
            'related_skills_ids' => $related_skills_ids,
            'related_skills_projected_growth' => $related_skills_projected_growth,
            'related_skills_projected_growth_chart' => $related_skills_projected_growth_chart,
            'projected_skill_growth_id' => $skill_id
        ];

        return Inertia::render('LightcastAPI/ProjectedSkillGrowth', $props);
    }
}
