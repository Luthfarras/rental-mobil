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


class TransController extends Controller
{
  public function laporan ($tgl1, $tgl2){
    if(Auth::user()->level=="petugas"){

    $trans = DB::table('transaksi')
    ->join('pelanggan', 'pelanggan.id', 'transaksi.id_pelanggan')
    ->join('petugas', 'petugas.id', 'transaksi.id_petugas')
    ->where('transaksi.tgl_trans', '>=', $tgl1)
    ->where('transaksi.tgl_trans', '<=', $tgl2)
    ->select('transaksi.id', 'tgl_trans', 'nama', 'alamat', 'pelanggan.telp', 'tgl_selesai')
    ->get();

    $dt_tr = array();
    $no = 0;
    foreach ($trans as $t) {
      $dt_tr[$no]['id'] = $t->id;
      $dt_tr[$no]['tgl_trans'] = $t->tgl_trans;
      $dt_tr[$no]['nama'] = $t->nama;
      $dt_tr[$no]['alamat'] = $t->alamat;
      $dt_tr[$no]['telp'] = $t->telp;
      $dt_tr[$no]['tgl_selesai'] = $t->tgl_selesai;

      $grand = DB::table('detail_trans')->where('id_trans', $t->id)->groupBy('id_trans')
      ->select(DB::raw('sum(subtotal) as grandtotal'))->first();

      $dt_tr[$no]['grandtotal'] = $grand->grandtotal;
      $detail=DB::table('detail_trans')->join('jenis_cuci', 'jenis_cuci.id', 'detail_trans.id_jenis')
      ->where('id_trans', $t->id)->select('jenis_cuci.nama_jenis', 'jenis_cuci.harga_perkilo', 'detail_trans.qty', 'detail_trans.subtotal')->get();

      $dt_tr[$no]['detail'] = $detail;
      $no++;
    }
    return response()->json(compact('dt_tr'));
  } else {
      return response()->json(['status'=>'anda bukan petugas']);
  }
}

  public function store(Request $req){
    if(Auth::user()->level=="petugas"){
    $validator = Validator::make($req->all(),
    [
      'id_pelanggan' => 'required',
      'id_petugas' => 'required',
      'tgl_trans' => 'required',
      'tgl_selesai' => 'required'
    ]);
    if($validator->fails()){
      return Response()->json($validator->errors());
    }
    $simpan = Transaksi::create([
      'id_pelanggan' => $req->id_pelanggan,
      'id_petugas' => $req->id_petugas,
      'tgl_trans' => $req->tgl_trans,
      'tgl_selesai' => $req->tgl_selesai
    ]);
    $status=1;
    $message="Tambah Transaksi Berhasil";
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
      'id_pelanggan' => 'required',
      'id_petugas' => 'required',
      'tgl_trans' => 'required',
      'tgl_selesai' => 'required'
    ]);
    if($validator->fails()){
      return Response()->json($validator->errors());
    }
    $ubah = Transaksi::where('id', $id)->update([
      'id_pelanggan' => $req->id_pelanggan,
      'id_petugas' => $req->id_petugas,
      'tgl_trans' => $req->tgl_trans,
      'tgl_selesai' => $req->tgl_selesai
    ]);
    $status=1;
    $message="Ubah Transaksi Berhasil";
    if($ubah){
      return Response()->json(compact('status','message'));
    } else {
      return Response()->json(['status' => 0]);
    }
  }

  // public function tampil(){
  //   if(Auth::user()->level=="admin"){
  //   $jenis=Jenis::get();
  //   $count=$jenis->count();
  //   $arr_data=array();
  //   foreach ($jenis as $j){
  //     $arr_data[]=array(
  //       'nama_jenis' => $j->nama_jenis,
  //       'harga_perkilo' => $j->harga_perkilo
  //     );
  //   }
  //   $status=1;
  //   return Response()->json(compact('status','count','arr_data'));
  // } else {
  //   return Response()->json(['status' => 0]);
  // }
  // }

  public function destroy($id){
    $hapus = Transaksi::where('id', $id)->delete();
    $status=1;
    $message="Hapus Transaksi berhasil";
    if($hapus){
      return Response()->json(compact('status','message'));
    } else {
      return Response()->json(['status' => 0]);
    }
  }

  public function show(Request $req){
      if(Auth::user()->level == "petugas"){
          $transaksi = DB::table('transaksi')->join('pelanggan','pelanggan.id','=','transaksi.id_pelanggan')
          ->where('transaksi.tgl_trans','>=',$req->tgl_trans)
          ->where('transaksi.tgl_selesai','<=',$req->tgl_selesai)
          ->get();

          if($transaksi->count() > 0){
          $data_transaksi = array();
          foreach ($transaksi as $tr){
              $grand = DB::table('detail_trans')->where('id_trans','=',$tr->id)
              ->groupBy('id_trans')
              ->select(DB::raw('sum(subtotal) as grandtotal'))
              ->first();

              $detail = DB::table('detail_trans')->join('jenis_cuci','detail_trans.id_jenis','=','jenis_cuci.id')
              ->where('id_trans','=',$tr->id)
              ->get();

              $data [] = array(
                'tgl' => $tr->tgl_trans,
                'nama_pelanggan' => $tr->nama,
                'alamat' => $tr->alamat,
                'telp' => $tr->telp,
                'tgl_selesai' => $tr->tgl_selesai,
                'grandtotal' => $grand,
                'detail' => $detail
                );
            }
          return response()->json(compact('data'));
          } else {
            $status = 'tidak ada transaksi antara tanggal '.$req->tgl_trans.' sampai dengan tanggal '.$req->tgl_selesai;
            return response()->json(compact('status'));
            }
            } else {
            return Response()->json('Anda Bukan Petugas');
          }
        }
}
