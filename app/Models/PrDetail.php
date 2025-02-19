<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrDetail extends Model
{
    use HasFactory;

    protected $fillable = ['pr_id' ,'kode_barang', 'jumlah_diajukan'];

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'kode_barang', 'kode_barang');
    }

    public function pr()
    {
        return $this->belongsTo(Pr::class, 'pr_id');
    }

    public function penerimaan()
    {
        return $this->hasMany(\App\Models\PenerimaanBarang::class, 'pr_detail_id');
    }



}
