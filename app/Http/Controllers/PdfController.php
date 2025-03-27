<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Pr;
use App\Models\Barang;
use App\Models\MutasiBarang;
use Illuminate\Routing\Controller;

class PdfController extends Controller
{
    // Cetak PDF untuk PR (Process Request)
    public function print($id)
    {
        $pr = Pr::with('prDetails.barang')->findOrFail($id);

        $pdf = Pdf::loadView('pdf.pr', compact('pr'))->setPaper('a4', 'portrait');
        return $pdf->download("pr-$pr->id.pdf");
    }

    // Cetak PDF untuk Stok Barang
    public function printStokBarang()
    {
        $barangs = Barang::all(); // Ambil semua data barang

        $pdf = Pdf::loadView('pdf.barang_stok', compact('barangs'))
            ->setPaper('a4', 'portrait');

        return $pdf->download('stok_barang.pdf');
    }

    // Cetak PDF untuk Barang Keluar berdasarkan filter tanggal
    public function PrintBarangKeluar(Request $request)
    {
        // Ambil filter tanggal dari request
        $from = $request->input('tableFilters.tanggal.from');
        $to = $request->input('tableFilters.tanggal.to');

        // Terapkan filter ke query
        $query = MutasiBarang::query();
        
        if ($from) {
            $query->where('tanggal', '>=', $from);
        }
        if ($to) {
            $query->where('tanggal', '<=', $to);
        }

        // Ambil data hasil filter
        $mutasiBarang = $query->get();

        // Generate PDF dengan data yang sudah difilter
        $pdf = Pdf::loadView('pdf.barang_keluar', compact('mutasiBarang'));

        return $pdf->stream('barang_keluar.pdf');
    }
    
}
