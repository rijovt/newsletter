<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subscriber extends Model
{
    protected $fillable = [
        'name', 'email', 'user_id'
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
