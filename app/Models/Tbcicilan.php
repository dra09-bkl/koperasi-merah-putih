<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbcicilan extends Model
{
    protected $table = 'tbcicilans';

    protected $fillable = [
        'pinjaman_id',
        'user_id',
        'bulan_ke',
        'jumlah_cicilan',
        'pokok',
        'bunga',
        'denda',
        'tanggal_jatuh_tempo',
        'tanggal_bayar',
        'foto_bukti',
        'status',
        'catatan',
    ];

    protected $casts = [
        'jumlah_cicilan'     => 'decimal:2',
        'pokok'              => 'decimal:2',
        'bunga'              => 'decimal:2',
        'denda'              => 'decimal:2',
        'tanggal_jatuh_tempo'=> 'date',
        'tanggal_bayar'      => 'date',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELASI
    |--------------------------------------------------------------------------
    */

    /** Cicilan ini milik pinjaman mana */
    public function pinjaman()
    {
        return $this->belongsTo(TbPinjaman::class, 'pinjaman_id');
    }

    /** Cicilan ini milik user mana */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /*
    |--------------------------------------------------------------------------
    | HELPER
    |--------------------------------------------------------------------------
    */

    /** Total yang harus dibayar termasuk denda */
    public function getTotalAttribute(): float
    {
        return (float) $this->jumlah_cicilan + (float) $this->denda;
    }

    /** Cek apakah sudah lewat jatuh tempo & belum dibayar */
    public function isTerlambat(): bool
    {
        return $this->status === 'belum_bayar'
            && $this->tanggal_jatuh_tempo < now()->startOfDay();
    }
}