@extends('layouts.app')

@section('title', 'Daftar Buku')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>
            <i class="bi bi-book"></i>
            Daftar Buku
        </h1>
        <div class="d-flex gap-2">
            <button type="button" id="btn-bulk-delete" class="btn btn-danger d-none" onclick="konfirmasiBulkDelete()">
                <i class="bi bi-trash"></i>
                Hapus Terpilih (<span id="jumlah-terpilih">0</span>)
            </button>

            <a href="{{ route('buku.export') }}" class="btn btn-success">
                <i class="bi bi-file-earmark-spreadsheet"></i> Export CSV
            </a>

            <a href="{{ route('buku.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Tambah Buku
            </a>
        </div>
    </div>

    {{-- Statistik Cards --}}
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card border-primary">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Total Buku</h6>
                            <h2 class="mb-0">{{ $totalBuku }}</h2>
                        </div>
                        <div class="text-primary">
                            <i class="bi bi-book-fill" style="font-size: 3rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-success">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Buku Tersedia</h6>
                            <h2 class="mb-0">{{ $bukuTersedia }}</h2>
                        </div>
                        <div class="text-success">
                            <i class="bi bi-check-circle-fill" style="font-size: 3rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-danger">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Buku Habis</h6>
                            <h2 class="mb-0">{{ $bukuHabis }}</h2>
                        </div>
                        <div class="text-danger">
                            <i class="bi bi-x-circle-fill" style="font-size: 3rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Filter Kategori --}}
    <div class="card mb-4">
        <div class="card-body">
            <h6 class="card-title">
                <i class="bi bi-funnel"></i> Filter Kategori:
            </h6>
            <div class="btn-group" role="group">
                <a href="{{ route('buku.index') }}"
                    class="btn btn-sm {{ !isset($kategori) ? 'btn-primary' : 'btn-outline-primary' }}">
                    Semua
                </a>
                <a href="{{ route('buku.kategori', 'Programming') }}"
                    class="btn btn-sm {{ isset($kategori) && $kategori == 'Programming' ? 'btn-primary' : 'btn-outline-primary' }}">
                    Programming
                </a>
                <a href="{{ route('buku.kategori', 'Database') }}"
                    class="btn btn-sm {{ isset($kategori) && $kategori == 'Database' ? 'btn-primary' : 'btn-outline-primary' }}">
                    Database
                </a>
                <a href="{{ route('buku.kategori', 'Web Design') }}"
                    class="btn btn-sm {{ isset($kategori) && $kategori == 'Web Design' ? 'btn-primary' : 'btn-outline-primary' }}">
                    Web Design
                </a>
                <a href="{{ route('buku.kategori', 'Networking') }}"
                    class="btn btn-sm {{ isset($kategori) && $kategori == 'Networking' ? 'btn-primary' : 'btn-outline-primary' }}">
                    Networking
                </a>
                <a href="{{ route('buku.kategori', 'Data Science') }}"
                    class="btn btn-sm {{ isset($kategori) && $kategori == 'Data Science' ? 'btn-primary' : 'btn-outline-primary' }}">
                    Data Science
                </a>
            </div>
        </div>
    </div>

    {{-- Form Search & Filter Advanced --}}
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-light">
            <h6 class="mb-0"><i class="bi bi-search"></i> Pencarian & Filter Advanced</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('buku.search') }}" method="GET">
                <div class="row g-2">
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Keyword</label>
                        <input type="text" name="keyword" class="form-control form-control-sm"
                            placeholder="Cari judul, pengarang, penerbit..." value="{{ request('keyword') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small fw-semibold">Kategori</label>
                        <select name="kategori" class="form-select form-select-sm">
                            <option value="">Semua Kategori</option>
                            @isset($kategoriList)
                                @foreach ($kategoriList as $kat)
                                    <option value="{{ $kat }}" {{ request('kategori') == $kat ? 'selected' : '' }}>
                                        {{ $kat }}
                                    </option>
                                @endforeach
                            @endisset
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small fw-semibold">Tahun</label>
                        <select name="tahun" class="form-select form-select-sm">
                            <option value="">Semua Tahun</option>
                            @isset($tahunList)
                                @foreach ($tahunList as $thn)
                                    <option value="{{ $thn }}" {{ request('tahun') == $thn ? 'selected' : '' }}>
                                        {{ $thn }}
                                    </option>
                                @endforeach
                            @endisset
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small fw-semibold">Ketersediaan</label>
                        <select name="ketersediaan" class="form-select form-select-sm">
                            <option value="">Semua</option>
                            <option value="tersedia" {{ request('ketersediaan') == 'tersedia' ? 'selected' : '' }}>
                                Tersedia
                            </option>
                            <option value="habis" {{ request('ketersediaan') == 'habis' ? 'selected' : '' }}>
                                Habis
                            </option>
                        </select>
                    </div>
                    <div class="col-md-2 d-flex align-items-end gap-2">
                        <button type="submit" class="btn btn-primary btn-sm w-100">
                            <i class="bi bi-search"></i> Cari
                        </button>
                        <a href="{{ route('buku.index') }}" class="btn btn-outline-secondary btn-sm w-100">
                            <i class="bi bi-x-circle"></i> Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Header Grid + Pilih Semua --}}
    <div class="d-flex justify-content-between align-items-center mt-5 mb-3">
        <h5 class="text-muted mb-0">
            <i class="bi bi-grid-3x3-gap"></i> Tampilan Grid (BukuCard Component)
        </h5>
        <label for="select-all" class="d-flex align-items-center gap-2 mb-0" style="cursor: pointer;">
            <input class="form-check-input m-0" type="checkbox" id="select-all"
                style="width: 1.1rem; height: 1.1rem; cursor: pointer;">
            <span class="text-muted small">Pilih Semua</span>
        </label>
    </div>

    {{-- Form Bulk Delete — DILUAR grid, tidak membungkus buku-card --}}
    <form id="form-bulk-delete" action="{{ route('buku.bulk-delete') }}" method="POST">
        @csrf
        {{-- Checkbox ID akan diisi oleh JavaScript saat submit --}}
        <div id="hidden-ids"></div>
    </form>

    {{-- Grid Buku — TERPISAH dari form bulk delete --}}
    <div class="row">
        @forelse($bukus as $buku)
            <div class="col-md-4 col-lg-3 mb-4">
                <div class="position-relative">

                    {{-- Checkbox pill pojok kiri atas --}}
                    <label for="buku-{{ $buku->id }}"
                        class="position-absolute top-0 start-0 z-3 m-2
                           d-flex align-items-center gap-1
                           bg-white rounded-pill px-2 py-1 shadow-sm
                           text-muted small"
                        style="cursor: pointer; border: 1px solid #dee2e6;">
                        <input class="form-check-input checkbox-buku m-0" type="checkbox" data-id="{{ $buku->id }}"
                            id="buku-{{ $buku->id }}" style="width: 1rem; height: 1rem; cursor: pointer;">
                        <span>Pilih</span>
                    </label>

                    <x-buku-card :buku="$buku" />
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info">Tidak ada buku untuk ditampilkan.</div>
            </div>
        @endforelse
    </div>

    @if ($bukus->count() > 0)
        <div class="text-center mt-4">
            <p class="text-muted">
                Menampilkan {{ $bukus->count() }} buku
                @isset($kategori)
                    dari kategori <strong>{{ $kategori }}</strong>
                @endisset
            </p>
        </div>
    @endif

