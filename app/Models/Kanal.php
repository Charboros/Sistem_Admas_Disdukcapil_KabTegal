<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kanal extends Model
{
    protected $fillable = ['nama'];

    public static function getList(): array
    {
        $kanals = self::pluck('nama')->sort()->values()->toArray();
        $kanals = array_filter($kanals, fn($k) => strtolower($k) !== 'lainnya');
        $kanals[] = 'Lainnya';
        return array_values($kanals);
    }
}
