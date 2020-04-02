<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShortURL extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'short_urls';

    /**
     * Encode URL id
     *
     * @var string
     */
    public function encode()
    {
        $base = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $i = $this->id % 62;
        $encoded = $base[$i];
        $q = floor($this->id / 62);

        while ($q) {
            $i = $q % 62;
            $q = floor($q / 62);
            $encoded = $base[$i].$encoded;
        }

        return $encoded;
    }
}
