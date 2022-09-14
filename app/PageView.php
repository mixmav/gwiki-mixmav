<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PageView extends Model
{
    public function fingerprint(){
        return $this->belongsTo('App\Fingerprint');
    }
}
