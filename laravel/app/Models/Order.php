<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends BaseModel
{
    protected $guarded = [];

    protected $hidden = [
    ];

    public $timestamps = false;
}
