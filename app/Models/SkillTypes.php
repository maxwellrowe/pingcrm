<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SkillTypes extends Model
{
    protected $table = 'skill_types';

    protected $fillable = [
        'id',
        'name',
        'description'
    ];

    protected $primaryKey = 'id';
}
