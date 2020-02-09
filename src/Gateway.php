<?php

namespace Zibal;

use Illuminate\Database\Eloquent\Model;

class Gateway extends Model
{
    protected  $fillable = [
        'track_id', 'amount'
    ];

    public function order()
    {
        return $this->hasOne('App/Order', 'id', 'order_id');
    }
}
