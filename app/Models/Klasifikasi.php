<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Klasifikasi extends Model
{
    protected $fillable = ['nama'];

    public static function getList(): array
    {
        $klasifikasis = self::pluck('nama')->sort()->values()->toArray();
        $klasifikasis = array_filter($klasifikasis, fn($k) => strtolower($k) !== 'lainnya');
        $klasifikasis[] = 'Lainnya';
        return array_values($klasifikasis);
    }
}
