<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class KodeBukuFormat implements ValidationRule
{
    /**
     * Menjalankan validasi.
     * Format yang diizinkan: BK-XX-000 sampai BK-XXXX-000
     * Contoh valid: BK-PROG-001, BK-DB-002, BK-WD-010
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Cek apakah format kode buku sesuai pola: BK-[2-4 huruf kapital]-[3 digit angka]
        if (!preg_match('/^BK-[A-Z]{2,4}-\d{3}$/', $value)) {
            $fail('Format kode buku tidak valid. Contoh yang benar: BK-PROG-001 atau BK-DB-002');
        }
    }
}
