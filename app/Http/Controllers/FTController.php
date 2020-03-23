<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\FTayang;
use App\Film;
use App\Studio;
use Illuminate\Support\Facades\Validator;
use JWTAuth;
use Auth;
use DB;
use Tymon\JWTAuth\Exceptions\JWTException;

class FTController extends Controller
{
  public function index(){
    if(Auth::user()->level=="admin"){
      $dt_ft=DB::table('film_tayang')
      ->join('film', 'film.id', 'film_tayang.id_film')
      ->join('studio', 'studio.id', 'film_tayang.id_studio')
      ->select('film_tayang.id', 'film_tayang.id_film', 'film_tayang.id_studio', 'film.nama_film', 'studio.nama_studio', 'film_tayang.waktu')
      ->get();

      $data=array();
      foreach ($dt_ft as $dt_ft) {
        $data[]=array(
          'id' => $dt_ft->id,
          'id_film' => $dt_ft->id_film,
          'id_studio' => $dt_ft->id_studio,
          'nama_film' => $dt_ft->nama_film,
          'nama_studio' => $dt_ft->nama_studio,
          'waktu' => $dt_ft->waktu
        );
      }
      return response()->json(compact('data'));
    } else {
      return response()->json(['status'=>'anda bukan admin']);
    }
  }

  public function store(Request $req){
    if(Auth::user()->level=="admin"){
    $validator = Validator::make($req->all(),
    [
      'id_film' => 'required',
      'id_studio' => 'required',
      'waktu' => 'required'
    ]);
    if($validator->fails()){
      return Response()->json($validator->errors());
    }
    $simpan = FTayang::create([
      'id_film' => $req->id_film,
      'id_studio' => $req->id_studio,
      'waktu' => $req->waktu
    ]);
    $status=1;
    $message="Berhasil Menambah Tayangan";
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
      'id_film' => 'required',
      'id_studio' => 'required',
      'waktu' => 'required'
    ]);
    if($validator->fails()){
      return Response()->json($validator->errors());
    }
    $ubah = FTayang::where('id', $id)->update([
      'id_film' => $req->id_film,
      'id_studio' => $req->id_studio,
      'waktu' => $req->waktu
    ]);
    $status=1;
    $message="Berhasil Mengupdate Tayangan";
    if($ubah){
      return Response()->json(compact('status','message'));
    } else {
      return Response()->json(['status' => 0]);
    }
  }

  public function tampil(){
    if(Auth::user()->level=="admin"){
    $dt_ft=FTayang::orderBy('waktu', 'ASC')
    ->join('film', 'film.id', 'film_tayang.id_film')
    ->join('studio', 'studio.id', 'film_tayang.id_studio')
    ->select('film.nama_film', 'film_tayang.waktu', 'studio.nama_studio')
    ->get();
    $count=$dt_ft->count();
    return Response()->json(compact('dt_ft','count'));
  } else {
    return response()->json(['status'=>'anda bukan admin']);
  }
  }

  public function destroy($id){
    $hapus = FTayang::where('id', $id)->delete();
    $status=1;
    $message="Berhasil Menghapus Tayangan";
    if($hapus){
      return Response()->json(compact('status','message'));
    } else {
      return Response()->json(['status' => 0]);
    }
  }
}
