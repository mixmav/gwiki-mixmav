<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    public function fingerprint(){
        return $this->belongsTo('App\Fingerprint');
    }
}
