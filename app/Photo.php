<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    //
    protected $fillable = [
        'user_id', 'image_url'
    ];

    public function User()
    {
        return $this->belongsTo('App\User');
    }
}
