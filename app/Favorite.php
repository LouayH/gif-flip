<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    /**
     * Get user that owns the favorite.
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
