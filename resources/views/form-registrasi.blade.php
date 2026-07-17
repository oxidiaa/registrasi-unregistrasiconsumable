@extends('layouts.app')

@section('title', 'Form Pendaftaran Barang Consumable')

@section('content')
<div class="header">
    <div class="header-title">
        <h1>Form Pendaftaran Barang</h1>
        <p>Form pendaftaran barang consumable PT. Metalart Astra Indonesia.</p>
    </div>
    <div style="display: flex; gap: 0.75rem;">
        <button class="btn btn-primary" onclick="openModal('addItemModal')">
            <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2.5" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <line x1="12" y1="5" x2="12" y2="19"></line>
                <line x1="5" y1="12" x2="19" y2="12"></line>
            </svg>
            Tambah Data
        </button>
        <button class="btn btn-secondary" onclick="window.print()">
            <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="6 9 6 2 18 2 18 9"></polyline>
                <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
                <rect x="6" y="14" width="12" height="8"></rect>
            </svg>
            Cetak / Print
        </button>
    </div>
</div>

<div class="glass-card form-reg-card">

    {{-- ===== FORM HEADER ===== --}}
    <div class="form-reg-header">
        <div class="form-reg-header-left">
            <div class="form-reg-logo">
                <svg viewBox="0 0 60 40" width="60" height="40">
                    <rect width="60" height="40" rx="4" fill="#e11d48"/>
                    <text x="30" y="26" text-anchor="middle" fill="white" font-weight="bold" font-size="14" font-family="Arial">MAI</text>
                </svg>
                <div class="form-reg-company">
                    <strong>PT. METALART ASTRA INDONESIA</strong>
                    <span>Kawasan Industri MM2100, Jl. Irian Blok LL-1 No.22, Cikarang Barat,<br>
                    Bekasi, Jawa Barat 17520. Telp: (021) 89983388</span>
                </div>
            </div>
        </div>
        <div class="form-reg-header-center">
            <h2 class="form-reg-title">FORM PENDAFTARAN BARANG CONSUMABLE</h2>
            <p class="form-reg-nodoc">No Doc : W1-CDS-PP-20/F1 Rev 2</p>
        </div>
        <div class="form-reg-header-right"></div>
    </div>

    {{-- ===== META INFO ROW ===== --}}
    <div class="form-reg-meta">
        <div class="form-reg-meta-item">
            <span class="form-reg-meta-label">TANGGAL :</span>
            <div class="form-reg-meta-line"></div>
        </div>
        <div class="form-reg-meta-item">
            <span class="form-reg-meta-label">User Dept / Requestor :</span>
            <div class="form-reg-meta-line"></div>
        </div>
    </div>

    {{-- ===== TABLE ===== --}}
    <div class="form-reg-table-wrap">
        <table class="form-reg-table">
            <thead>
                <tr>
                    <th rowspan="2" class="th-center" style="width:40px;">NO.</th>
                    <th rowspan="2" class="th-center" style="width:110px;">KODE BARANG</th>
                    <th rowspan="2" class="th-center">NAMA BARANG</th>
                    <th rowspan="2" class="th-center" style="width:90px;">HARGA</th>
                    <th rowspan="2" class="th-center" style="width:80px;">ESTIMASI USIA PAKAI</th>
                    <th rowspan="2" class="th-center" style="width:90px;">KATEGORI PENGGUNAAN</th>
                    <th rowspan="2" class="th-center" style="width:90px;">KATEGORI UKURAN</th>
                    <th rowspan="2" class="th-center" style="width:50px;">MIN</th>
                    <th rowspan="2" class="th-center" style="width:65px;">TITIK ORDER</th>
                    <th rowspan="2" class="th-center" style="width:50px;">MAX</th>
                    <th rowspan="2" class="th-center" style="width:75px;">LEAD TIME</th>
                    <th colspan="2" class="th-center">KATEGORI</th>
                    <th rowspan="2" class="th-center no-print" style="width:50px;"></th>
                </tr>
                <tr>
                    <th class="th-center" style="width:45px;">B3</th>
                    <th class="th-center" style="width:55px;">NON B3</th>
                </tr>
            </thead>
            <tbody>
                @forelse($formItems as $index => $item)
                <tr class="{{ $index % 2 != 0 ? 'tr-even' : '' }}">
                    <td class="td-center td-no">{{ $index + 1 }}</td>
                    <td class="td-center" style="font-size:0.72rem; font-weight:600; color:var(--color-primary); padding:0 0.4rem;">
                        {{ $item->kode_barang ?? '-' }}
                    </td>
                    <td style="padding:0 0.6rem; font-weight:600; font-size:0.8rem;">
                        {{ $item->nama_barang }}
                    </td>
                    <td class="td-center" style="font-size:0.75rem;">
                        {{ $item->harga ? 'Rp ' . number_format($item->harga, 0, ',', '.') : '-' }}
                    </td>
                    <td class="td-center" style="font-size:0.75rem;">{{ $item->estimasi_usia_pakai ?? '-' }}</td>
                    <td class="td-center" style="font-size:0.75rem;">{{ $item->kategori_penggunaan ?? '-' }}</td>
                    <td class="td-center" style="font-size:0.75rem;">{{ $item->kategori_ukuran ?? '-' }}</td>
                    <td class="td-center" style="font-size:0.75rem;">{{ $item->min ?? '-' }}</td>
                    <td class="td-center" style="font-size:0.75rem;">{{ $item->titik_order ?? '-' }}</td>
                    <td class="td-center" style="font-size:0.75rem;">{{ $item->max ?? '-' }}</td>
                    <td class="td-center" style="font-size:0.75rem;">{{ $item->lead_time ?? '-' }}</td>
                    <td class="td-center" style="font-size:0.85rem;">
                        @if($item->is_b3)
                            <svg viewBox="0 0 24 24" width="14" height="14" stroke="var(--color-success)" stroke-width="3" fill="none"><polyline points="20 6 9 17 4 12"></polyline></svg>
                        @endif
                    </td>
                    <td class="td-center" style="font-size:0.85rem;">
                        @if($item->is_non_b3)
                            <svg viewBox="0 0 24 24" width="14" height="14" stroke="var(--color-success)" stroke-width="3" fill="none"><polyline points="20 6 9 17 4 12"></polyline></svg>
                        @endif
                    </td>
                    <td class="td-center no-print">
                        <form action="{{ route('form-registrasi.delete', $item->id) }}" method="POST"
                              onsubmit="return confirm('Hapus data ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-icon-danger" title="Hapus">
                                <svg viewBox="0 0 24 24" width="14" height="14" stroke="currentColor" stroke-width="2.5" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="3 6 5 6 21 6"></polyline>
                                    <path d="M19 6l-1 14H6L5 6"></path>
                                    <path d="M10 11v6M14 11v6"></path>
                                    <path d="M9 6V4h6v2"></path>
                                </svg>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="14" style="text-align:center; padding:3rem 1rem; color:var(--text-muted);">
                        <div style="display:flex; flex-direction:column; align-items:center; gap:0.75rem;">
                            <svg viewBox="0 0 24 24" width="40" height="40" stroke="#94a3b8" stroke-width="1.5" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                <polyline points="14 2 14 8 20 8"></polyline>
                            </svg>
                            <span style="font-weight:600; font-size:0.9rem; color:var(--text-primary);">Belum ada data</span>
                            <span style="font-size:0.8rem;">Klik tombol <strong>Tambah Data</strong> untuk mengisi form.</span>
                        </div>
                    </td>
                </tr>
                @endforelse

                {{-- Baris kosong sisa (minimal tampilkan 13 baris total) --}}
                @if($formItems->count() < 13)
                    @for($i = $formItems->count(); $i < 13; $i++)
                    <tr class="{{ $i % 2 != 0 ? 'tr-even' : '' }}">
                        <td class="td-center td-no" style="color:#cbd5e1;">{{ $i + 1 }}</td>
                        <td class="td-input"></td>
                        <td class="td-input"></td>
                        <td class="td-input"></td>
                        <td class="td-input"></td>
                        <td class="td-input"></td>
                        <td class="td-input"></td>
                        <td class="td-input"></td>
                        <td class="td-input"></td>
                        <td class="td-input"></td>
                        <td class="td-input"></td>
                        <td class="td-input"></td>
                        <td class="td-input"></td>
                        <td class="td-input no-print"></td>
                    </tr>
                    @endfor
                @endif
            </tbody>
        </table>
    </div>

    {{-- ===== SIGNATURE ===== --}}
    <div class="form-reg-signature">
        <div class="sig-box">
            <div class="sig-label">Dibuat Oleh</div>
            <div class="sig-space"></div>
            <div class="sig-line"></div>
        </div>
        <div class="sig-box">
            <div class="sig-label">Diperiksa Oleh</div>
            <div class="sig-space"></div>
            <div class="sig-line"></div>
        </div>
        <div class="sig-box">
            <div class="sig-label">Disetujui Oleh</div>
            <div class="sig-space"></div>
            <div class="sig-line"></div>
        </div>
    </div>

