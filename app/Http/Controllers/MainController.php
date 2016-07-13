<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Carbon\Carbon;
use DateTimeZone;
use App\Absen;
use App\Bulan;
use App\Cuti;
use App\TempCuti;
use App\CutiOut;
use App\User;
use Auth;
use DB;
use App\Karyawan;
use Validator;
class MainController extends Controller
{
    //
    protected $date = '';
    protected $bulan = [
                    '1' => 'Januari',
                    '2' => 'Ferbuari',
                    '3' => 'Maret',
                    '4' => 'April',
                    '5' => 'Mei',
                    '6' => 'Juni',
                    '7' => 'Juli',
                    '8' => 'Agustus',
                    '9' => 'September',
                    '10' => 'Oktober',
                    '11' => 'November',
                    '12' => 'Desember'
                ];
    public function date ($x){
        return $x;
    }
    public function home ()
    {
        /*$tgl = Carbon::createFromDate( date('Y'), date('m'), date('d'), 'Asia/Jakarta');*/
        $date = Carbon::now(new DateTimeZone('Asia/Jakarta'));
        $absen = Absen::where('user_id', Auth::user()->id)
                        ->where('tgl', $date->toDateString())->get();
        if ($absen->count() == 0) {
            $info = array(
                'msg' => "Anda Belum absen hari ini.",
                'st_in' => "",
                'st_out' => "disabled"
                );
            $ijin = array(
                    'out' => 'disabled',
                    'in' => 'disabled'
                    );
        } else {
            if ($absen->first()->jam_out == NULL){
                $info = array(
                    'msg' => "Absen pagi berhasil, jangan lupa absen pulang.",
                    'st_in' => "disabled",
                    'st_out' => ""
                    );
            } else {
                $info = array(
                    'msg' => "Absen hari ini selesai, Terimakasih. :)",
                    'st_in' => "disabled",
                    'st_out' => "disabled"
                    );
            }
            if ($absen->first()->out_ijin == NULL AND $absen->first()->jam_out == NULL) {
                $ijin = array(
                    'out' => '',
                    'in' => 'disabled'
                    );
            } elseif ($absen->first()->in_ijin == NULL AND $absen->first()->jam_out == NULL) {
                $ijin = array(
                    'out' => 'disabled',
                    'in' => ''
                    );
            } else {
                $ijin = array(
                    'out' => 'disabled',
                    'in' => 'disabled'
                    );
            }
        }
        return view('home', ['absen' => $absen, 'info' => $info, 'btn_ijin' => $ijin]);
    }
    public function absen_now (Request $request)
    {
        $date = Carbon::now(new DateTimeZone('Asia/Jakarta'));
        $user_id = Auth::user()->id;
        $absen = new Absen;
        $act = $request->act;
        $kt_ijin = $request->kt_ijin;
        switch ($act) {
            case 'in':
                $absen->user_id = $user_id;
                $absen->tgl = $date->toDateString();
                $absen->bulan_id = $date->parse()->setTimezone('GMT+7')->month;
                $absen->jam_in = $date->toTimeString();
                if ($absen->save()) {
                    return '1';
                }
                break;

            case 'out':
                $absen = Absen::where('user_id', Auth::user()->id)
                            ->where('tgl', $date->toDateString());
                if ($absen->update(['jam_out' => $date->toTimeString()]))
                {

                    $data = $absen->first();
                    $n_date = $data->tgl;
                    $n_in = $data->jam_in;
                    $n_out = $data->jam_out;
                    $date = explode('-', $n_date);
                    $hour_in = explode(':', $n_in);
                    $hour_out = explode(':', $n_out);
                    $time_in = Carbon::create($date[0], $date[1], $date[2], $hour_in[0], $hour_in[1], $hour_in[2]);
                    $time_out = Carbon::create($date[0], $date[1], $date[2], $hour_out[0], $hour_out[1], $hour_out[2]);
                    
                    if ($data->out_ijin == NULL) {
                        $result = $time_out->diffInHours($time_in);
                    } else {
                        $hour_out_ijin = explode(':', $data->out_ijin);
                        $ijin_out = Carbon::create($date[0], $date[1], $date[2], $hour_out_ijin[0], $hour_out_ijin[1], $hour_out_ijin[2]);

                        $result = $ijin_out->diffInHours($time_in);
                        if ($data->in_ijin !== NULL) {
                            $hour_in_ijin = explode(':', $data->in_ijin);
                            $ijin_in = Carbon::create($date[0], $date[1], $date[2], $hour_in_ijin[0], $hour_in_ijin[1], $hour_in_ijin[2]);
                            $result += $time_out->diffInHours($ijin_in);
                        }
                    }
                    $data->update(['jam_kerja' => $result]);
                    return '1';
                }
                break;

            case 'out_ijin':
                if ($absen->where('user_id', Auth::user()->id)
                      ->where('tgl', $date->toDateString())
                      ->update(['out_ijin' => $date->toTimeString(), 'kt_ijin' => $kt_ijin ]))
                {
                    return '1';
                }
                break;

            case 'in_ijin':
                if ($absen->where('user_id', Auth::user()->id)
                      ->where('tgl', $date->toDateString())
                      ->update(['in_ijin' => $date->toTimeString()]))
                {
                    return '1';
                }
                break;
        }
        return '0';
    }
    public function data_absen ()
    {
        $bulan = Bulan::orderBy('id','asc')->get();
        $cek = Absen::where('user_id', Auth::user()->id)->count();
        return view('absen.data-absen', ['bulan' => $bulan, 'cek' => $cek]);
    }
    public function jam () {
        /*return Carbon::now(new DateTimeZone('Asia/Jakarta'))->parse()->setTimezone('GMT+7')->hour;*/
        $x = Carbon::create(2016, 7, 10, 0);
        $y = Carbon::create(2016, 7, 4, 0);
        $diff = date_diff($y, $x);
        echo $diff->format("%a");
    }
    public function cuti ()
    {
        $cuti = Cuti::where('user_id', Auth::user()->id)->first();
        $temp_cuti = TempCuti::where('user_id', Auth::user()->id)->orderBy('id','desc')->get();
        $cuti_out = CutiOut::where('user_id' , Auth::user()->id)->orderBy('id','desc')->paginate(5);
        return view('cuti.cuti', ['cuti' => $cuti, 'temp_cuti' => $temp_cuti, 'cuti_out' => $cuti_out]);
    }
    public function cuti_temp (Request $request)
    {
        $this->validate($request, [
            'date' => 'required|max:100',
            'note' => 'required|max:200'
            ]);

        $tgl = $request->date;
        $note = $request->note;
        $date = explode('-', $tgl);

        $from = trim($date[0]);
        $to = trim($date[1]);

        $cut_from = explode('/', $from);
        $cut_to = explode('/', $to);
       
        $x_from = Carbon::create($cut_from[2], $cut_from[0], $cut_from[1], 0, 0, 0);
        $y_to = Carbon::create($cut_to[2], $cut_to[0], $cut_to[1], 0, 0, 0);
        
        // Jumlah hari cuti
        $result = date_diff($x_from, $y_to)->format("%a");
        $cuti = Cuti::where('user_id', Auth::user()->id)->first();
        if ($result > $cuti->qty) {
            return redirect()->back()->with(['message' => 'Permintaan waktu cuti melebihi sisa cuti anda.', 'type' => 'danger']);
        }
        $cuti->update(['qty' => $cuti->qty-$result ]);
        // Buat kode
        $get = TempCuti::select('id')->orderBy('id','desc')->first();
        
        if (empty($get)) {
            $kode = 1;
        } else {
            $kode = $get->id + 1;
        }
        // Keperluan/Keterangan
        $note = $request->note;
        // Status : Pending = 3
        $status = 3;
        $temp = new TempCuti;
        $temp->user_id = Auth::user()->id;
        $temp->kode = 'CUTI-'.$kode.'-'.date("dmY");
        $temp->qty = $result;
        $temp->from = $from;
        $temp->to = $to;
        $temp->note = $note;
        $temp->status = $status;
        if ($temp->save()) {
            $message = 'Ditambahkan';
            $type = 'info';
        } else {
            $message = 'Terjadi kesalahan';
            $type = 'danger';
        }
        return redirect()->back()->with(['message' => $message, 'type' => $type]);
    }
    public function cuti_send (Request $request)
    {
        $temp = TempCuti::where('user_id', Auth::user()->id);
        $cuti = $temp->get();
        //$cuti_out = new CutiOut;
        foreach ($cuti as $cuti) {
            $from = $cuti->from;
            $to = $cuti->to;
            
            // Jumlah hari cuti
            $result = $cuti->qty;
            // Buat kode
            $kode = $cuti->kode;
            // Keperluan/Keterangan
            $note = $cuti->note;
            // Status : Pending = 3
            $status = 3;
            //S$cuti_out = new CutiOut;
            $cuti_out = new CutiOut;
            $cuti_out->create(['user_id' => $cuti->user_id,
                                'kode' => $kode,
                                'qty' => $result,
                                'from' => $from,
                                'to' => $to,
                                'note' => $note]);
            $temp->delete();
            
        }
        return redirect()->back();
    }
    public function cuti_batal(Request $request)
    {
        $temp = TempCuti::find($request->cuti_id);
        if (is_null($temp)) {
            return '0';
        }
        $cuti = Cuti::where('user_id', Auth::user()->id);
        $cuti->update(['qty' => $cuti->first()->qty + $temp->qty]);
        if ($temp->delete()) {
            return '1';
        }
        return '2';
    }
    public function report_absen ()
    {
        $absen = Absen::where('user_id', '1')
                ->orderBy('tgl','asc')
                ->get();
        return view('report.absen', ['absens' => $absen]);
    }
    public function tos (Request $request)
    {
        $date = Carbon::now(new DateTimeZone('Asia/Jakarta'));
        $data = Absen::where('user_id', Auth::user()->id)
                        ->where('tgl', $date->toDateString())->first();
        $n_date = $data->tgl;
        $n_in = $data->jam_in;
        $n_out = $data->jam_out;
        $date = explode('-', $n_date);
        $hour_in = explode(':', $n_in);
        $hour_out = explode(':', $n_out);
        $time_in = Carbon::create($date[0], $date[1], $date[2], $hour_in[0], $hour_in[1], $hour_in[2]);
        $time_out = Carbon::create($date[0], $date[1], $date[2], $hour_out[0], $hour_out[1], $hour_out[2]);
        
        if ($data->out_ijin == NULL) {
            $result = $time_out->diffInHours($time_in);
        } else {
            $hour_out_ijin = explode(':', $data->out_ijin);
            $ijin_out = Carbon::create($date[0], $date[1], $date[2], $hour_out_ijin[0], $hour_out_ijin[1], $hour_out_ijin[2]);

            $result = $ijin_out->diffInHours($time_in);
            if ($data->in_ijin !== NULL) {
                $hour_in_ijin = explode(':', $data->in_ijin);
                $ijin_in = Carbon::create($date[0], $date[1], $date[2], $hour_in_ijin[0], $hour_in_ijin[1], $hour_in_ijin[2]);
                $result += $time_out->diffInHours($ijin_in);
            }
        }
        $data->update(['jam_kerja' => $result]);
        return $data->tgl;
    }
}
