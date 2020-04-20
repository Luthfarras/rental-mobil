<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Jenis;
use App\Mobil;
use Illuminate\Support\Facades\Validator;
use JWTAuth;
use Auth;
use Tymon\JWTAuth\Exceptions\JWTException;

class MobilController extends Controller
{
  public function store(Request $req){
    if(Auth::user()->level=="admin"){
    $validator = Validator::make($req->all(),
    [
      'id_jenis' => 'required',
      'nama_mobil' => 'required',
      'merk' => 'required',
      'plat' => 'required',
      'kondisi' => 'required',
      'keterangan' => 'required'
    ]);
    if($validator->fails()){
      return Response()->json($validator->errors());
    }
    $simpan = Mobil::create([
      'id_jenis' => $req->id_jenis,
      'nama_mobil' => $req->nama_mobil,
      'merk' => $req->merk,
      'plat' => $req->plat,
      'kondisi' => $req->kondisi,
      'keterangan' => $req->keterangan
    ]);
    $status=1;
    $message="Berhasil Menambah Data";
    if($simpan){
      return Response()->json(compact('status','message'));
    } else {
        return Response()->json(['status' => 0]);
    }
  } else {
      return response()->json(['status'=>'anda bukan admin']);
  }
  }

  public function update($id, Request $req){
    $validator = Validator::make($req->all(),
    [
      'id_jenis' => 'required',
      'nama_mobil' => 'required',
      'merk' => 'required',
      'plat' => 'required',
      'kondisi' => 'required',
      'keterangan' => 'required'
    ]);
    if($validator->fails()){
      return Response()->json($validator->errors());
    }
    $ubah = Mobil::where('id', $id)->update([
      'id_jenis' => $req->id_jenis,
      'nama_mobil' => $req->nama_mobil,
      'merk' => $req->merk,
      'plat' => $req->plat,
      'kondisi' => $req->kondisi,
      'keterangan' => $req->keterangan
    ]);
    $status=1;
    $message="Ubah Data Berhasil";
    if($ubah){
      return Response()->json(compact('status','message'));
    } else {
      return Response()->json(['status' => 0]);
    }
  }

  public function tampil(){
    if(Auth::user()->level=="admin"){
    $mobil=Mobil::get();
    $count=$mobil->count();
    $arr_data=array();
    foreach ($mobil as $m){
      $arr_data[]=array(
        'id' => $m->id,
        'id_jenis' => $m->id_jenis,
        'nama_mobil' => $m->nama_mobil,
        'merk' => $m->merk,
        'plat' => $m->plat,
        'kondisi' => $m->kondisi,
        'keterangan' => $m->keterangan
      );
    }
    $status=1;
    return Response()->json(compact('status','count','arr_data'));
  } else {
    return Response()->json(['status' => 0]);
  }
  }

  public function destroy($id){
    $hapus =  Mobil::where('id', $id)->delete();
    $status=1;
    $message="Hapus Data berhasil";
    if($hapus){
      return Response()->json(compact('status','message'));
    } else {
      return Response()->json(['status' => 0]);
    }
  }
}
