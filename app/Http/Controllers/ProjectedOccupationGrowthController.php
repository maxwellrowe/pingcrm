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
           'region_ids' => 'required',
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

        // Projected Occupations Growth Output -- main array
        $projected_occupations_growth_array = array();

        // Cycle through each occupation selected
        foreach($request->occupations as $occupation) {
            // Get Projections for Occupation Growth
            // Dimensions are "levels" within the Classification API  - 2 equals lotocc and 3 equals lotspecocc
            $dimension = $occupation['dimension'];
            $id = $occupation['lot_id'];
            $region_ids = $request->region_ids;

            // We're going to push each region into this array
            $projected_occupation_growth_regions = array();

            // Cycle through each selected region
            foreach($request->region_ids as $region_id) {
                // Set the region level to State
                $region_level = "state";
                // FIPS Code -- this is how we set the region
                $fips = $region_id['fips_code'];

                // Now we get the region data for the occupation
                $proj_occupation_growth_region = $this->projected_occupation_growth($apitoken, $dimension, $id, $region_level, $fips);

                // push to the array, we'll eventually pass this to the main array
                array_push($projected_occupation_growth_regions, $proj_occupation_growth_region);
            }

            // Output the United States/ National Data Region
            // Set the region level to "nation"
            $region_level = 'nation';
            $region = 'US';
            $proj_occupation_growth_nation = $this->projected_occupation_growth($apitoken, $dimension, $id, $region_level, $region);
            // Push it up into the region array
            array_push($projected_occupation_growth_regions, $proj_occupation_growth_nation);

            // Handle the chart data here
            // Pass the dataset for the chart here
            $chart_datasets = array();
            $chart_labels = array('Year 1', 'Year 2', 'Year 3', 'Year 5', 'Year 10');

            foreach($projected_occupation_growth_regions as $chart_region) {
                $chart_region_data = array();

                $og_chart_region_data = array_values(json_decode(json_encode($chart_region->growthPercent), true));

                foreach($og_chart_region_data as $og_region) {
                    array_push($chart_region_data, $og_region * 100);
                }

                $chart_region_array = array(
                  'label' => $chart_region->region->name,
                  'data' => $chart_region_data
                );

                // push to our array for chart regions
                array_push($chart_datasets, $chart_region_array);
            }

            // final set up of data passed for chart
            $chart_data = [
              'labels' => $chart_labels,
              'datasets' => $chart_datasets
            ];

            // Build Occupation Array
            $proj_occupation_growth = [
              'lot_id' => $occupation['lot_id'],
              'name' => $occupation['name'],
              'description_us' => $occupation['description_us'],
              'dimension' => $occupation['dimension'],
              'regions' => $projected_occupation_growth_regions,
              'chart_data' => $chart_data
            ];

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
