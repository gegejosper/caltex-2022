<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pump extends Model
{
    //
    public function gastype()
    {
        return $this->belongsTo('App\Gastype','gasid','id');
    }

    public function pumplog(){
        return $this->hasOne('App\Pumplog', 'pumpid', 'id')->latest();
        //return $this->hasOne('App\Pumplog', 'pumpid', 'id')->where('status','=', 'FINAL')->latest();
    }
    public function pumploglastfinal(){
        //return $this->hasOne('App\Pumplog', 'pumpid', 'id')->latest();
        return $this->hasOne('App\Pumplog', 'pumpid', 'id')->where('status','=', 'FINAL')->latest();
    }
}
