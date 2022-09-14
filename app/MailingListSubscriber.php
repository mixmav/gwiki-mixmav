<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MailingListSubscriber extends Model
{
    public function fingerprint(){
        return $this->belongsTo('App\Fingerprint');
    }
}
