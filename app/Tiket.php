<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tiket extends Model
{
  protected $table="tiket";
  protected $primaryKey="id";
  protected $fillable = [
    'id_film_tayang', 'id_petugas', 'tanggal', 'harga'
  ];

  public function ftayang(){
    return $this->belongsTo('App/FTayang','id_film_tayang');
  }
  public function petugas(){
    return $this->belongsTo('App/Petugas','id_petugas');
  }

}
