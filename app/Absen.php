<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Absen extends Model
{
    //
    protected $table = 'absen';
    protected $fillable = [
        'out_ijin', 'kt_ijin', 'jam_kerja'
    ];
    public function user ()
    {
        return $this->belongsTo('App\User');
    }
}
