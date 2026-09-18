<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    public $table = "event";
    
    public static $rules = [
        'advertiser_id' => 'required',
        'media_id' => 'required',
        'event_name' => 'required',
        'event_url' => 'required',
        'page_url' => 'required'
    ];
    
    protected $fillable = [
         'advertiser_id', 'media_id', 'event_name', 'event_url', 'page_url'
    ];
}
