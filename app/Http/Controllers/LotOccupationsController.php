<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

class LotOccupationsController extends Controller
{
    public function index()
    {
        // Prep for Datatables output
        // Remove keys and include LOT ID, Name, Level, Level Name
        $og_lot_occupations = global_lot_occupations_array();

        // Our updated array
        $lot_occupations = array();

        // Foreach to push to our array above
        foreach ($og_lot_occupations as $og_lot_occupation) {
            $lot_id = $og_lot_occupation->lot_id;
            $name = $og_lot_occupation->name;
            $level = $og_lot_occupation->level;
            $level_name = $og_lot_occupation->level_name;
            $dimension = $og_lot_occupation->dimension;
            $parent_id = $og_lot_occupation->parent_id;
            $description_us = $og_lot_occupation->description_us;

            array_push($lot_occupations, array($lot_id, $name, $dimension, $level, $level_name, $parent_id, $description_us));
        }

        $props = [
            'lot_occupations' => $lot_occupations,
            'lot_occupation_current_version' => global_current_lot_occupation_version()
        ];

        return Inertia::render('LightcastAPI/LotOccupations', $props);
    }
}
