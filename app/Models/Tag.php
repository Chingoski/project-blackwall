<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends BaseModel
{
    use HasFactory;

    protected $table = 'tag';

    protected $fillable = [
        'name',
        'slug',
    ];
}
