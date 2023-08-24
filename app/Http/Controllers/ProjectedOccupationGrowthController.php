<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class ProjectedOccupationGrowthController extends Controller
{
    // *********************************************
    // Projected Occupation Growth
    // *********************************************

    // Function to get the Projected Occupation Growth
    function projected_occupation_growth($apitoken, $dimension, $id, $region_level, $region_id)
    {
        $curl = curl_init();

        $post_fields = array(
            "id" => $id,
            "region" => array(
                "level" => $region_level,
                "id" => $region_id
            )
        );

        curl_setopt_array($curl, [
          CURLOPT_URL => "https://emsiservices.com/projected-occupation-growth/dimensions/$dimension",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTFIELDS => json_encode($post_fields),
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
            // Get just the object
            $array = json_decode($response);
            return $array->data;
        }
    }

    // function to get meta data for job projections
    function projected_occupation_growth_meta($apitoken)
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
          CURLOPT_URL => "https://emsiservices.com/projected-occupation-growth/meta",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "GET",
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
            // Get just the object
            $array = json_decode($response);
            return $array->data;
        }
    }

    // Dimension Meta
    function projected_occupation_growth_dimension_meta($apitoken, $dimension) {
        if(!empty($dimension)) {
            $curl = curl_init();

            curl_setopt_array($curl, [
              CURLOPT_URL => "https://emsiservices.com/projected-occupation-growth/dimensions/$dimension",
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => "",
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 30,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => "GET",
              CURLOPT_HTTPHEADER => [
                "Authorization: Bearer $apitoken"
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
        } else {
            return 'No dimension defined.';
        }
    }

    // Show of page function, connected to the Router
    public function show()
    {

        // API Token received from LightcastAuth.php middleware
        $apitoken = app('projected_occupation_growth_access_key');

        // Get Projections Meta Information
        $proj_occupation_meta = $this->projected_occupation_growth_meta($apitoken);

        // Get dimensions meta data
        // push dimensions to this array
        $proj_occupation_dimensions = array();
        $dimensions = $proj_occupation_meta->dimensions;
        foreach($dimensions as $dimension) {
            array_push($proj_occupation_dimensions, $this->projected_occupation_growth_dimension_meta($apitoken, $dimension));
        }

        // Region Levels
        // push the region options to array
        $proj_occupation_region_levels = array();
        $region_levels = $proj_occupation_meta->regions->nationLevels->us;
        foreach($region_levels as $region_level) {
            array_push($proj_occupation_region_levels, $region_level);
        }

        // set the props for Vue
        $props = [
            'proj_occupation_growth' => '',
            'proj_occupation_region_levels' => $proj_occupation_region_levels,
            'proj_occupation_meta' => $proj_occupation_meta,
            'proj_occupation_dimensions' => $proj_occupation_dimensions,
            'states' => global_states_array(),
            'lot_occupations' => global_lot_and_special_occupations_array(),
            'apitoken' => $apitoken,
        ];

        return Inertia::render('LightcastAPI/ProjectedOccupationGrowth', $props);

    }

    // Form submission --> Update
    public function update(Request $request)
    {
        // API Token received from LightcastAuth.php middleware
        $apitoken = app('projected_occupation_growth_access_key');

        // Validate the form submission
        $this->validate($request, [
           'occupations' => 'required',
           'region_id' => 'required',
        ]);

        // Get Projections Meta Information
        $proj_occupation_meta = $this->projected_occupation_growth_meta($apitoken);

        // Get dimensions meta data
        // push dimensions to this array
        $proj_occupation_dimensions = array();
        $dimensions = $proj_occupation_meta->dimensions;
        foreach($dimensions as $dimension) {
            array_push($proj_occupation_dimensions, $this->projected_occupation_growth_dimension_meta($apitoken, $dimension));
        }

        // Region Levels
        // push the region options to array
        $proj_occupation_region_levels = array();
        $region_levels = $proj_occupation_meta->regions->nationLevels->us;
        foreach($region_levels as $region_level) {
            array_push($proj_occupation_region_levels, $region_level);
        }

        // Projected Occupations Growth Output
        $projected_occupations_growth_array = array();

        foreach($request->occupations as $occupation) {
            // Get Projections for Occupation Growth
            // Dimensions are "levels" within the Classification API  - 2 equals lotocc and 3 equals lotspecocc
            $dimension = $occupation['dimension'];
            $id = $occupation['lot_id'];
            $region_id = $request->region_id;

            // If the region selected is "us" then set region_level to nation, else set to state
            if($region_id == 'us') {
                $region_level = 'nation';
            } else {
                $region_level = "state";
            }

            $proj_occupation_growth = $this->projected_occupation_growth($apitoken, $dimension, $id, $region_level, $region_id);

            // Push to our array
            array_push($projected_occupations_growth_array, $proj_occupation_growth);
        }

        // set the props for Vue
        $props = [
            'proj_occupation_growth' => $projected_occupations_growth_array,
            'proj_occupation_region_levels' => $proj_occupation_region_levels,
            'proj_occupation_meta' => $proj_occupation_meta,
            'proj_occupation_dimensions' => $proj_occupation_dimensions,
            'states' => global_states_array(),
            'lot_occupations' => global_lot_and_special_occupations_array(),
            'apitoken' => $apitoken,
        ];

        return Inertia::render('LightcastAPI/ProjectedOccupationGrowth', $props);

    }
}
