<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Matakuliah extends Model
{
    protected $fillable = [
        'kode', 'matakuliah', 'slug', 'prodi_id'
    ];

    public function emoduls()
    {
        return $this->hasMany(Emodul::class);
    }
    public function prodis()
    {
        return $this->belongsTo(Prodi::class, 'prodi_id');
    }
}