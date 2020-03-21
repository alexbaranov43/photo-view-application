<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    //
    protected $fillable = [
        'user_id', 'image_url', 'photo_id', 'height', 'width'
    ];

    public function User()
    {
        return $this->belongsTo('App\User');
    }
}
