<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Emodul extends Model
{
    protected $fillable = [
        'title', 'deskripsi', 'dosen', 'prodi_id', 'matakuliah_id'
    ];

    // public function users()
    // {
    //     return $this->hasMany(User::class);
    // }

    public function prodis()
    {
        return $this->belongsTo(Prodi::class, 'prodi_id');
    }

    public function matakuliahs()
    {
        return $this->belongsTo(Matakuliah::class, 'matakuliah_id');
    }

    public function modules()
    {
        return $this->hasMany(Module::class);
    }
}