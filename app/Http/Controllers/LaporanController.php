<?php

namespace App\Http\Controllers;

use App\Models\PendaftaranPasien;
use App\Models\Poliklinik;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function LaporanJenis(Request $request)  {
        $param['title'] = 'Laporan Kunjungan Pasien BPJS Umum';
        $start = Carbon::parse($request->start)->format('Y-m-d');
        $end = Carbon::parse($request->end)->format('Y-m-d');
        $jenis = $request->get('pembayaran');
        $param['data'] = PendaftaranPasien::with('dokter','poliklinik','pasien')
                        ->when($request->get('start'), function ($query) use ($start, $end) {
                            $query->whereBetween('created_at', [$start, $end]);
                        })
                        ->when($request->get('pembayaran'), function ($query) use ($jenis) {
                            $query->where('jenis_pembayaran', $jenis);
                        })
                        ->latest()->get();
        return view('backoffice.laporan.laporan-jenis',$param);
    }

    public function LaporanJenisPdf(Request $request) {
        $param['title'] = 'Laporan Kunjungan Pasien BPJS Umum';
        $start = Carbon::parse($request->start)->format('Y-m-d');
        $end = Carbon::parse($request->end)->format('Y-m-d');
        $jenis = $request->get('pembayaran');
        $param['data'] = PendaftaranPasien::with('dokter','poliklinik','pasien')
                        ->when($request->get('start'), function ($query) use ($start, $end) {
                            $query->whereBetween('created_at', [$start, $end]);
                        })
                        ->when($request->get('pembayaran'), function ($query) use ($jenis) {
                            $query->where('jenis_pembayaran', $jenis);
                        })
        ->latest()->get();
        $param['count_umum'] = PendaftaranPasien::with('dokter','poliklinik','pasien')
                            ->when($request->get('start'), function ($query) use ($start, $end) {
                                $query->whereBetween('created_at', [$start, $end]);
                            })->where('jenis_pembayaran','umum')->count();
        $param['count_bpjs'] = PendaftaranPasien::with('dokter','poliklinik','pasien')
                            ->when($request->get('start'), function ($query) use ($start, $end) {
                                $query->whereBetween('created_at', [$start, $end]);
                            })->where('jenis_pembayaran','bpjs')->count();
        return view('backoffice.laporan.laporan-jenis-pdf',$param);
    }

    public function LaporanJenisExcel(Request $request) {
        $param['title'] = 'Laporan Kunjungan Pasien BPJS Umum';
        $start = Carbon::parse($request->start)->format('Y-m-d');
        $end = Carbon::parse($request->end)->format('Y-m-d');
        $jenis = $request->get('pembayaran');
        $param['data'] = PendaftaranPasien::with('dokter','poliklinik','pasien')
                        ->when($request->get('start'), function ($query) use ($start, $end) {
                            $query->whereBetween('created_at', [$start, $end]);
                        })
                        ->when($request->get('pembayaran'), function ($query) use ($jenis) {
                            $query->where('jenis_pembayaran', $jenis);
                        })
                    ->latest()->get();
        $param['count_umum'] = PendaftaranPasien::with('dokter','poliklinik','pasien')
                    ->when($request->get('start'), function ($query) use ($start, $end) {
                        $query->whereBetween('created_at', [$start, $end]);
                    })->where('jenis_pembayaran','umum')->count();
        $param['count_bpjs'] = PendaftaranPasien::with('dokter','poliklinik','pasien')
                    ->when($request->get('start'), function ($query) use ($start, $end) {
                        $query->whereBetween('created_at', [$start, $end]);
                    })->where('jenis_pembayaran','bpjs')->count();
        return view('backoffice.laporan.laporan-jenis-excel',$param);
    }

    public function LaporanKunjunganPasien(Request $request)  {
        $param['title'] = 'Laporan Kunjungan Pasien Pendaftaran Online';
        $start = Carbon::parse($request->start)->format('Y-m-d');
        $end = Carbon::parse($request->end)->format('Y-m-d');
        $poliklinik = $request->get('poliklinik');
        $param['data'] = PendaftaranPasien::with('dokter','poliklinik','pasien')
                        ->when($request->get('start'), function ($query) use ($start, $end) {
                            $query->whereBetween('created_at', [$start, $end]);
                        })
                        ->when($request->get('poliklinik'), function ($query) use ($poliklinik) {
                            $query->where('poliklinik_id', $poliklinik);
                        })
                        ->latest()->get();
        $param['poliklinik'] = Poliklinik::latest()->get();
        return view('backoffice.laporan.laporan-kunjungan',$param);
    }

    public function LaporanKunjunganPasienPdf(Request $request) {
        $param['title'] = 'Laporan Kunjungan Pasien Pendaftaran Online';
        $start = Carbon::parse($request->start)->format('Y-m-d');
        $end = Carbon::parse($request->end)->format('Y-m-d');
        $poliklinik = $request->get('poliklinik');
        $param['data'] = PendaftaranPasien::with('dokter','poliklinik','pasien')
                        ->when($request->get('start'), function ($query) use ($start, $end) {
                            $query->whereBetween('created_at', [$start, $end]);
                        })
                        ->when($request->get('poliklinik'), function ($query) use ($poliklinik) {
                            $query->where('poliklinik_id', $poliklinik);
                        })
                        ->latest()->get();
        $param['count_pendaftaran_online'] = PendaftaranPasien::with('dokter','poliklinik','pasien')
                            ->when($request->get('start'), function ($query) use ($start, $end) {
                                $query->whereBetween('created_at', [$start, $end]);
                            })
                            ->when($request->get('poliklinik'), function ($query) use ($poliklinik) {
                                $query->where('poliklinik_id', $poliklinik);
                            })
                            ->where('status_pendaftaran','online')->count();

        return view('backoffice.laporan.laporan-kunjungan-pdf',$param);
    }

    public function LaporanKunjunganPasienExcel(Request $request) {
        $param['title'] = 'Laporan Kunjungan Pasien Pendaftaran Online';
        $start = Carbon::parse($request->start)->format('Y-m-d');
        $end = Carbon::parse($request->end)->format('Y-m-d');
        $poliklinik = $request->get('poliklinik');
        $param['data'] = PendaftaranPasien::with('dokter','poliklinik','pasien')
                        ->when($request->get('start'), function ($query) use ($start, $end) {
                            $query->whereBetween('created_at', [$start, $end]);
                        })
                        ->when($request->get('poliklinik'), function ($query) use ($poliklinik) {
                            $query->where('poliklinik_id', $poliklinik);
                        })
                    ->latest()->get();

        return view('backoffice.laporan.laporan-kunjungan-excel',$param);
    }



}