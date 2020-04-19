<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Penyewa extends Model
{
  protected $table="penyewa";
  protected $primaryKey="id";
  protected $fillable = [
    'nama_penyewa', 'alamat', 'telp', 'foto_ktp'
  ];
}
