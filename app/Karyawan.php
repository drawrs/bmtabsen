<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Karyawan extends Model
{
    //
    protected $table = 'user_detail';
    protected $fillable = [
        'user_id', 'jabatan_id', 'ktp', 'nama', 'jk', 'tgl', 'alamat'
    ];
    public function jabatan ()
    {
        return $this->belongsTo('App\Jabatan');
    }
    
}
