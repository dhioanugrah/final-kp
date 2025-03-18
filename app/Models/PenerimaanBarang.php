<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenerimaanBarang extends Model
{
    use HasFactory;

    protected $table = 'penerimaan_barang';
    protected $fillable = ['pr_detail_id', 'jumlah_diterima', 'vendor', 'tanggal_diterima'];

    public function prDetail()
    {
        return $this->belongsTo(PrDetail::class);
    }

    public function penerimaan()
{
    return $this->hasMany(\App\Models\PenerimaanBarang::class, 'pr_id', 'id');
}

}
