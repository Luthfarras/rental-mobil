<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Pelanggan;
use Illuminate\Support\Facades\Validator;
use JWTAuth;
use Auth;
use Tymon\JWTAuth\Exceptions\JWTException;


class PelangganController extends Controller
{
  public function store(Request $req){
    if(Auth::user()->level=="admin"){
    $validator = Validator::make($req->all(),
    [
      'nama' => 'required',
      'alamat' => 'required',
      'telp' => 'required'
    ]);
    if($validator->fails()){
      return Response()->json($validator->errors());
    }
    $simpan = Pelanggan::create([
      'nama' => $req->nama,
      'alamat' => $req->alamat,
      'telp' => $req->telp
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
      'nama' => 'required',
      'alamat' => 'required',
      'telp' => 'required'
    ]);
    if($validator->fails()){
      return Response()->json($validator->errors());
    }
    $ubah = Pelanggan::where('id', $id)->update([
      'nama' => $req->nama,
      'alamat' => $req->alamat,
      'telp' => $req->telp
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
    $pelanggan=Pelanggan::get();
    $count=$pelanggan->count();
    $arr_data=array();
    foreach ($pelanggan as $p){
      $arr_data[]=array(
        'nama' => $p->nama,
        'alamat' => $p->alamat,
        'telp' => $p->telp
      );
    }
    $status=1;
    return Response()->json(compact('status','count','arr_data'));
  } else {
    return Response()->json(['status' => 0]);
  }
  }

  public function destroy($id){
    $hapus = Pelanggan::where('id', $id)->delete();
    $status=1;
    $message="Hapus Data berhasil";
    if($hapus){
      return Response()->json(compact('status','message'));
    } else {
      return Response()->json(['status' => 0]);
    }
  }
}
