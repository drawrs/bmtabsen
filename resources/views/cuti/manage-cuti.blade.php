@extends('layouts.masbar')
@section('title', 'Cuti')
@section('content')
<div class="row">
  
  <div class="col-md-12">
    <!-- /.box -->
    <div class="box box-primary">
      <div class="box-header">
        <h3 class="box-title">Riwayat Pengajuan Cuti</h3>
      </div>
      <div class="box-body  table-responsive">
        <table class="table table-bordered table-hover">
          <tbody><tr>
            <th >ID</th>
            <th width="150px">Nama Karyawan</th>
            <th>Jabatan</th>
            <th width="10vh">Lama Cuti</th>
            <th width="10vh">Dari Tanggal</th>
            <th width="10vh">Sampai Tanggal</th>
            <th>Keterangan</th>
            <th>Status</th>
            <th>Aksi</th>
          </tr>
          
          @foreach($cuti as $c_out)
          <tr>
            <td>{{$c_out->kode}}</td>
            <td>{{$c_out->user->name}}</td>
            <td>{{$c_out->user->detail->jabatan->name}}</td>
            <td>{{$c_out->qty}} Hari</td>
            <td>{{$c_out->from}}</td>
            <td>{{$c_out->to}}</td>
            <td>{{$c_out->note}}</td>
            <td>
              @if($c_out->status == 3)
                <b class="btn btn-danger btn-block">Pending</b>
              @elseif($c_out->status == 2)
                <b class="btn btn-warning btn-block">ACC Pimpinan cabang</b>
              @elseif($c_out->status == 1)
                <b class="btn btn-success btn-block">ACC</b>
              @elseif($c_out->status == 0)
                <b class="btn btn-danger btn-block">Ditolak</b>
              @endif
            </td>
            <td>
              <button class="btn btn-success acc-cuti" value="{{$c_out->id}}">ACC</button>
              <button class="btn btn-danger dec-cuti"  value="{{$c_out->id}}">DEC</button>
            </td>
          </tr>
          @endforeach
        </tbody></table>
      </div>
      <!-- /.box-body -->
      <div class="box-footer">
        {!! $cuti->links() !!}
      </div>
      
    </div>
    <!-- /.box -->
    
    
    
  </div>
  <!-- /.col (left) -->
  
  <!-- /.col (right) -->
</div>
@endsection
@section('script')
<script src="{{URL::to('dist/sweetalert.min.js')}}"></script>
<link rel="stylesheet" type="text/css" href="{{URL::to('dist/sweetalert.css')}}">
@endsection
@section('bottom-script')

<!-- date-range-picker -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>
<script src="{{URL::to('admin/plugins/daterangepicker/daterangepicker.js')}}"></script>
<!-- bootstrap datepicker -->
<script src="{{URL::to('admin/plugins/datepicker/bootstrap-datepicker.js')}}"></script>
<!-- bootstrap time picker -->
<script src="{{URL::to('admin/plugins/timepicker/bootstrap-timepicker.min.js')}}"></script>


<!-- Page script -->

<script>
   $(document).ready(function() {
      $('.acc-cuti').click(function(){
        var id = $(this).attr("value");
        swal({
            title: "Anda Yakin?",
            text: "",
            type: "warning",
            showCancelButton: true,
            closeOnConfirm: false,
            confirmButtonText: "Ya",
            cancelButtonText: "Tutup",
            confirmButtonColor: "#DD6B55",
            showLoaderOnConfirm: true,
          },
          function(){
            setTimeout(function(){
              $.ajax({
                type: "POST",
                url: "{{route('cuti.aksi')}}",
                data : { act: 'acc', id : id, _token: "{{csrf_token()}}"},
                success: function(msg) {
                    if (msg == '0') {
                      swal("Data tidak ditemukan!", "Data tidak ditemukan! Silahkan refresh halaman.", "error");
                    } else if (msg == '1') {
                      swal("Berhasil!", "Permohonan Cuti Di ACC.", "success");
                    } else {
                      swal("Gagal!", "Terjadi kesalahan, silahkan hubungi Admin atau Webmaster.", "error");
                    }
                     location.reload();
                }
              });
            }, 2000);
          });
      });
      $('.dec-cuti').click(function(){
        var id = $(this).attr("value");
        swal({
            title: "Anda Yakin?",
            text: "",
            type: "warning",
            showCancelButton: true,
            closeOnConfirm: false,
            confirmButtonText: "Ya",
            cancelButtonText: "Tutup",
            confirmButtonColor: "#DD6B55",
            showLoaderOnConfirm: true,
          },
          function(){
            setTimeout(function(){
              $.ajax({
                type: "POST",
                url: "{{route('cuti.aksi')}}",
                data : { act: 'dec', id : id, _token: "{{csrf_token()}}"},
                success: function(msg) {
                    if (msg == '0') {
                      swal("Data tidak ditemukan!", "Data tidak ditemukan! Silahkan refresh halaman.", "error");
                    } else if (msg == '1') {
                      swal("Berhasil!", "Permohonan cuti telah di tolak.", "success");
                    } else {
                      swal("Gagal!", "Terjadi kesalahan, silahkan hubungi Admin atau Webmaster.", "error");
                    }
                     location.reload();
                }
              });
            }, 2000);
          });
      });
      
        $('.batal-cuti').click(function(){
          window.dataID = $.trim($(this).attr("value"));
          swal({
            title: "Batalkan?",
            text: "Anda yakin ingin membatalkan Pengajuan Cuti?",
            type: "warning",
            showCancelButton: true,
            closeOnConfirm: false,
            confirmButtonText: "Ya",
            cancelButtonText: "Tutup",
            confirmButtonColor: "#DD6B55",
            showLoaderOnConfirm: true,
          },
          function(){
            setTimeout(function(){
              $.ajax({
                type : "POST",
                url : "{{route('cuti.batal')}}",
                data : { cuti_id : window.dataID, _token : "{{ csrf_token() }}"},
                success: function(msg) {
                    if (msg == '0') {
                      swal("Data tidak ditemukan!", "Data tidak ditemukan! Silahkan refresh halaman.", "error");
                    } else if (msg == '1') {
                      swal("Berhasil!", "Data telah dihapus.", "success");
                    } else {
                      swal("Gagal!", "Terjadi kesalahan, silahkan hubungi Admin atau Webmaster.", "error");
                    }
                     location.reload();
                }
              });
            }, 2000);
          });
        });
    });
</script>
@endsection