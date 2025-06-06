<?php

use Filament\Facades\Filament;
use Illuminate\Support\Facades\Route;
use App\Filament\Resources\PrResource;
use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\PrDetail;
use App\Http\Controllers\PdfController;
// Halaman Utama
Route::get('/', function () {
    return view('welcome');
});

// API Get Barang
Route::get('/get-barang/{kode_barang}', function ($kode_barang) {
    $barang = Barang::where('kode_barang', $kode_barang)->first();
    return response()->json($barang);
});


// PrResource::routes(Filament::getCurrentPanel());



Route::get('/pr/{id}/print', [PdfController::class, 'print'])->name('pr.print');
Route::get('/barang/stok/pdf', [PdfController::class, 'printStokBarang'])->name('barang.stok.pdf');
Route::get('/barang/keluar/pdf', [PdfController::class, 'PrintBarangKeluar'])->name('barang.keluar.pdf');




// API untuk menerima barang
Route::post('/terima-barang/{id}', function (Request $request, $id) {
    $record = PrDetail::findOrFail($id);

    $record->update([
        'jumlah_diajukan' => $record->jumlah_diajukan - $request->jumlah_diterima,
        'jumlah_diterima' => $request->jumlah_diterima,
        'keterangan_vendor' => $request->keterangan_vendor,
        'keterangan_barang' => $request->keterangan_barang,
    ]);

    return redirect()->back()->with('message', 'Barang berhasil diterima!');
})->name('terima-barang');
