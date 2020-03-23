<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Film extends Model
{
    protected $table="film";
    protected $primaryKey="id";
    protected $fillable = [
      'nama_film', 'genre', 'deskripsi'
    ];

    public function ftayang(){
      return $this->hasMany('App\FTayang','id');
    }

}
