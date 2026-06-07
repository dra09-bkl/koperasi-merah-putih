<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Tbpenarikan extends Model
{
    protected $table = 'penarikan';

    protected $fillable = [
        'user_id',
        'kode_penarikan',
        'jumlah',
        'nama_bank',
        'rekening_tujuan',
        'nama_rekening',
        'keterangan',
        'status',
    ];

    public static function generateKode(): string
    {
        $prefix = 'WD-' . now()->format('Ymd') . '-';

        $last = self::where('kode_penarikan', 'like', $prefix . '%')
            ->orderBy('id', 'desc')
            ->first();

        if ($last) {
            $lastNumber = (int) substr($last->kode_penarikan, -3);
            $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '001';
        }

        return $prefix . $newNumber;
    }

    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            'pending' => 'Menunggu',
            'disetujui' => 'Disetujui',
            'ditolak' => 'Ditolak',
            default => ucfirst($this->status),
        };
    }

    public function diprosesoleh()
    {
    return $this->belongsTo(User::class, 'diproses_oleh');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}