<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Penyewa;
use Illuminate\Support\Facades\Validator;
use JWTAuth;
use Auth;
use Tymon\JWTAuth\Exceptions\JWTException;


class PenyewaController extends Controller
{
  public function store(Request $req){
    if(Auth::user()->level=="admin"){
    $validator = Validator::make($req->all(),
    [
      'nama_penyewa' => 'required',
      'alamat' => 'required',
      'telp' => 'required',
      'foto_ktp' => 'required'
    ]);
    if($validator->fails()){
      return Response()->json($validator->errors());
    }
    $simpan = Penyewa::create([
      'nama_penyewa' => $req->nama_penyewa,
      'alamat' => $req->alamat,
      'telp' => $req->telp,
      'foto_ktp' => $req->foto_ktp
    ]);
    $status=1;
    $message="Tambah Data Berhasil";
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
      'nama_penyewa' => 'required',
      'alamat' => 'required',
      'telp' => 'required',
      'foto_ktp' => 'required'
    ]);
    if($validator->fails()){
      return Response()->json($validator->errors());
    }
    $ubah = Penyewa::where('id', $id)->update([
      'nama_penyewa' => $req->nama_penyewa,
      'alamat' => $req->alamat,
      'telp' => $req->telp,
      'foto_ktp' => $req->foto_ktp
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
    $penyewa=Penyewa::get();
    $count=$penyewa->count();
    $arr_data=array();
    foreach ($penyewa as $p){
      $arr_data[]=array(
        'nama_penyewa' => $p->nama_penyewa,
        'alamat' => $p->alamat,
        'telp' => $p->telp,
        'foto_ktp' => $p->foto_ktp
      );
    }
    $status=1;
    return Response()->json(compact('status','count','arr_data'));
  } else {
    return Response()->json(['status' => 0]);
  }
  }

  public function destroy($id){
    $hapus = Penyewa::where('id', $id)->delete();
    $status=1;
    $message="Hapus Data berhasil";
    if($hapus){
      return Response()->json(compact('status','message'));
    } else {
      return Response()->json(['status' => 0]);
    }
  }
}
