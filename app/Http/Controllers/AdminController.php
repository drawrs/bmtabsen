<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\User;
use App\Absen;
use App\Cabang;
use App\Jabatan;
use App\Karyawan;
use App\Cuti;
use App\CutiOut;
use Auth;
class AdminController extends Controller
{
    //
    public function report_absen ()
    {
        $users = User::orderBy('karyawan_id', 'asc')
                ->get();
        $cabangs = Cabang::orderBy('id', 'asc')
                ->get();
        return view('report.list-absen', compact('users','cabangs'));
    }
    public function report_karyawan ()
    {
        $users = User::orderBy('karyawan_id', 'asc')
                ->get();
        $cabangs = Cabang::orderBy('id', 'asc')
                ->get();
        return view('report.list-karyawan', compact('users','cabangs'));
    }
    public function printReportAbsen (Request $request)
    {
        $this->validate($request, [
            'opsi' => 'required|max:12'
            ]);
        switch ($request->opsi) {
            case 'all':
                $opsi = new Absen;
                break;
            case 'name':
            $this->validate($request, [
                'user' => 'required'
                ]);
                $opsi = Absen::where('user_id', $request->user);
                break;
            case 'date':
            $this->validate($request, [
                'date' => 'required'
                ]);
                $date = explode('-', $request->date);
                $cut_from = explode('/', trim($date[0]));
                $cut_to = explode('/', trim($date[1]));
                $from = $cut_from[2].'-'.$cut_from[0].'-'.$cut_from[1];
                $to = $cut_to[2].'-'.$cut_to[0].'-'.$cut_to[1];
                $opsi = Absen::whereBetween('tgl', [$from, $to]);
                break;
            case 'cabang':
            $this->validate($request, [
                'cabang' => 'required'
                ]);
                $cabang = $request->cabang;
                $opsi = Absen::whereHas('User', function($q) use ($cabang){
                    $q->where('cabang_id', $cabang);
                });
                break;
            default:
               return redirect()->back();
                break;
        }
        $absen = $opsi->orderBy('tgl','asc')->get();
        return view('report.absen', ['absens' => $absen]);
    }
    public function printReportKaryawan (Request $request)
    {
        $this->validate($request, [
            'opsi' => 'required|max:12'
            ]);
        switch ($request->opsi) {
            case 'all':
                $opsi = new User;
                break;
            case 'name':
            $this->validate($request, [
                'user' => 'required'
                ]);
                $opsi = User::where('id', $request->user);
                break;
            default:
               return redirect()->back();
                break;
        }
        $users = $opsi->orderBy('id','asc')->get();
        return view('report.karyawan', compact('users'));
    }
    public function cuti_req ()
    {
        if (Auth::user()->level == 'pc') {
            $opsi = CutiOut::where('status','3');
        }
        else if (Auth::user()->level == 'hrd') {
            $opsi = CutiOut::where('status','2');
        } else {
            return redirect('/');
        }
        $cuti = $opsi->orderBy('created_at','desc')->paginate(5);
        return view('cuti.manage-cuti', compact('cuti'));
    }
    public function cuti_aksi (Request $request)
    {
        $this->validate($request, [
            'act' => 'required',
            'id' => 'required'
            ]);
        $cuti = CutiOut::find($request->id);
        if (is_null($cuti)) {
            return '0';
        }

        if ($request->act == 'acc') {
            if (Auth::user()->level == 'pc') {
                $cuti->status = '2';
            } else if (Auth::user()->level == 'hrd') {
                $cuti->status = '1';
            }
        } elseif ($request->act == 'dec') {
            $stmt = Cuti::where('user_id', Auth::user()->id)->first();
            $stmt->update(['qty' => $stmt->qty + $cuti->qty]);
            $cuti->status = '0';
        }
        if($cuti->update()) {
            return '1';
        }
        return '2';
    }
    public function karyawan ()
    {
        $users = User::orderBy('karyawan_id', 'asc')->paginate(20);
        return view('karyawan', compact('users'));
    }
    public function addUser (Request $request)
    {
        
    }
    public function getAddKaryawan () {
        $jabs = Jabatan::all();
        $cabs = Cabang::all();
        return view('karyawan.tambah', compact('jabs','cabs'));
    }
    public function postAddKaryawan (Request $request) {
        $this->validate($request, [
            'name' => 'required|max:255',
            'level' => 'required|max:100',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
            'tgl' => 'required',
            'thn' => 'required',
            'bln' => 'required',
            'ktp' => 'required',
            'jk' => 'required|max:2',
            'alamat' => 'required|max:250',
            'jabatan' => 'required',
            'cabang' => 'required',
            'cuti' => 'required|integer'
            ]);
        $user = User::select('id')->orderBy('id','desc')->first();
        if (empty($user)) {
            $kode = 1;
        } else {
            $kode = $user->id + 1;
        }
        // buat ID Karyawan Format AIS-0001/AIS-xxxx
        $karyawan_id = 'AIS-000'. $kode;
        if ($kode >= 1000) {
            $karyawan_id = 'AIS-'. $kode;
        } elseif ($kode >= 100) {
            $karyawan_id = 'AIS-0'. $kode;
        } elseif ($kode >= 10) {
            $karyawan_id = 'AIS-00'. $kode;
        }
        User::create([
            'name' => $request->name,
            'karyawan_id' => $karyawan_id,
            'cabang_id' => $request->cabang,
            'level' => $request->level,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);
        $user = User::select('id')->orderBy('id','desc')->first();
        $user_id = $user->id;
        Karyawan::create([
            'user_id' => $user_id,
            'jabatan_id' => $request->jabatan,
            'ktp' => $request->ktp,
            'nama' => $request->name,
            'jk' => $request->jk,
            'tgl' => $request->thn.'-'.$request->bln.'-'.$request->tgl,
            'alamat' => $request->alamat
        ]);
        Cuti::create([
            'user_id' => $user_id,
            'qty' => $request->cuti,
            'satuan' => 'hari'
            ]);
        return redirect()->route('karyawan');
    }
    public function getEditKaryawan ($id) {
        $user = User::findOrFail($id);
        $jabs = Jabatan::all();
        $cabs = Cabang::all();
        $tgl = explode('-', $user->detail->tgl);
        //return "<textarea>$tgl[0] $tgl[1] $tgl[2]</textarea>";
        return view('karyawan.edit', compact('user','jabs','cabs', 'tgl'));
    }
    public function postEditKaryawan (Request $request) {
        $this->validate($request, [
            'id' => 'required',
            'name' => 'required|max:255',
            'level' => 'required|max:100',
            'tgl' => 'required',
            'thn' => 'required',
            'bln' => 'required',
            'ktp' => 'required',
            'jk' => 'required|max:2',
            'alamat' => 'required|max:250',
            'jabatan' => 'required',
            'cabang' => 'required',
            'cuti' => 'required|integer'
            ]);
        $user_id = $request->id;
        User::findOrFail($user_id)
            ->update([
            'name' => $request->name,
            'cabang_id' => $request->cabang,
            'level' => $request->level
        ]);
        
        Karyawan::where('user_id', $user_id)
            ->update([
            'user_id' => $user_id,
            'jabatan_id' => $request->jabatan,
            'ktp' => $request->ktp,
            'nama' => $request->name,
            'jk' => $request->jk,
            'tgl' => $request->thn.'-'.$request->bln.'-'.$request->tgl,
            'alamat' => $request->alamat
        ]);
        Cuti::where('user_id', $user_id)
            ->update([
            'user_id' => $user_id,
            'qty' => $request->cuti,
            'satuan' => 'hari'
            ]);
        return redirect()->route('karyawan');
    }
    public function gantiPwd (Request $request)
    {
        $this->validate($request, [
            'password' => 'required|min:6|confirmed'
            ]);
        $user_id = $request->id;
        User::where('id', $user_id)->update(['password' => bcrypt($request->password)]);
        return redirect()->back()->with(['message' => 'Katasandi Dirubah', 'type' => 'success']);
    }
    public function hapusUser (Request $request)
    {
        $user = User::find($request->id);
        if (is_null($user)) {
            return '0';
        }
        if ($user->delete()) {
            Karyawan::where('user_id', $request->id)->delete();
            Cuti::where('user_id', $request->id)->delete();
            Absen::where('user_id', $request->id)->delete();
            CutiOut::where('user_id', $request->id)->delete();
            return '1';
        }
        return '2';
    }
    public function postAddCabang (Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:100|min:2',
            ]);
        $cabs = Cabang::create(['name' => $request->name, 'desk' => 'none']);
        if ($cabs) {
            return '1';
        }
        return '0';
    }
    public function postDelCabang (Request $request)
    {
        $this->validate($request, [
            'id' => 'required',
            ]);
        $cabs = Cabang::find($request->id);
        if (is_null($cabs)) {
            return '0';
        }
        if ($cabs->delete()) {
            $users = User::where('cabang_id', $request->id)->get();
            foreach ($users as $user) {
                Karyawan::where('user_id', $user->id)->delete();
                Cuti::where('user_id', $user->id)->delete();
                Absen::where('user_id', $user->id)->delete();
                CutiOut::where('user_id', $user->id)->delete();
                User::where('id', $user->id)->delete();
            }
            return '1';
        }
        return '2';
    }
}
