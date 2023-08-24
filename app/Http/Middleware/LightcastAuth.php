<?php

namespace App\Http\Middleware;

use App\Models\Settings;
use Closure;
use Illuminate\Http\Request;

class LightcastAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Handle each of the API Keys in this file -->

        // Var Used for each
        // Set to 59 minutes bc 1 hour sometimes does not update key in DB quick enough
        $oneHourAgo = now()->subMinutes(59);
        $clientId = 'UnivofcaliforniaSD';
        $clientSecret = 'KBtdkPpt';

        /**********************
        Job Postings
        **********************/
        $setting = Settings::where('option_name', 'job_postings_access_key')->first();

        if($setting) {
            $accessKeyValue = $setting->option_value;
            $accessKeyUpdated = $setting->updated_at;

            // Check if it's been an hour and if so update the key in the DB
            if ($accessKeyUpdated->lessThan($oneHourAgo)) {
                // Get the API Token from Lightcast
                $curl = curl_init();

                curl_setopt_array($curl, [
                  CURLOPT_URL => "https://auth.emsicloud.com/connect/token",
                  CURLOPT_RETURNTRANSFER => true,
                  CURLOPT_ENCODING => "",
                  CURLOPT_MAXREDIRS => 10,
                  CURLOPT_TIMEOUT => 30,
                  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                  CURLOPT_CUSTOMREQUEST => "POST",
                  CURLOPT_POSTFIELDS => "client_id=$clientId&client_secret=$clientSecret&grant_type=client_credentials&scope=postings:us",
                  CURLOPT_HTTPHEADER => [
                    "content-type: application/x-www-form-urlencoded"
                  ],
                ]);

                $response = curl_exec($curl);
                $err = curl_error($curl);

                curl_close($curl);

                if ($err) {
                  //return false;
                } else {
                    $response_array = json_decode($response);
                    $accessToken = $response_array->access_token;

                    // Update the database
                    $setting->updated_at = now();
                    $setting->option_value = $accessToken;
                    $setting->save();
                }

            }

            // Set the value across the app
            app()->instance('job-postings-access-key-value', $accessKeyValue);
        }

        /**********************
        Projected Occupation Growth Access POG
        **********************/
        $settingPOG = Settings::where('option_name', 'projected_occupation_growth_access_key')->first();

        if($settingPOG) {
            $accessKeyValuePOG = $settingPOG->option_value;
            $accessKeyUpdatedPOG = $settingPOG->updated_at;

            // Check if it's been an hour and if so update the key in the DB
            if ($accessKeyUpdatedPOG->lessThan($oneHourAgo)) {
                // Get the API Token from Lightcast
                $curl = curl_init();

                curl_setopt_array($curl, [
                  CURLOPT_URL => "https://auth.emsicloud.com/connect/token",
                  CURLOPT_RETURNTRANSFER => true,
                  CURLOPT_ENCODING => "",
                  CURLOPT_MAXREDIRS => 10,
                  CURLOPT_TIMEOUT => 30,
                  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                  CURLOPT_CUSTOMREQUEST => "POST",
                  CURLOPT_POSTFIELDS => "client_id=$clientId&client_secret=$clientSecret&grant_type=client_credentials&scope=projected-occupation-growth",
                  CURLOPT_HTTPHEADER => [
                    "content-type: application/x-www-form-urlencoded"
                  ],
                ]);

                $response = curl_exec($curl);
                $err = curl_error($curl);

                curl_close($curl);

                if ($err) {
                  //return false;
                } else {
                    $response_arrayPOG = json_decode($response);
                    $accessTokenPOG = $response_arrayPOG->access_token;

                    // Update the database
                    $settingPOG->updated_at = now();
                    $settingPOG->option_value = $accessTokenPOG;
                    $settingPOG->save();
                }

            }

            // Set the value across the app
            app()->instance('projected_occupation_growth_access_key', $accessKeyValuePOG);
        }

        /**********************
        Classifications
        **********************/
        $settingClassifications = Settings::where('option_name', 'classifications_access_key')->first();

        if($settingClassifications) {
            $accessKeyValueClassifications = $settingClassifications->option_value;
            $accessKeyUpdatedClassifications = $settingClassifications->updated_at;

            // Check if it's been an hour and if so update the key in the DB
            if ($accessKeyUpdatedClassifications->lessThan($oneHourAgo)) {
                // Get the API Token from Lightcast
                $curl = curl_init();

                curl_setopt_array($curl, [
                    CURLOPT_URL => "https://auth.emsicloud.com/connect/token",
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "POST",
                    CURLOPT_POSTFIELDS => "client_id=$clientId&client_secret=$clientSecret&grant_type=client_credentials&scope=classification_api",
                    CURLOPT_HTTPHEADER => [
                    "content-type: application/x-www-form-urlencoded"
                    ],
                ]);

                $response = curl_exec($curl);
                $err = curl_error($curl);

                curl_close($curl);

                if ($err) {
                    //return false;
                } else {
                    $response_arrayClassifications = json_decode($response);
                    $accessTokenClassifications = $response_arrayClassifications->access_token;

                    // Update the database
                    $settingClassifications->updated_at = now();
                    $settingClassifications->option_value = $accessTokenClassifications;
                    $settingClassifications->save();
                }
            }

            // Set the value across the app
            app()->instance('classifications_access_key', $accessKeyValueClassifications);
        }

        /**********************
        Open API Access Key
        **********************/
        $settingOpenApi = Settings::where('option_name', 'open_api_access_key')->first();

        if($settingOpenApi) {
            $accessKeyValueOpenApi = $settingOpenApi->option_value;
            $accessKeyUpdatedOpenApi = $settingOpenApi->updated_at;

            // Check if it's been an hour and if so update the key in the DB
            if ($accessKeyUpdatedOpenApi->lessThan($oneHourAgo)) {
                    // Get the API Token from Lightcast
                    $curl = curl_init();

                    curl_setopt_array($curl, [
                            CURLOPT_URL => "https://auth.emsicloud.com/connect/token",
                            CURLOPT_RETURNTRANSFER => true,
                            CURLOPT_ENCODING => "",
                            CURLOPT_MAXREDIRS => 10,
                            CURLOPT_TIMEOUT => 30,
                            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                            CURLOPT_CUSTOMREQUEST => "POST",
                            CURLOPT_POSTFIELDS => "client_id=$clientId&client_secret=$clientSecret&grant_type=client_credentials&scope=emsi_open",
                            CURLOPT_HTTPHEADER => [
                            "content-type: application/x-www-form-urlencoded"
                            ],
                    ]);

                    $response = curl_exec($curl);
                    $err = curl_error($curl);

                    curl_close($curl);

                    if ($err) {
                            //return false;
                    } else {
                            $response_arrayOpenApi = json_decode($response);
                            $accessTokenOpenApi = $response_arrayOpenApi->access_token;

                            // Update the database
                            $settingOpenApi->updated_at = now();
                            $settingOpenApi->option_value = $accessTokenOpenApi;
                            $settingOpenApi->save();
                    }
            }

            // Set the value across the app
            app()->instance('open_api_access_key', $accessKeyValueOpenApi);
        }

        /**********************
        Projected Skill Growth API Key
        **********************/
        $settingSkillGrowth = Settings::where('option_name', 'skill_growth_access_key')->first();

        if($settingSkillGrowth) {
            $accessKeyValueSkillGrowth = $settingSkillGrowth->option_value;
            $accessKeyUpdatedSkillGrowth = $settingSkillGrowth->updated_at;

            // Check if it's been an hour and if so update the key in the DB
            if ($accessKeyUpdatedSkillGrowth->lessThan($oneHourAgo)) {
                // Get the API Token from Lightcast
                $curl = curl_init();

                curl_setopt_array($curl, [
                    CURLOPT_URL => "https://auth.emsicloud.com/connect/token",
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "POST",
                    CURLOPT_POSTFIELDS => "client_id=$clientId&client_secret=$clientSecret&grant_type=client_credentials&scope=projected-skill-growth",
                    CURLOPT_HTTPHEADER => [
                    "content-type: application/x-www-form-urlencoded"
                    ],
                ]);

                $response = curl_exec($curl);
                $err = curl_error($curl);

                curl_close($curl);

                if ($err) {
                                //return false;
                } else {
                    $response_arraySkillGrowth = json_decode($response);
                    $accessTokenSkillGrowth = $response_arraySkillGrowth->access_token;

                    // Update the database
                    $settingSkillGrowth->updated_at = now();
                    $settingSkillGrowth->option_value = $accessTokenSkillGrowth;
                    $settingSkillGrowth->save();
                }
            }

            // Set the value across the app
            app()->instance('skill_growth_access_key', $accessKeyValueSkillGrowth);
        }


        // After all updated
        return $next($request);
    }
}
