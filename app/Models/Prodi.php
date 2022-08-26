<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prodi extends Model
{
    protected $fillable = [
        'jenjang', 'prodi', 'slug', 'image'
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function emoduls()
    {
        return $this->hasMany(Emodul::class)->with('matakuliahs');
    }

    public function matakuliahs()
    {
        return $this->hasMany(Matakuliah::class);
    }
}