</div>

{{-- ===== MODAL: TAMBAH DATA ===== --}}
<div class="modal" id="addItemModal">
    <div class="modal-content" style="max-width: 680px; max-height: 90vh; overflow-y: auto;">
        <div class="modal-header">
            <h3>Tambah Data Barang</h3>
            <button class="btn-close" onclick="closeModal('addItemModal')">&times;</button>
        </div>

        <form action="{{ route('form-registrasi.store') }}" method="POST">
            @csrf

            {{-- Row 1: Kode & Nama --}}
            <div style="display:grid; grid-template-columns:1fr 2fr; gap:1rem; margin-bottom:1rem;">
                <div class="form-group" style="margin-bottom:0;">
                    <label for="fi_kode">Kode Barang</label>
                    <input type="text" id="fi_kode" name="kode_barang" class="form-control" placeholder="Cth: CDS-001" value="{{ old('kode_barang') }}">
                </div>
                <div class="form-group" style="margin-bottom:0;">
                    <label for="fi_nama">Nama Barang <span style="color:var(--color-danger);">*</span></label>
                    <input type="text" id="fi_nama" name="nama_barang" class="form-control @error('nama_barang') is-invalid @enderror" placeholder="Nama barang consumable" value="{{ old('nama_barang') }}" required>
                    @error('nama_barang')<div class="error-text">{{ $message }}</div>@enderror
                </div>
            </div>

            {{-- Row 2: Harga & Estimasi Usia --}}
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem; margin-bottom:1rem;">
                <div class="form-group" style="margin-bottom:0;">
                    <label for="fi_harga">Harga (Rp)</label>
                    <input type="number" id="fi_harga" name="harga" class="form-control" placeholder="0" min="0" value="{{ old('harga') }}">
                </div>
                <div class="form-group" style="margin-bottom:0;">
                    <label for="fi_usia">Estimasi Usia Pakai</label>
                    <input type="text" id="fi_usia" name="estimasi_usia_pakai" class="form-control" placeholder="Cth: 6 Bulan, 1 Tahun" value="{{ old('estimasi_usia_pakai') }}">
                </div>
            </div>

            {{-- Row 3: Kategori Penggunaan & Ukuran --}}
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem; margin-bottom:1rem;">
                <div class="form-group" style="margin-bottom:0;">
                    <label for="fi_katpenggunaan">Kategori Penggunaan</label>
                    <input type="text" id="fi_katpenggunaan" name="kategori_penggunaan" class="form-control" placeholder="Cth: Produksi, Kantor" value="{{ old('kategori_penggunaan') }}">
                </div>
                <div class="form-group" style="margin-bottom:0;">
                    <label for="fi_katukuran">Kategori Ukuran</label>
                    <input type="text" id="fi_katukuran" name="kategori_ukuran" class="form-control" placeholder="Cth: Kecil, Sedang, Besar" value="{{ old('kategori_ukuran') }}">
                </div>
            </div>

            {{-- Row 4: Min, Titik Order, Max, Lead Time --}}
            <div style="display:grid; grid-template-columns:1fr 1fr 1fr 1fr; gap:1rem; margin-bottom:1rem;">
                <div class="form-group" style="margin-bottom:0;">
                    <label for="fi_min">Min</label>
                    <input type="number" id="fi_min" name="min" class="form-control" placeholder="0" min="0" value="{{ old('min') }}">
                </div>
                <div class="form-group" style="margin-bottom:0;">
                    <label for="fi_titik">Titik Order</label>
                    <input type="number" id="fi_titik" name="titik_order" class="form-control" placeholder="0" min="0" value="{{ old('titik_order') }}">
                </div>
                <div class="form-group" style="margin-bottom:0;">
                    <label for="fi_max">Max</label>
                    <input type="number" id="fi_max" name="max" class="form-control" placeholder="0" min="0" value="{{ old('max') }}">
                </div>
                <div class="form-group" style="margin-bottom:0;">
                    <label for="fi_lead">Lead Time</label>
                    <input type="text" id="fi_lead" name="lead_time" class="form-control" placeholder="Cth: 3 Hari" value="{{ old('lead_time') }}">
                </div>
            </div>

            {{-- Row 5: Kategori B3 / NON B3 --}}
            <div style="margin-bottom:1.5rem;">
                <label style="display:block; font-size:0.875rem; font-weight:600; margin-bottom:0.75rem;">Kategori</label>
                <div style="display:flex; gap:2rem;">
                    <label class="fi-checkbox-label">
                        <input type="checkbox" name="is_b3" value="1" class="fi-checkbox" {{ old('is_b3') ? 'checked' : '' }}>
                        <span class="fi-checkbox-custom"></span>
                        <span>B3 (Bahan Berbahaya Beracun)</span>
                    </label>
                    <label class="fi-checkbox-label">
                        <input type="checkbox" name="is_non_b3" value="1" class="fi-checkbox" {{ old('is_non_b3') ? 'checked' : '' }}>
                        <span class="fi-checkbox-custom"></span>
                        <span>NON B3</span>
                    </label>
                </div>
            </div>

            <div style="display:flex; gap:0.75rem; justify-content:flex-end;">
                <button type="button" class="btn btn-secondary" onclick="closeModal('addItemModal')">Batal</button>
                <button type="submit" class="btn btn-primary">
                    <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2.5" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                        <polyline points="17 21 17 13 7 13 7 21"></polyline>
                        <polyline points="7 3 7 8 15 8"></polyline>
                    </svg>
                    Simpan Data
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@section('scripts')
<script>
    function openModal(id) {
        document.getElementById(id).classList.add('show');
    }
    function closeModal(id) {
        document.getElementById(id).classList.remove('show');
    }
    window.onclick = function(e) {
        if (e.target.classList.contains('modal')) {
            e.target.classList.remove('show');
        }
    }

    // Auto-open modal jika ada validation error dari form tambah data
    @if($errors->any())
        document.addEventListener('DOMContentLoaded', function() {
            openModal('addItemModal');
        });
    @endif
</script>
@endsection
