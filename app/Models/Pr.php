<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pr extends Model
{
    use HasFactory;

    protected $fillable = ['no_pr', 'tanggal_diajukan', 'required_for', 'request_by', 'checker_1_status', 'checker_2_status', 'direktur_status'];



    protected static function boot()
    {
        parent::boot();

        static::creating(function ($pr) {
            $pr->no_pr = self::generateNoPr();
        });
    }

    public static function generateNoPr()
    {
        $lastPr = self::latest()->first();
        $lastNumber = $lastPr ? intval(substr($lastPr->no_pr, 2)) : 0;
        $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        return 'A-' . $newNumber;
    }

    public function barang()
{
    return $this->belongsTo(Barang::class, 'kode_barang', 'kode_barang'); // Pastikan relasi benar
}

public function prDetails()
{
    return $this->hasMany(PrDetail::class, 'pr_id', 'id');
}

public function prPengajuan()
{
    return $this->hasMany(PrPengajuan::class, 'pr_id', 'id');
}

}
