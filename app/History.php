<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'history';

    /**
     * Get user that owns the history.
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

}
