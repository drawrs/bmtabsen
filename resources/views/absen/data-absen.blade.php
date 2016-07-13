@extends('layouts.masbar')
@section('title','Absensiku')
@section('content')
  <section class="content">
      <div class="row">
        <div class="col-xs-12">

          @if($cek == 0)
          <strong>Tidak ada absensi untuk ditampilkan</strong>
          @endif
          @foreach($bulan as $bulan)
          <?php
                $absen = DB::table('absen')
                          ->join('bulan','absen.bulan_id','=','bulan.id')
                          ->where('user_id', Auth::user()->id)
                          ->where('bulan_id', $bulan->id)
                          ->get();
                $no=1;
                ?>
          @if(!empty($absen))
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Data Absen Bulan <strong>{{ $bulan->nama_bln }}</strong></h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body table-responsive">
              <table id="example" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th width="10vh">No</th>
                  <th>Taggal</th>
                  <th>Jam Masuk</th>
                  <th>Jam Pulang</th>
                  <th>Jam Ijin</th>
                  <th>Jam Kembali</th>
                  <th>Total Jam Kerja</th>
                  <th width="400vh">Keterangan</th>
                </tr>
                </thead>
                <tbody>
                
                  @foreach($absen as $absen)
                  <tr>
                    <td>{{$no++}}</td>
                    <td>{{$absen->tgl}}</td>
                    <td>{{$absen->jam_in}}</td>
                    <td>{{$absen->jam_out}} @if(is_null($absen->jam_out)) <i><strong>Belum Absen</strong></i> @endif</td>
                    <td>{{$absen->out_ijin}} @if(is_null($absen->out_ijin)) <i><strong>Belum Ijin</strong></i> @endif</td>
                    <td>{{$absen->in_ijin}} @if(is_null($absen->in_ijin)) <i><strong>Belum Ijin</strong></i> @endif</td>
                    <td><strong>{{$absen->jam_kerja}}</strong> @if(is_null($absen->jam_kerja)) <i><strong>-</strong></i> @endif Jam</td>
                    <td>{{$absen->kt_ijin}} @if(is_null($absen->kt_ijin)) <i><strong>-</strong></i> @endif</td>
                  </tr>
                  @endforeach
                
                <!-- disini -->
                </tfoot>
              </table>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
          @endif
          @endforeach
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
@endsection

