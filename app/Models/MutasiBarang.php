<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MutasiBarang extends Model
{
    use HasFactory;

    protected $table = 'mutasi_barang'; // Sesuaikan dengan nama tabel

    protected $fillable = [
        'kode_barang',
        'tanggal',
        'jenis',
        'jumlah',
        'keterangan',
        'pengguna', // Tambahkan pengguna
    ];



    // Relasi dengan model Barang
    public function barang(): BelongsTo
    {
        return $this->belongsTo(Barang::class, 'kode_barang', 'kode_barang');

    }

    // Event untuk update stok setelah penyimpanan mutasi barang
    protected static function booted()
    {
        static::creating(function ($mutasiBarang) {
            $mutasiBarang->cekStokSebelumMutasi();
        });

        static::created(function ($mutasiBarang) {
            $mutasiBarang->updateStokBarang();
        });

        static::updated(function ($mutasiBarang) {
            $mutasiBarang->updateStokBarang();
        });
    }

    public function cekStokSebelumMutasi()
    {
        $barang = Barang::where('kode_barang', $this->kode_barang)->first();

        if (!$barang) {
            throw new \Exception("Barang dengan kode {$this->kode_barang} tidak ditemukan.");
        }

        if ($this->jenis === 'output' && $this->jumlah > $barang->stok) {
            throw new \Exception("Stok barang tidak mencukupi.");
        }

        return true;
    }



    public function updateStokBarang()
    {
        $barang = Barang::where('kode_barang', $this->kode_barang)->first();

        if (!$barang) {
            throw new \Exception("Barang dengan kode {$this->kode_barang} tidak ditemukan.");
        }

        if ($this->jenis === 'input') {
            $barang->stok += $this->jumlah;
        } elseif ($this->jenis === 'output') {
            $barang->stok -= $this->jumlah;
        }

        $barang->save();
    }

}
