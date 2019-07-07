<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Maximum extends Model
{
    protected $table = 'maximums';
    protected $fillable = ['year'];
}
