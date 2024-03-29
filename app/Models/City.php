<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends BaseModel
{
    use HasFactory;

    protected $table = 'city';

    protected $fillable = ['name'];
}
