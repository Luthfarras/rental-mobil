<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Studio;
use Illuminate\Support\Facades\Validator;
use JWTAuth;
use Auth;
use Tymon\JWTAuth\Exceptions\JWTException;

class StudioController extends Controller
{
  public function index(){
    if(Auth::user()->level=="admin"){
      $studio=Studio::get();
      return response()->json($studio);
    } else {
      return response()->json(['status'=>'anda bukan admin']);
    }
  }

  public function store(Request $req){
    if(Auth::user()->level=="admin"){
    $validator = Validator::make($req->all(),
    [
      'nama_studio' => 'required'
    ]);
    if($validator->fails()){
      return Response()->json($validator->errors());
    }
    $simpan = Studio::create([
      'nama_studio' => $req->nama_studio
    ]);
    $status=1;
    $message="Berhasil Menambah Studio";
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
      'nama_studio' => 'required'
    ]);
    if($validator->fails()){
      return Response()->json($validator->errors());
    }
    $ubah = Studio::where('id', $id)->update([
      'nama_studio' => $req->nama_studio
    ]);
    $status=1;
    $message="Berhasil Mengupdate Studio";
    if($ubah){
      return Response()->json(compact('status','message'));
    } else {
      return Response()->json(['status' => 0]);
    }
  }

  public function tampil(){
    if(Auth::user()->level=="admin"){
    $studio=Studio::get();
    $count=$studio->count();
    $arr_data=array();
    foreach ($studio as $studio){
      $arr_data[]=array(
        'id' => $studio->id,
        'nama_studio' => $studio->nama_studio
      );
    }
    $status=1;
    return Response()->json(compact('status','count','arr_data'));
  } else {
    return response()->json(['status'=>'anda bukan admin']);
  }
  }

  public function destroy($id){
    $hapus = Studio::where('id', $id)->delete();
    $status=1;
    $message="Berhasil Menghapus Studio";
    if($hapus){
      return Response()->json(compact('status','message'));
    } else {
      return Response()->json(['status' => 0]);
    }
  }
}
