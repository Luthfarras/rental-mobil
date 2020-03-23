<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FTayang extends Model
{
  protected $table="film_tayang";
  protected $primaryKey="id";
  protected $fillable = [
    'id_film', 'id_studio', 'waktu'
  ];

  public function film(){
    return $this->belongsTo('App/Film','id_film');
  }
  public function studio(){
    return $this->belongsTo('App/Studio','id_studio');
  }
  public function tiket(){
  return $this->hasMany('App\Tiket','id');
  }

}