@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const selectAll = document.getElementById('select-all');

            // ===== SELECT ALL =====
            selectAll.addEventListener('change', function() {
                document.querySelectorAll('.checkbox-buku').forEach(cb => {
                    cb.checked = this.checked;
                });
                updateJumlahTerpilih();
            });

            // ===== PANTAU SETIAP CHECKBOX BUKU =====
            document.querySelectorAll('.checkbox-buku').forEach(cb => {
                cb.addEventListener('change', function() {
                    const adaYangTidakCentang = document.querySelectorAll(
                        '.checkbox-buku:not(:checked)').length;
                    selectAll.checked = adaYangTidakCentang === 0;
                    updateJumlahTerpilih();
                });
            });

            // ===== UPDATE COUNTER & TOMBOL =====
            function updateJumlahTerpilih() {
                const terpilih = document.querySelectorAll('.checkbox-buku:checked').length;
                document.getElementById('jumlah-terpilih').textContent = terpilih;

                const btnBulk = document.getElementById('btn-bulk-delete');
                if (terpilih > 0) {
                    btnBulk.classList.remove('d-none');
                } else {
                    btnBulk.classList.add('d-none');
                }
            }

            // ===== KONFIRMASI BULK DELETE =====
            window.konfirmasiBulkDelete = function() {
                const jumlah = document.querySelectorAll('.checkbox-buku:checked').length;

                Swal.fire({
                    title: 'Konfirmasi Hapus',
                    text: `Anda akan menghapus ${jumlah} buku sekaligus. Tindakan ini tidak bisa dibatalkan!`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Hapus Semua!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {

                        // Ambil semua ID yang dicentang
                        const hiddenIds = document.getElementById('hidden-ids');
                        hiddenIds.innerHTML = ''; // kosongkan dulu

                        // Masukkan ID ke dalam form sebagai hidden input
                        document.querySelectorAll('.checkbox-buku:checked').forEach(cb => {
                            const input = document.createElement('input');
                            input.type = 'hidden';
                            input.name = 'buku_ids[]';
                            input.value = cb.dataset.id;
                            hiddenIds.appendChild(input);
                        });

                        // Submit form
                        document.getElementById('form-bulk-delete').submit();
                    }
                });
            }

        });
    </script>
@endpush
