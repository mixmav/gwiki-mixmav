<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Fingerprint extends Model
{
    public function logs(){
        return $this->hasMany('App\Log');
    }

    public function page_views(){
        return $this->hasMany('App\PageView');
    }

    public function mailing_list_subscribers(){
        return $this->hasMany('App\MailingListSubscriber');
    }
}
