<?php

namespace App\Http\Controllers;

use App\Models\LotOccupations;
use App\Models\Skills;
use App\Models\SkillTypes;
use App\Models\SkillCategories;
use App\Models\Settings;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class SettingsController extends Controller
{
    public function index()
    {
        return Inertia::render('Settings/Index');
    }

    /*********************
    Skill Categories and Subcategories Updater
    **********************/
    // Get Skill Subcategories from Classifcations API
    public function get_subcategories() {

        $classification_access_token = app('classifications_access_key');

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "https://classification.emsicloud.com/taxonomies/skills/versions/8.9.0/concepts?fields=name%2Cid%2Clevel%2CparentId&filter=level%3A1&limit=1000",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => [
                "Authorization: Bearer $classification_access_token"
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            $array = json_decode($response);

            $array = $array->data;

            return $array;
        }
    }

    // Get Skill Categories
    public function get_categories() {

        $classification_access_token = app('classifications_access_key');

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "https://classification.emsicloud.com/taxonomies/skills/versions/8.9.0/concepts?fields=name%2Cid%2Clevel%2CparentId&filter=level%3A0&limit=1000",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => [
                "Authorization: Bearer $classification_access_token"
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            $array = json_decode($response);

            $array = $array->data;

            return $array;
        }
    }

    // Initial Show of Categories page
    public function skill_cats_show() {

        // Set api token for Open API
        $apitoken = app('open_api_access_key');

        // Skills Version Details
        $skills_version_info = $this->skills_version_info($apitoken);

        $props = [
            'apitoken' => $apitoken,
            'skills_version_info' => $skills_version_info,
            'skills_current_version' => global_current_skill_categories_version()
        ];

        return Inertia::render('Settings/SkillCategories', $props);
    }

    // Import Categories
    public function skill_cats_import() {

        // Set api token for Open API to get version
        $apitoken = app('open_api_access_key');

        // Skills Version Details
        $skills_version_info = $this->skills_version_info($apitoken);
        $version = $skills_version_info->version;

        // Get the cats and subs
        $subcategories = $this->get_subcategories();
        $categories = $this->get_categories();

        // Remove current entries
        SkillCategories::truncate();

        foreach($subcategories as $subcategory) {
            $name = $subcategory->name;
            $id = $subcategory->id;
            $level = $subcategory->level;
            $levelName = $subcategory->levelName;
            $parentId = $subcategory->parentId;

            $insert_subcategory = ['name'=>$name,'id'=>$id,'level'=>$level,'levelName'=>$levelName, 'parentId'=>$parentId];

            SkillCategories::insert($insert_subcategory);
        }

        foreach($categories as $category) {
            $name = $category->name;
            $id = $category->id;
            $level = $category->level;
            $levelName = $category->levelName;
            $parentId = $category->parentId;

            $insert_category = ['name'=>$name,'id'=>$id,'level'=>$level,'levelName'=>$levelName, 'parentId'=>$parentId];

            SkillCategories::insert($insert_category);
        }



        // Update the Version in settings table
        $skill_cat_version = Settings::where('option_name', 'skill_categories_version')->first();
        $skill_cat_version->updated_at = now();
        $skill_cat_version->option_value = $version;
        $skill_cat_version->save();

        // If all good,then redirect back and success!
        return Redirect::back()->with('success', 'Hot diggity! Skill categories imported successfully!');
    }

    /*********************
    Skills Updater
    *********************/

    // Get Version Information (for the latest version)

    // Get all latest version of skills via Lightcast API
    public function get_skills($apitoken, $limit) {
        // Latest All Skills
        $curl = curl_init();

        // Fields we want to get
        $fields = array('id','type','name','isSoftware','infoUrl','isLanguage','description','category','subcategory');

        $fields = implode(',', $fields);

        curl_setopt_array($curl, [
            CURLOPT_URL => "https://emsiservices.com/skills/versions/latest/skills?fields=$fields$limit",
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
            return "cURL Error #:" . $err;
        } else {
            $skills = json_decode($response);
            $skills = $skills->data;

            return $skills;

        }
    }

    // Version Information
    public function skills_version_info($apitoken) {
        $curl = curl_init();

        curl_setopt_array($curl, [
          CURLOPT_URL => "https://emsiservices.com/skills/versions/latest",
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
          return "cURL Error #:" . $err;
        } else {
          $skills_version = json_decode($response);
          $skills_version = $skills_version->data;

          return $skills_version;
        }
    }

    // Clean Up Skills Output for consumption
    // The biggest thing we're doing here is
    public function clean_skills($skills_array) {

        // Final Array of Cleaned Skills
        $clean_skills_array = array();

        foreach($skills_array as $skill) {
            // Vars
            $id = $skill->id;
            $name = $skill->name;
            $isSoftware = $skill->isSoftware;
            $infoUrl = $skill->infoUrl;
            $isLanguage = $skill->isLanguage;
            $description = $skill->description;
            $category_name = $skill->category->name;
            $category_id = $skill->category->id;
            $subcategory_id = $skill->subcategory->id;
            $subcategory_name = $skill->subcategory->name;
            $type_id = $skill->type->id;
            $type_name = $skill->type->name;

            $skill_array = array(
                'id'=>$id,
                'name' => $name,
                'description' => $description,
                'type_id' => $type_id,
                'type_name' => $type_name,
                'category_id' => $category_id,
                'category_name' => $category_name,
                'subcategory_id' => $subcategory_id,
                'subcategory_name' => $subcategory_name,
                'isSoftware' => $isSoftware,
                'isLanguage' => $isLanguage,
                'infoUrl' => $infoUrl,
            );

            array_push($clean_skills_array, $skill_array);
        }

        return $clean_skills_array;
    }

    // Example list of Skills
    public function example_skills_list() {
        // Set a limit
        $limit = '&limit=50';
        $apitoken = app('open_api_access_key');

        $skills_array = $this->get_skills($apitoken, $limit);

        // Clean it and get a new array
        $example_skills = $this->clean_skills($skills_array);

        // Set up for Datatables JS
        $example_skills = json_encode($example_skills);
        // Convert back to PHP with each being an array
        $example_skills = json_decode($example_skills, true);

        $example_skills_array = array();

        foreach ($example_skills as $example_skill) {
            $values = array_values($example_skill);
            array_push($example_skills_array, $values);
        }

        return $example_skills_array;
    }

    // Import Types Function --- fired when importing/ updating Skills
    public function import_types($types) {
        // Remove existing entries in database
        \DB::table('skill_types')->truncate();

        foreach($types as $type) {
            $type_array = array(
              'id' => $type->id,
              'name' => $type->name,
              'description' => $type->description
            );
            // Import to DB
            \DB::table('skill_types')->insert($type_array);
        }

    }

    // Initial Display of Skills page
    public function skills_show() {

        // Set api token for Open API
        $apitoken = app('open_api_access_key');

        // Skills Version Details
        $skills_version_info = $this->skills_version_info($apitoken);

        // Example Listing of Skills Structure
        $example_skills = $this->example_skills_list();

        $props = [
            'apitoken' => $apitoken,
            'skills_version_info' => $skills_version_info,
            'skills_data_tables' => $example_skills,
            'skills_current_version' => global_current_skills_version()
        ];

        return Inertia::render('Settings/Skills', $props);
    }

    // Import Skills to DB
    public function skills_import(Skills $skills) {
        // Set api token for Open API
        $apitoken = app('open_api_access_key');

        // Setup the import values
        // Skills Version Details
        $skills_version_info = $this->skills_version_info($apitoken);

        // Get all available types to cycle through
        $types = $skills_version_info->types;

        // Limit
        $limit = '';

        // Array to hold all skills
        $skills_array = $this->get_skills($apitoken, $limit);

        // Clean it up prior to importing
        $skills = $this->clean_skills($skills_array);

        /******
        TO DO: Need to fix the IMPORT */
        // Remove existing entries in database
        //Skills::truncate();

        // Import to DB
        /*foreach($skills as $skill) {
            Skills::insert($skill);
        }*/

        // Import the TYPES
        $this->import_types($types);

        // Update version in settings table
        $skill_current_version = Settings::where('option_name', 'skills_version_current')->first();
        $skill_current_version->updated_at = now();
        $skill_current_version->option_value = $skills_version_info->version;
        $skill_current_version->save();

        // If all good,then redirect back and success!
        return Redirect::back()->with('success', 'Hot diggity! Skills imported successfully!');
    }

    /**********************
    LOT Occupation Updater
    **********************/
    // Function to get versions of LOT Occupations
    public function get_lot_occupations_versions($apitoken) {
        $curl = curl_init();

        curl_setopt_array($curl, [
          CURLOPT_URL => "https://classification.emsicloud.com/taxonomies/lot/versions",
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
          return $err;
        } else {
          return $response;
        }
    }

    // Function to get LOT Occupation IDs
    // $level 2 is for LOT IDs, level 3 is for Specialized Occupations
    public function get_lot_specialized_occupations($apitoken, $last_lot_specialized_occupation_id, $level, $version) {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "https://classification.emsicloud.com/taxonomies/lot/versions/$version/concepts?fields=name,id,levelName,level,descriptionUs,parentId&limit=1000&filter=level:$level&after=$last_lot_specialized_occupation_id",
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
            return $err;
        } else {

            // Convert to array
            $response_array = json_decode($response);

            // Return the array
            return $response_array->data;
        }
    }


    // Function to loop through and get all LOT IDs. Used for both specialized and not
    // This will loop through until the amount returned is not equal to 1000.
    // This loops through level 2 and 3
    public function run_lot_specialized_occupations() {

        // Array holding all of the LOT IDs
        $lot_specialized_occupations_array = array();

        // API Key for Classifications
        $apitoken = app('classifications_access_key');

        // Version -- latest
        $all_versions = $this->get_lot_occupations_versions($apitoken);
        $all_versions = json_decode($all_versions);
        $version = $all_versions->data[0];

        // Get both levels combined
        $levels = array("0","1","2","3");

        foreach($levels as $level) {

            // If we go beyond 1000 returned need to know where we left off to get next batch
            $last_lot_specialized_occupation_id = '';

            $counter = '';
            // Loop through until we a number less than 1000
            do {

                // Get the LOT IDs
                $lots_array = $this->get_lot_specialized_occupations($apitoken, $last_lot_specialized_occupation_id, $level, $version);

                // Push the values our array that holds them all
                foreach($lots_array as $lot_array) {
                    array_push($lot_specialized_occupations_array, $lot_array);
                }

                // Update the $counter so we know when to stop the loop
                $counter = count($lots_array);

                // Push the last ID so we can get new IDs where we left off
                $last_lot_specialized_occupation_id = end($lots_array)->id;

            } while ($counter == 1000);
        }


        return $lot_specialized_occupations_array;

    }

    // Vues
    public function lot_occupations_show() {
        // API Key for Classifications
        $apitoken = app('classifications_access_key');

        // Version -- latest
        $all_versions = $this->get_lot_occupations_versions($apitoken);
        $all_versions = json_decode($all_versions);
        $version = $all_versions->data[0];

        // Array of all LOT Occupations
        // We push to this array to get all the occupation codes
        $lot_occupations = $this->run_lot_specialized_occupations();

        // Set up for Datatables JS
        $lot_occupations_json = json_encode($lot_occupations);
        // Convert back to PHP with each being an array
        $lot_occupations_array_array = json_decode($lot_occupations_json, true);

        $lot_occupations_data_tables = array();

        foreach ($lot_occupations_array_array as $result) {
            $values = array_values($result);
            array_push($lot_occupations_data_tables, $values);
        }

        // Set up props
        $props = [
            'apitoken' => $apitoken,
            'lot_occupations' => $lot_occupations,
            'lot_occupations_data_tables' => $lot_occupations_data_tables,
            'lot_occupation_versions' => $this->get_lot_occupations_versions($apitoken),
            'lot_occupation_version_latest' => $version,
            'lot_occupation_current_version' => global_current_lot_occupation_version()
        ];

        return Inertia::render('Settings/LotOccupations', $props);
    }

    public function lot_occupations_import(LotOccupations $lot_occupations)
    {

        // API Token
        $apitoken = app('classifications_access_key');

        // Version -- latest
        $all_versions = $this->get_lot_occupations_versions($apitoken);
        $all_versions = json_decode($all_versions);
        $version = $all_versions->data[0];

        // First delete the DB table lot_occupations
        LotOccupations::truncate();

        // Import new data

        // Array of all LOT Occupations
        $lot_occupations = $this->run_lot_specialized_occupations();

        // Foreach bc the original is not in a format to insert directly
        foreach ($lot_occupations as $lot_occupation) {
            $name = $lot_occupation->name;
            $lot_id = $lot_occupation->id;
            $level = $lot_occupation->level;
            $level_name = $lot_occupation->levelName;
            $parent_id = $lot_occupation->parentId;
            $description_us = $lot_occupation->descriptionUs;

            // We set the dimension manually based on the level. This is for use with other APIs in the Lightcast family
            if($level == 2) {
                $dimension = 'lotocc';
            } else if($level == 3) {
                $dimension = 'lotspecocc';
            } else {
                $dimension = '';
            }

            $insert_occupation = ['name'=>$name,'lot_id'=>$lot_id,'level'=>$level,'level_name'=>$level_name, 'dimension'=>$dimension, 'description_us'=>$description_us, 'parent_id'=>$parent_id];

            // Insert to DB
            LotOccupations::insert($insert_occupation);

        }

        // Update the Version in settings table
        $lot_current_version = Settings::where('option_name', 'lot_occupation_version_current')->first();
        $lot_current_version->updated_at = now();
        $lot_current_version->option_value = $version;
        $lot_current_version->save();

        // If error

        // If all good,then redirect back and success!
        return Redirect::back()->with('success', 'LOT Occupations imported successfully!');
    }
}
