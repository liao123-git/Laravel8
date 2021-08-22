<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends BaseModel
{
    use BooleanSoftDeletes;

    protected $guarded = [];

    protected $hidden = [
        'deleted'
    ];

    public $timestamps = false;
}
