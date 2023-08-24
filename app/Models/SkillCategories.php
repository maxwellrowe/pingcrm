<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SkillCategories extends Model
{
    protected $table = 'skill_categories';

    protected $fillable = [
        'id',
        'name',
        'level',
        'levelName',
        'parentId'
    ];

    protected $primaryKey = 'id';

    public $incrementing = false;
}
