@extends('layouts.masbar')
@section('title','Edit Data Karyawan')
@section('content')
@include('includes.messages')
  <section class="content">
      <div class="row">
        <div class="col-xs-12">
          
          <?php
           $no=1;
          ?>
          
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Data karyawan</strong></h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body table-responsive">
              <form class="form-horizontal" role="form" method="POST" action="{{ route('karyawan.edit-post', ['id' => $user->id]) }}">
                        {{ csrf_field() }}
                        <input type="hidden" name="id" value="{{$user->id}}">
                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="name" class="col-md-4 control-label">Nama Lengkap</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control" name="name" value="{{$user->detail->nama}}">

                                @if ($errors->has('name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('ktp') ? ' has-error' : '' }}">
                            <label for="ktp" class="col-md-4 control-label">Nomor KTP</label>

                            <div class="col-md-6">
                                <input id="ktp" type="text" class="form-control" name="ktp" value="{{ $user->detail->ktp }}">

                                @if ($errors->has('ktp'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('ktp') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group {{ $errors->has('tgl') ? ' has-error' : '' }}">
                            <label for="tgl" class="col-md-4 control-label">Tanggal Lahir</label>

                            <div class="">
                                <div class="col-md-2">
                                  <select name="tgl" id="" class="form-control">
                                    @for ($i=1; $i <= 31 ; $i++)
                                    <?php
                                    if ($i < 10) {
                                        $angka = '0'.$i;
                                    } else {
                                        $angka = $i;
                                    }
                                    ?>
                                    <option value="{{$angka}}" {{$angka == $tgl[2] ? "SELECTED" : ''}}>{{$angka}}</option>
                                    @endfor
                                </select>
                                </div>
                                <div class="col-md-2">
                                  <select name="bln" id="" class="form-control">
                                    <?php
                                    $bln = array('','Januari','Febuari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember');
                                    ?>
                                    @for ($i=1; $i <= 12 ; $i++)
                                    <?php
                                    if ($i < 10) {
                                        $angka = '0'.$i;
                                    } else {
                                        $angka = $i;
                                    }
                                    ?>
                                    <option value="{{$angka}}" {{$angka == $tgl[1] ? "SELECTED" : ''}}>{{$bln[$i]}}</option>
                                    @endfor
                                </select>
                                </div>
                                <div class="col-md-2">
                                  <select name="thn" id="" class="form-control">
                                    @for ($i=date("Y"); $i >= 1965 ; $i--)
                                    <option value="{{$i}}" {{ $i == $tgl[0] ? "SELECTED": ''}}>{{$i}}</option>
                                    @endfor
                                </select>
                                </div>
    
                                @if ($errors->has('tgl'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('tgl') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group {{ $errors->has('jk') ? ' has-error' : '' }}">
                            <label for="jk" class="col-md-4 control-label">Jenis Kelamin</label>

                            <div class="col-md-6">
                                <input type="radio" name="jk" value="L" {{ $user->detail->jk == 'L' ? "CHECKED": ''}}> Laki-Laki <input type="radio" name="jk" value="P" {{ $user->detail->jk == 'P' ? "CHECKED": ''}}> Perempuan
    
                                @if ($errors->has('jk'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('jk') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="form-group {{ $errors->has('jabatan') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-4 control-label">Jabatan</label>

                            <div class="col-md-6">
                                <select name="jabatan" id="" class="form-control">
                                    @foreach($jabs as $jab)
                                    <option value="{{$jab->id}}" {{$jab->id == $user->detail->jabatan_id ? "SELECTED": ''}}>{{$jab->name}}</option>
                                    @endforeach
                                </select>
    
                                @if ($errors->has('jabatan'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('jabatan') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group {{ $errors->has('cabang') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-4 control-label">Kantor Cabang</label>

                            <div class="col-md-6">
                                <select name="cabang" id="" class="form-control">
                                    @foreach($cabs as $jab)
                                    <option value="{{$jab->id}}" {{$jab->id == $user->cabang_id ? "SELECTED": ''}}>{{$jab->name}}</option>
                                    @endforeach
                                </select>
    
                                @if ($errors->has('cabang'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('cabang') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group {{ $errors->has('cuti') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-4 control-label">Jatah Cuti</label>

                            <div class="col-md-6">
                                <div class="input-group">
                                  <input type="text" class="form-control pull-left active" placeholder="Jumlah Hari Cuti" id="reservation" required="1" name="cuti" value="{{$user->cuti->qty}}">
                                  <div class="input-group-addon">
                                    Hari
                                  </div>
                                </div>
    
                                @if ($errors->has('cuti'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('cuti') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group {{ $errors->has('alamat') ? ' has-error' : '' }}">
                            <label for="alamat" class="col-md-4 control-label">Alamat Lengkap</label>

                            <div class="col-md-6">
                                <textarea name="alamat" id="" rows="3" class="form-control">{{$user->detail->alamat}}</textarea>
    
                                @if ($errors->has('alamat'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('alamat') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-4 control-label">Alamat E-mail</label>

                            <div class="col-md-6">
                                <strong class="form-control">{{$user->email}}</strong>
                            </div>
                        </div>
                        <div class="form-group {{ $errors->has('level') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-4 control-label">Level</label>

                            <div class="col-md-6">
                                <select name="level" id="" class="form-control">
                                    <option value="user" {{$user->level == 'user' ? "SELECTED": ''}}>Karyawan</option>
                                    <option value="admin" {{$user->level == 'admin' ? "SELECTED": ''}}>Admin</option>
                                    <option value="pc" {{$user->level == 'pc' ? "SELECTED": ''}}>Pimpinan Cabang</option>
                                    <option value="hrd" {{$user->level == 'hrd' ? "SELECTED": ''}}>HRD</option>
                                </select>
    
                                @if ($errors->has('level'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('level') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password"  class="col-md-4 control-label">Katasandi</label>

                            <div class="col-md-6">
                                <strong><a id="ubah_pwd" style="cursor:pointer;" data-toggle="modal" data-target="#modal-add">Ubah Katasandi</a></strong>
                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-btn fa-user"></i> Daftar
                                </button>
                            </div>
                        </div>
                    </form>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
         
         
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
      <!-- Modal -->
<div class="modal fade" id="modal-add" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
<div class="modal-dialog" role="document">
  <div class="modal-content">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
      <span aria-hidden="true">×</span></button>
      <h4 class="modal-title">Rubah Katasandi</h4>
    </div>
    <div class="modal-body">
      <form role="form" enctype="multipart/form-data" method="post" action="{{route('karyawan.gantipw')}}">
      {{csrf_field()}}
      <input type="hidden" name="id" value="{{$user->id}}">
        <div class="box-body">
          <div class="form-group">
            <label for="exampleInputEmail1">Katasandi Baru</label>
            <input class="form-control" name="password" type="password">
          </div>
          <div class="form-group">
            <label for="exampleInputFile">Ulangi Katasandi</label>
            <input id="password-confirm" type="password" class="form-control" name="password_confirmation">
          </div>
        </div>
      
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Tutup</button>
      <button type="submit" class="btn btn-primary">Simpan</button></form>
    </div>
  </div>
  <!-- /.modal-content -->
</div>
</div>
@endsection

