<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Jenis extends Model
{
  protected $table="jenis_cuci";
  protected $primaryKey="id";
  protected $fillable = [
    'nama_jenis', 'harga_perkilo'
  ];
}
