<?php

namespace Zibal;

use Illuminate\Database\Eloquent\Model;

class Gateway extends Model
{
    protected  $fillable = [
        'track_id', 'amount'
    ];
}
