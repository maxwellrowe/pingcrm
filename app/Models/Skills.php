<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Skills extends Model
{
    protected $table = 'skills';

    protected $fillable = [
        'id',
        'name',
        'description',
        'type_id',
        'type_name',
        'category_id',
        'category_name',
        'subcategory_id',
        'subcategory_name',
        'isSoftware',
        'isLanguage',
        'infoUrl'
    ];

    protected $primaryKey = 'id';

    public $incrementing = false;
}
