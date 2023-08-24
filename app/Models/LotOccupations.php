<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// Based on the Classifications API from Lightcast
class LotOccupations extends Model
{
    protected $table = 'lot_occupations';

    protected $fillable = [
        'lot_id',
        'name',
        'level',
        'level_name',
        'dimension',
        'descriptionUs',
        'parentId'
    ];

    protected $primaryKey = 'lot_id';
}
