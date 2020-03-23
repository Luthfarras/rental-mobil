<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Jenis;
use App\Pelanggan;
use App\Petugas;
use App\Detail;
use App\Transaksi;
use Illuminate\Support\Facades\Validator;
use JWTAuth;
use Auth;
use DB;
use Tymon\JWTAuth\Exceptions\JWTException;


class DetailController extends Controller
{
  public function store(Request $req){
    if(Auth::user()->level=="petugas"){
      $validator = Validator::make($req->all(),
      [
        'id_trans' => 'required',
        'id_jenis' => 'required',
        'qty' => 'required'
      ]);
      if($validator->fails()){
        return Response()->json($validator->errors());
      }

      $harga=DB::table('jenis_cuci')->where('id', $req->id_jenis)->first();
      $subtotal = ($harga->harga_perkilo * $req->qty);

      $simpan = Detail::create([
        'id_trans' => $req->id_trans,
        'id_jenis' => $req->id_jenis,
        'subtotal' => $subtotal,
        'qty' => $req->qty
      ]);
      $status=1;
      $message="Berhasil Menambah Detail";
      if($simpan){
        return Response()->json(compact('status','message'));
      } else {
        return Response()->json(['status' => 0]);
      }
    } else {
      return response()->json(['status'=>'anda bukan petugas']);
    }
  }

  public function update($id, Request $req){
    $validator = Validator::make($req->all(),
    [
      'id_trans' => 'required',
      'id_jenis' => 'required',
      'qty' => 'required'
    ]);
    if($validator->fails()){
      return Response()->json($validator->errors());
    }

    $harga=DB::table('jenis_cuci')->where('id', $req->id_jenis)->first();
    $subtotal = ($harga->harga_perkilo * $req->qty);

    $ubah = Detail::where('id', $id)->update([
      'id_trans' => $req->id_trans,
      'id_jenis' => $req->id_jenis,
      'subtotal' => $subtotal,
      'qty' => $req->qty
    ]);
    $status=1;
    $message="Berhasil Mengubah Detail";
    if($ubah){
      return Response()->json(compact('status','message'));
    } else {
      return Response()->json(['status' => 0]);
    }

  }

  // public function tampil(){
  //   if(Auth::user()->level=="petugas"){
  //     $detail=detail::orderBy('waktu', 'ASC')
  //     ->join('film_tayang', 'film_tayang.id', 'detail.id_film_tayang')
  //     ->join('film', 'film.id', 'film_tayang.id_film')
  //     ->join('studio', 'studio.id', 'film_tayang.id_studio')
  //     ->select('detail.id', 'detail.tanggal', 'film_tayang.waktu', 'film.nama_film', 'film.deskripsi', 'studio.nama_studio')
  //     ->get();
  //     $count=$detail->count();
  //     return Response()->json(compact('detail','count'));
  //     } else {
  //       return response()->json(['status'=>'anda bukan petugas']);
  //     }
  //   }

  public function destroy($id){
    $hapus = Detail::where('id', $id)->delete();
    $status=1;
    $message="Berhasil Menghapus Detail";
    if($hapus){
      return Response()->json(compact('status','message'));
    } else {
      return Response()->json(['status' => 0]);
    }
  }
}
