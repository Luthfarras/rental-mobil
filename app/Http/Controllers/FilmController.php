<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Film;
use Illuminate\Support\Facades\Validator;
use JWTAuth;
use Auth;
use Tymon\JWTAuth\Exceptions\JWTException;


class FilmController extends Controller
{
    public function index(){
      if(Auth::user()->level=="admin"){
        $film=Film::get();
        return response()->json($film);
      } else {
        return response()->json(['status'=>'anda bukan admin']);
      }
    }

    public function store(Request $req){
      if(Auth::user()->level=="admin"){
      $validator = Validator::make($req->all(),
      [
        'nama_film' => 'required',
        'genre' => 'required',
        'deskripsi' => 'required'
      ]);
      if($validator->fails()){
        return Response()->json($validator->errors());
      }
      $simpan = Film::create([
        'nama_film' => $req->nama_film,
        'genre' => $req->genre,
        'deskripsi' => $req->deskripsi
      ]);
      $status=1;
      $message="Berhasil Menambah Film";
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
        'nama_film' => 'required',
        'genre' => 'required',
        'deskripsi' => 'required'
      ]);
      if($validator->fails()){
        return Response()->json($validator->errors());
      }
      $ubah = Film::where('id', $id)->update([
        'nama_film' => $req->nama_film,
        'genre' => $req->genre,
        'deskripsi' => $req->deskripsi
      ]);
      $status=1;
      $message="Berhasil Mengupdate Film";
      if($ubah){
        return Response()->json(compact('status','message'));
      } else {
        return Response()->json(['status' => 0]);
      }
    }

    public function tampil(){
      if(Auth::user()->level=="admin"){
      $film=Film::get();
      $count=$film->count();
      $arr_data=array();
      foreach ($film as $film){
        $arr_data[]=array(
          'id' => $film->id,
          'nama_film' => $film->nama_film,
          'genre' => $film->genre,
          'deskripsi' => $film->deskripsi
        );
      }
      $status=1;
      return Response()->json(compact('status','count','arr_data'));
    } else {
      return Response()->json(['status' => 0]);
    }
    }

    public function destroy($id){
      $hapus = Film::where('id', $id)->delete();
      $status=1;
      $message="Berhasil Menghapus Film";
      if($hapus){
        return Response()->json(compact('status','message'));
      } else {
        return Response()->json(['status' => 0]);
      }
    }
}
