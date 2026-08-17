<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Institution extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'code',
        'type',
        'description',
        'website',
        'logo_path',
        'is_verified',
    ];

    protected $casts = [
        'is_verified' => 'boolean',
    ];

    /**
     * Relasi ke seluruh User (PIC / Mentor) dari institusi ini.
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Helper otomatis untuk menghasilkan kode inisial institusi yang unik dan bebas bentrok.
     */
    public static function generateUniqueCode(string $name, ?int $ignoreId = null): string
    {
        // 1. Bersihkan awalan/akhiran umum badan usaha (PT, CV, Tbk, Yayasan, Ltd, Corp)
        $cleanName = preg_replace('/^(pt\.?|cv\.?|ud\.?|yayasan|firma)\s+/i', '', trim($name));
        $cleanName = preg_replace('/\s+(tbk\.?|inc\.?|corp\.?|ltd\.?)$/i', '', trim($cleanName));
        $cleanName = trim(preg_replace('/[^a-zA-Z0-9\s]/', '', $cleanName));

        $words = array_values(array_filter(explode(' ', $cleanName)));
        $baseCode = '';

        if (empty($words)) {
            $baseCode = 'VNDR';
        } elseif (count($words) === 1) {
            $word = strtoupper($words[0]);
            // Ambil 4 karakter pertama
            $baseCode = substr($word, 0, 4);
            if (strlen($baseCode) < 3) {
                $baseCode = str_pad($baseCode, 3, 'X');
            }
        } elseif (count($words) === 2) {
            // 2 huruf kata 1 + 2 huruf kata 2
            $w1 = strtoupper(substr($words[0], 0, 2));
            $w2 = strtoupper(substr($words[1], 0, 2));
            $baseCode = $w1 . $w2;
        } else {
            // Akronim huruf depan dari setiap kata (maks 4 karakter)
            foreach ($words as $w) {
                if (!empty($w)) {
                    $baseCode .= strtoupper(substr($w, 0, 1));
                }
                if (strlen($baseCode) >= 4) {
                    break;
                }
            }
            if (strlen($baseCode) < 3) {
                $baseCode = str_pad($baseCode, 3, 'X');
            }
        }

        $baseCode = strtoupper($baseCode);
        $candidateCode = $baseCode;
        $counter = 1;

        // 2. Periksa apakah kode sudah ada di database; jika ada, tambahkan suffix sequence unik
        while (static::where('code', $candidateCode)
            ->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))
            ->exists()) {
            $counter++;
            $candidateCode = sprintf('%s%02d', substr($baseCode, 0, 4), $counter);
        }

        return $candidateCode;
    }
}
