<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class joinactivities extends Model
{
    use HasFactory;

    public $timestamps = false;
    public $incrementing = false;
    protected $fillables = [
      'matricid', 'activitiestittle',
    ];
    protected $primaryKey = 'matricid';
}

