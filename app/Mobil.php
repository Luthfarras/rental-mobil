<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Mobil extends Model
{
  protected $table="data_mobil";
  protected $primaryKey="id";
  protected $fillable = [
    'id_jenis', 'nama_mobil', 'merk', 'plat', 'kondisi', 'keterangan'
  ];

}
