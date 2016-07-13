<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel">
        <div class="pull-left image">
          <img src="{{URL::to('admin/dist/img/user2-160x160.jpg')}}" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
          <p>{{ Auth::user()->name }}</p>
         <!--  <a href="#"><i class="fa fa-circle text-success"></i> Online</a> -->
        </div>
      </div>
     
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu">
        <li class="header">MAIN NAVIGATION</li>
        <li class="treeview">
          <a href="#">
            <i class="fa fa-dashboard"></i> <span>Absen</span> <i class="fa fa-angle-left pull-right"></i>
          </a>
          <ul class="treeview-menu">
            <li><a href="{{ route('home') }}"><i class="fa fa-circle-o"></i> Absen</a></li>
            <li><a href="{{ route('absen.detail') }}"><i class="fa fa-circle-o"></i> Lihat Absensiku</a></li>
          </ul>
        </li>
        <li class="treeview">
          <a href="{{ route('cuti') }}">
            <i class="fa fa-files-o"></i>
            <span>Cuti</span>
          </a>
        </li>
        @if(Auth::user()->level == 'pc' || Auth::user()->level == 'hrd' )
        <li class="treeview">
          <a href="{{ route('cuti.request') }}">
            <i class="fa fa-files-o"></i>
            <span>Permohonan Cuti</span>
          </a>
        </li>
        @endif
        @if(Auth::user()->level == 'admin' || Auth::user()->level == 'pc' || Auth::user()->level == 'hrd')
        <li class="treeview">
          <a href="{{ route('karyawan') }}">
            <i class="fa fa-files-o"></i>
            <span>Karyawan</span>
          </a>
        </li>
        <li class="treeview">
          <a href="{{ route('report.absen') }}">
            <i class="fa fa-files-o"></i>
            <span>Laporan Absensi</span>
          </a>
        </li>
        <li class="treeview">
          <a href="{{ route('report.karyawan') }}">
            <i class="fa fa-files-o"></i>
            <span>Laporan Data Karyawan</span>
          </a>
        </li>
        @endif
        
        <li><a href="{{ URL::to('/logout') }}">Keluar</a></li>
        
      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>