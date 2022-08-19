<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    protected $fillable = [
        'module', 'emodul_id',
    ];

    public function emoduls()
    {
        return $this->belongsTo(Emodul::class);
    }
}