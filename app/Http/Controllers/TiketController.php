<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\FTayang;
use App\Film;
use App\Studio;
use App\Tiket;
use Illuminate\Support\Facades\Validator;
use JWTAuth;
use Auth;
use DB;
use Tymon\JWTAuth\Exceptions\JWTException;

class TiketController extends Controller
{
  public function index(){
    if(Auth::user()->level=="petugas"){
      $tiket=DB::table('tiket')
      ->join('film_tayang', 'film.id', 'film_tayang.id_film')
      ->join('studio', 'studio.id', 'film_tayang.id_studio')
      ->select('film_tayang.id', 'film_tayang.id_film', 'film_tayang.id_studio', 'film.nama_film', 'studio.nama_studio', 'film_tayang.waktu')
      ->get();

      $data=array();
      foreach ($tiket as $dt_ft) {
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
      return response()->json(['status'=>'anda bukan petugas']);
    }
  }

  public function store(Request $req){
    if(Auth::user()->level=="petugas"){
      $validator = Validator::make($req->all(),
      [
        'id_film_tayang' => 'required',
        'id_petugas' => 'required',
        'tanggal' => 'required',
        'harga' => 'required'
      ]);
      if($validator->fails()){
        return Response()->json($validator->errors());
      }
      $simpan = Tiket::create([
        'id_film_tayang' => $req->id_film_tayang,
        'id_petugas' => $req->id_petugas,
        'tanggal' => $req->tanggal,
        'harga' => $req->harga
      ]);
      $status=1;
      $message="Berhasil Membuat Tiket";
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
      'id_film_tayang' => 'required',
      'id_petugas' => 'required',
      'tanggal' => 'required',
      'harga' => 'required'
    ]);
    if($validator->fails()){
      return Response()->json($validator->errors());
    }
    $ubah = Tiket::where('id', $id)->update([
      'id_film_tayang' => $req->id_film_tayang,
      'id_petugas' => $req->id_petugas,
      'tanggal' => $req->tanggal,
      'harga' => $req->harga
    ]);
    $status=1;
    $message="Berhasil Mengubah Tiket";
    if($ubah){
      return Response()->json(compact('status','message'));
    } else {
      return Response()->json(['status' => 0]);
    }

  }

  public function tampil(){
    if(Auth::user()->level=="petugas"){
      $tiket=Tiket::orderBy('waktu', 'ASC')
      ->join('film_tayang', 'film_tayang.id', 'tiket.id_film_tayang')
      ->join('film', 'film.id', 'film_tayang.id_film')
      ->join('studio', 'studio.id', 'film_tayang.id_studio')
      ->select('tiket.id', 'tiket.tanggal', 'film_tayang.waktu', 'film.nama_film', 'film.deskripsi', 'studio.nama_studio')
      ->get();
      $count=$tiket->count();
      return Response()->json(compact('tiket','count'));
      } else {
        return response()->json(['status'=>'anda bukan petugas']);
      }
    }

  public function destroy($id){
    $hapus = Tiket::where('id', $id)->delete();
    $status=1;
    $message="Berhasil Membuang Tiket";
    if($hapus){
      return Response()->json(compact('status','message'));
    } else {
      return Response()->json(['status' => 0]);
    }
  }

//SELECT e.employee_name, e.salary, e.dept_name FROM Employee e INNER JOIN
//Register r ON e.emp_id = e.emp_id INNER JOIN Department d ON r.dept_id = d.dept_id

}
