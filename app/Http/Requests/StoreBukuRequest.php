<?php

namespace App\Http\Requests;

use App\Rules\KodeBukuFormat; // ← TAMBAHAN
use Illuminate\Foundation\Http\FormRequest;

class StoreBukuRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'kode_buku'    => ['required', 'string', 'max:20', 'unique:buku,kode_buku', new KodeBukuFormat], // ← UBAH
            'judul'        => 'required|string|max:200',
            'kategori'     => 'required|in:Programming,Database,Web Design,Networking,Data Science',
            'pengarang'    => 'required|string|max:100',
            'penerbit'     => 'required|string|max:100',
            'tahun_terbit' => 'required|integer|min:1900|max:' . date('Y'),
            'isbn'         => 'nullable|string|max:20',
            'harga'        => 'required|numeric|min:0',
            'stok'         => ['required', 'integer', 'min:0', $this->getStokMaxRule()], // ← UBAH
            'deskripsi'    => 'nullable|string',
            'bahasa'       => ['required', 'string', 'max:20', $this->getBahasaRule()],  // ← UBAH
        ];
    }

    public function messages(): array
    {
        return [
            'kode_buku.required'    => 'Kode buku wajib diisi.',
            'kode_buku.unique'      => 'Kode buku sudah digunakan.',
            'kode_buku.max'         => 'Kode buku maksimal 20 karakter.',
            'judul.required'        => 'Judul buku wajib diisi.',
            'judul.max'             => 'Judul buku maksimal 200 karakter.',
            'kategori.required'     => 'Kategori wajib dipilih.',
            'kategori.in'           => 'Kategori tidak valid.',
            'pengarang.required'    => 'Nama pengarang wajib diisi.',
            'penerbit.required'     => 'Nama penerbit wajib diisi.',
            'tahun_terbit.required' => 'Tahun terbit wajib diisi.',
            'tahun_terbit.integer'  => 'Tahun terbit harus berupa angka.',
            'tahun_terbit.min'      => 'Tahun terbit tidak valid.',
            'tahun_terbit.max'      => 'Tahun terbit tidak boleh melebihi tahun sekarang.',
            'isbn.max'              => 'ISBN maksimal 20 karakter.',
            'harga.required'        => 'Harga buku wajib diisi.',
            'harga.numeric'         => 'Harga harus berupa angka.',
            'harga.min'             => 'Harga tidak boleh negatif.',
            'stok.required'         => 'Stok wajib diisi.',
            'stok.integer'          => 'Stok harus berupa angka bulat.',
            'stok.min'              => 'Stok tidak boleh negatif.',
            'stok.max'              => 'Buku terbitan sebelum tahun 2000 stoknya maksimal 5.', // ← TAMBAH
            'bahasa.required'       => 'Bahasa wajib diisi.',
            'bahasa.in'             => 'Buku kategori Programming wajib berbahasa Inggris.',   // ← TAMBAH
        ];
    }

    public function attributes(): array
    {
        return [
            'kode_buku'    => 'kode buku',
            'judul'        => 'judul buku',
            'kategori'     => 'kategori',
            'pengarang'    => 'nama pengarang',
            'penerbit'     => 'nama penerbit',
            'tahun_terbit' => 'tahun terbit',
            'isbn'         => 'ISBN',
            'harga'        => 'harga',
            'stok'         => 'stok',
            'bahasa'       => 'bahasa',
        ];
    }

    // ↓ TAMBAH 2 METHOD 
    private function getStokMaxRule(): string
    {
        if ($this->tahun_terbit && (int)$this->tahun_terbit < 2000) {
            return 'max:5';
        }
        return 'max:99999';
    }

    private function getBahasaRule(): string
    {
        if ($this->kategori === 'Programming') {
            return 'in:Inggris';
        }
        return 'in:Indonesia,Inggris';
    }
}
