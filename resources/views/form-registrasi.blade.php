@extends('layouts.app')

@section('title', 'Form Pendaftaran Barang Consumable')

@section('content')
<style>
    /* Sheet Tabs Styling */
    .sheet-tabs-container {
        display: flex;
        gap: 0.5rem;
        margin-bottom: 1.5rem;
        border-bottom: 2px solid rgba(0, 0, 0, 0.07);
        padding-bottom: 1px;
    }

    .sheet-tab {
        padding: 0.75rem 1.5rem;
        font-family: var(--font-heading);
        font-weight: 600;
        font-size: 0.95rem;
        color: var(--text-muted);
        background: transparent;
        border: none;
        cursor: pointer;
        border-radius: var(--radius-sm) var(--radius-sm) 0 0;
        transition: var(--transition-smooth);
        position: relative;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .sheet-tab:hover {
        color: var(--color-primary);
        background: rgba(79, 70, 229, 0.04);
    }

    .sheet-tab.active {
        color: var(--color-primary);
        background: #fff;
        box-shadow: 0 -4px 10px -4px rgba(0,0,0,0.05);
    }

    .sheet-tab.active::after {
        content: '';
        position: absolute;
        bottom: -3px;
        left: 0;
        width: 100%;
        height: 3px;
        background-color: var(--color-primary);
        border-radius: 2px;
    }

    /* Tab panes */
    .tab-pane {
        display: none;
    }

    .tab-pane.active {
        display: block;
    }

    /* Data View Table & Stats */
    .dataview-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 1.25rem;
        margin-bottom: 1.75rem;
    }

    .dataview-stat-card {
        background: var(--bg-glass);
        backdrop-filter: blur(12px);
        border: 1px solid var(--border-glass);
        padding: 1.25rem;
        border-radius: var(--radius-md);
        display: flex;
        align-items: center;
        gap: 1rem;
        box-shadow: var(--shadow-sm);
        transition: var(--transition-smooth);
    }
    
    .dataview-stat-card:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }

    /* Timeline/Stepper styles */
    .stepper-container {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
        position: relative;
        padding-left: 2rem;
        margin-top: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .stepper-container::before {
        content: '';
        position: absolute;
        left: 11px;
        top: 12px;
        bottom: 12px;
        width: 2px;
        background: #e2e8f0;
        z-index: 1;
    }

    .step-item {
        position: relative;
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
        padding-bottom: 1.5rem;
    }
    
    .step-item:last-child {
        padding-bottom: 0;
    }

    .step-indicator {
        position: absolute;
        left: -32px;
        top: 0;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        background: #fff;
        border: 2px solid #cbd5e1;
        z-index: 2;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: var(--transition-smooth);
        color: #94a3b8;
        font-size: 0.75rem;
        font-weight: 700;
    }

    .step-item.completed .step-indicator {
        border-color: var(--color-success);
        background: var(--color-success);
        color: #fff;
    }

    .step-item.active .step-indicator {
        border-color: var(--color-primary);
        background: #fff;
        color: var(--color-primary);
        box-shadow: 0 0 0 4px var(--color-primary-light);
    }

    .step-item.pending .step-indicator {
        border-color: #cbd5e1;
        background: #f1f5f9;
        color: #94a3b8;
    }
    
    /* Print optimizations for sheets tabs */
    @media print {
        .sheet-tabs-container {
            display: none !important;
        }
        #btn-tambah-data {
            display: none !important;
        }
    }
</style>

<div class="header no-print">
    <div class="header-title">
        <h1>Form Pendaftaran Barang</h1>
        <p>Form pendaftaran barang consumable PT. Metalart Astra Indonesia.</p>
    </div>
    <div style="display: flex; gap: 0.75rem;">
        <button class="btn btn-outline" id="btn-form-baru" onclick="createNewForm()" style="border: 1.5px solid var(--color-primary); color: var(--color-primary); background: transparent; font-weight: 600; font-family: var(--font-body); display: inline-flex; align-items: center; gap: 0.4rem; padding: 0.6rem 1rem; border-radius: var(--radius-md); cursor: pointer;">
            <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2.5" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                <polyline points="14 2 14 8 20 8"></polyline>
                <line x1="12" y1="18" x2="12" y2="12"></line>
                <line x1="9" y1="15" x2="15" y2="15"></line>
            </svg>
            + Form Baru
        </button>
        <button class="btn btn-primary" id="btn-tambah-data" onclick="openModal('addItemModal')">
            <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2.5" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <line x1="12" y1="5" x2="12" y2="19"></line>
                <line x1="5" y1="12" x2="19" y2="12"></line>
            </svg>
            Tambah Data
        </button>
        <button class="btn btn-secondary" onclick="printCurrentSheet()">
            <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="6 9 6 2 18 2 18 9"></polyline>
                <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
                <rect x="6" y="14" width="12" height="8"></rect>
            </svg>
            Cetak / Print
        </button>
    </div>
</div>

{{-- ===== SHEET TABS SELECTOR ===== --}}
<div class="sheet-tabs-container no-print">
    <button class="sheet-tab active" onclick="switchSheet('print-preview')">
        <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round">
            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
            <polyline points="14 2 14 8 20 8"></polyline>
            <line x1="16" y1="13" x2="8" y2="13"></line>
            <line x1="16" y1="17" x2="8" y2="17"></line>
            <polyline points="10 9 9 9 8 9"></polyline>
        </svg>
        Print Preview
    </button>
    <button class="sheet-tab" onclick="switchSheet('data-view')">
        <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round">
            <line x1="8" y1="6" x2="21" y2="6"></line>
            <line x1="8" y1="12" x2="21" y2="12"></line>
            <line x1="8" y1="18" x2="21" y2="18"></line>
            <line x1="3" y1="6" x2="3.01" y2="6"></line>
            <line x1="3" y1="12" x2="3.01" y2="12"></line>
            <line x1="3" y1="18" x2="3.01" y2="18"></line>
        </svg>
        Data View
    </button>
    <button class="sheet-tab" onclick="switchSheet('info')">
        <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="10"></circle>
            <line x1="12" y1="16" x2="12" y2="12"></line>
            <line x1="12" y1="8" x2="12.01" y2="8"></line>
        </svg>
        Info
    </button>
    <button class="sheet-tab" onclick="switchSheet('account-master')">
        <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round">
            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
            <circle cx="9" cy="7" r="4"></circle>
            <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
        </svg>
        Account Master
    </button>
</div>

{{-- ===== TAB PANE: PRINT PREVIEW ===== --}}
<div id="print-preview-pane" class="tab-pane active">
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
                        <span>Kawasan Industri KIIC, JL. Harapan III Lot- JJ 2A, Desa Sirnabaya, Kecamatan
                            Teluk Jambe Timur, Karawang 41631 Jawa Barat,<br>
                        Telp : (021) 29369960, (0267) 78639862, Fax : (021) 29369965</span>
                    </div>
                </div>
            </div>
            <div class="form-reg-header-center">
                <h2 class="form-reg-title">FORM PENDAFTARAN BARANG CONSUMABLE</h2>
                <p class="form-reg-nodoc" id="preview-docno">No Doc : W1-CDS-PP-20/F1 Rev 2 &nbsp;|&nbsp; No. Form: <span id="form-number-display" style="font-weight: 700; color: var(--color-primary);">01/PR/{{ date('d-m-Y') }}</span></p>
            </div>
            <div class="form-reg-header-right"></div>
        </div>

        {{-- ===== META INFO ROW ===== --}}
        <div class="form-reg-meta">
            <div class="form-reg-meta-item">
                <span class="form-reg-meta-label">TANGGAL :</span>
                <div class="form-reg-meta-line" id="preview-date">{{ date('d-m-Y') }}</div>
            </div>
            <div class="form-reg-meta-item">
                <span class="form-reg-meta-label">User Dept / Requestor :</span>
                <div class="form-reg-meta-line" id="preview-requestor">{{ Auth::user()->name ?? 'Production User' }} / Production</div>
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
                        <th rowspan="2" class="th-center" style="width:95px;">ASET / NO ASET</th>
                        <th colspan="2" class="th-center">KATEGORI</th>
                        <th rowspan="2" class="th-center no-print" style="width:50px;"></th>
                    </tr>
                    <tr>
                        <th class="th-center" style="width:45px;">B3</th>
                        <th class="th-center" style="width:55px;">NON B3</th>
                    </tr>
                </thead>
                <tbody id="preview-table-body">
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
                        <td class="td-center" style="font-size:0.75rem;">{{ $item->estimasi_usia_pakai ? $item->estimasi_usia_pakai . ' Hari' : '-' }}</td>
                        <td class="td-center" style="font-size:0.75rem;">{{ $item->kategori_penggunaan ?? '-' }}</td>
                        <td class="td-center" style="font-size:0.75rem;">{{ $item->kategori_ukuran ?? '-' }}</td>
                        <td class="td-center" style="font-size:0.75rem;">{{ $item->min ?? '-' }}</td>
                        <td class="td-center" style="font-size:0.75rem;">{{ $item->titik_order ?? '-' }}</td>
                        <td class="td-center" style="font-size:0.75rem;">{{ $item->max ?? '-' }}</td>
                        <td class="td-center" style="font-size:0.75rem; vertical-align: middle;">
                            @if($item->kategori_aset === 'ASET')
                                <span style="background-color: var(--color-success-light); color: var(--color-success); padding: 0.2rem 0.5rem; border-radius: 4px; font-weight: 700; font-size: 0.7rem; display: inline-block;">ASET</span>
                            @else
                                <span style="background-color: #f1f5f9; color: var(--text-muted); padding: 0.2rem 0.5rem; border-radius: 4px; font-weight: 600; font-size: 0.7rem; display: inline-block;">NO ASET</span>
                            @endif
                        </td>
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
                <div class="sig-space" id="preview-sig-dibuat" style="display: flex; flex-direction: column; align-items: center; justify-content: center; height: 64px; font-size: 0.8rem; font-weight: 600;">
                    <span style="color: var(--text-muted); font-size: 0.75rem; font-style: italic;">...................</span>
                </div>
                <div class="sig-line"></div>
            </div>
            <div class="sig-box">
                <div class="sig-label">Diperiksa Oleh</div>
                <div class="sig-space" id="preview-sig-diperiksa" style="display: flex; flex-direction: column; align-items: center; justify-content: center; height: 64px; font-size: 0.8rem; font-weight: 600;">
                    <span style="color: var(--text-muted); font-size: 0.75rem; font-style: italic;">...................</span>
                </div>
                <div class="sig-line"></div>
            </div>
            <div class="sig-box">
                <div class="sig-label">Disetujui Oleh</div>
                <div class="sig-space" id="preview-sig-disetujui" style="display: flex; flex-direction: column; align-items: center; justify-content: center; height: 64px; font-size: 0.8rem; font-weight: 600;">
                    <span style="color: var(--text-muted); font-size: 0.75rem; font-style: italic;">...................</span>
                </div>
                <div class="sig-line"></div>
            </div>
        </div>

    </div>
</div>

{{-- ===== TAB PANE: DATA VIEW ===== --}}
<div id="data-view-pane" class="tab-pane no-print">
    {{-- Stats Cards Row --}}
    <div class="dataview-stats">
        <div class="dataview-stat-card">
            <div style="background-color: var(--color-primary-light); color: var(--color-primary); padding: 0.75rem; border-radius: var(--radius-md); display: flex; align-items: center; justify-content: center;">
                <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2.5" fill="none">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                    <polyline points="14 2 14 8 20 8"></polyline>
                </svg>
            </div>
            <div>
                <span style="font-size: 0.8rem; color: var(--text-muted); font-weight: 500; display: block; text-transform: uppercase; letter-spacing: 0.05em;">Total Checksheet</span>
                <span style="font-size: 1.5rem; font-weight: 800; color: var(--text-primary);">4</span>
            </div>
        </div>
        
        <div class="dataview-stat-card">
            <div style="background-color: var(--color-success-light); color: var(--color-success); padding: 0.75rem; border-radius: var(--radius-md); display: flex; align-items: center; justify-content: center;">
                <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2.5" fill="none">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                </svg>
            </div>
            <div>
                <span style="font-size: 0.8rem; color: var(--text-muted); font-weight: 500; display: block; text-transform: uppercase; letter-spacing: 0.05em;">Selesai (Approved)</span>
                <span style="font-size: 1.5rem; font-weight: 800; color: var(--text-primary);">2</span>
            </div>
        </div>
        
        <div class="dataview-stat-card">
            <div style="background-color: rgba(59, 130, 246, 0.1); color: rgb(29, 78, 216); padding: 0.75rem; border-radius: var(--radius-md); display: flex; align-items: center; justify-content: center;">
                <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2.5" fill="none">
                    <circle cx="12" cy="12" r="10"></circle>
                    <polyline points="12 6 12 12 16 14"></polyline>
                </svg>
            </div>
            <div>
                <span style="font-size: 0.8rem; color: var(--text-muted); font-weight: 500; display: block; text-transform: uppercase; letter-spacing: 0.05em;">Proses Approval</span>
                <span style="font-size: 1.5rem; font-weight: 800; color: var(--text-primary);">1</span>
            </div>
        </div>
    </div>

    {{-- Checksheet list glass container --}}
    <div class="glass-card" style="padding: 1.5rem;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem;">
            <h3 style="font-family: var(--font-heading); font-weight: 700; color: var(--text-primary);">Daftar Form Registrasi (Print Preview)</h3>
        </div>
        
        <div class="form-reg-table-wrap">
            <table class="form-reg-table">
                <thead>
                    <tr>
                        <th class="th-center" style="width: 50px;">NO</th>
                        <th>NO. CHECKSHEET</th>
                        <th>TANGGAL BUAT</th>
                        <th>REQUESTOR / DEPT</th>
                        <th class="th-center">JUMLAH BARANG</th>
                        <th class="th-center">STATUS</th>
                        <th class="th-center" style="width: 200px;">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="td-center td-no">1</td>
                        <td style="font-weight: 700; color: var(--color-primary); padding: 0 0.8rem;">01/PR/{{ date('d-m-Y') }}</td>
                        <td style="padding: 0 0.8rem; font-size: 0.85rem;">{{ date('d-m-Y') }}</td>
                        <td style="padding: 0 0.8rem; font-size: 0.85rem; font-weight: 600;" id="dataview-req-004">{{ Auth::user()->name ?? 'Production User' }} / Production</td>
                        <td class="td-center" style="font-weight: 700;">{{ $formItems->count() }} Item</td>
                        <td class="td-center">
                            <span style="background-color: var(--color-warning-light); color: var(--color-warning); padding: 0.25rem 0.5rem; border-radius: 4px; font-weight: 700; font-size: 0.75rem; display: inline-block;">DRAFT</span>
                        </td>
                        <td class="td-center" style="padding: 0 0.5rem;">
                            <button class="btn btn-primary btn-sm" onclick="viewChecksheet('01/PR/{{ date('d-m-Y') }}')" style="padding: 0.35rem 0.75rem; font-size: 0.75rem; font-family: var(--font-body); font-weight: 600; border-radius: 6px;">Lihat Preview</button>
                            <button class="btn btn-secondary btn-sm" onclick="printChecksheet('01/PR/{{ date('d-m-Y') }}')" style="padding: 0.35rem 0.75rem; font-size: 0.75rem; margin-left: 0.25rem; font-family: var(--font-body); font-weight: 600; border-radius: 6px;">Cetak</button>
                        </td>
                    </tr>
                    <tr>
                        <td class="td-center td-no">2</td>
                        <td style="font-weight: 700; color: var(--color-primary); padding: 0 0.8rem;">02/PR/22-07-2026</td>
                        <td style="padding: 0 0.8rem; font-size: 0.85rem;">22-07-2026</td>
                        <td style="padding: 0 0.8rem; font-size: 0.85rem; font-weight: 600;">Ahmad Fauzi / Production</td>
                        <td class="td-center" style="font-weight: 700;">5 Item</td>
                        <td class="td-center">
                            <span style="background-color: var(--color-success-light); color: var(--color-success); padding: 0.25rem 0.5rem; border-radius: 4px; font-weight: 700; font-size: 0.75rem; display: inline-block;">APPROVED</span>
                        </td>
                        <td class="td-center" style="padding: 0 0.5rem;">
                            <button class="btn btn-primary btn-sm" onclick="viewChecksheet('02/PR/22-07-2026')" style="padding: 0.35rem 0.75rem; font-size: 0.75rem; font-family: var(--font-body); font-weight: 600; border-radius: 6px;">Lihat Preview</button>
                            <button class="btn btn-secondary btn-sm" onclick="printChecksheet('02/PR/22-07-2026')" style="padding: 0.35rem 0.75rem; font-size: 0.75rem; margin-left: 0.25rem; font-family: var(--font-body); font-weight: 600; border-radius: 6px;">Cetak</button>
                        </td>
                    </tr>
                    <tr>
                        <td class="td-center td-no">3</td>
                        <td style="font-weight: 700; color: var(--color-primary); padding: 0 0.8rem;">01/PC/20-07-2026</td>
                        <td style="padding: 0 0.8rem; font-size: 0.85rem;">20-07-2026</td>
                        <td style="padding: 0 0.8rem; font-size: 0.85rem; font-weight: 600;">Siti Rahma / Procurement</td>
                        <td class="td-center" style="font-weight: 700;">3 Item</td>
                        <td class="td-center">
                            <span style="background-color: rgba(59, 130, 246, 0.1); color: rgb(29, 78, 216); padding: 0.25rem 0.5rem; border-radius: 4px; font-weight: 700; font-size: 0.75rem; display: inline-block;">PENDING</span>
                        </td>
                        <td class="td-center" style="padding: 0 0.5rem;">
                            <button class="btn btn-primary btn-sm" onclick="viewChecksheet('01/PC/20-07-2026')" style="padding: 0.35rem 0.75rem; font-size: 0.75rem; font-family: var(--font-body); font-weight: 600; border-radius: 6px;">Lihat Preview</button>
                            <button class="btn btn-secondary btn-sm" onclick="printChecksheet('01/PC/20-07-2026')" style="padding: 0.35rem 0.75rem; font-size: 0.75rem; margin-left: 0.25rem; font-family: var(--font-body); font-weight: 600; border-radius: 6px;">Cetak</button>
                        </td>
                    </tr>
                    <tr>
                        <td class="td-center td-no">4</td>
                        <td style="font-weight: 700; color: var(--color-primary); padding: 0 0.8rem;">01/MT/15-07-2026</td>
                        <td style="padding: 0 0.8rem; font-size: 0.85rem;">15-07-2026</td>
                        <td style="padding: 0 0.8rem; font-size: 0.85rem; font-weight: 600;">Eko Prasetyo / Maintenance</td>
                        <td class="td-center" style="font-weight: 700;">4 Item</td>
                        <td class="td-center">
                            <span style="background-color: var(--color-success-light); color: var(--color-success); padding: 0.25rem 0.5rem; border-radius: 4px; font-weight: 700; font-size: 0.75rem; display: inline-block;">APPROVED</span>
                        </td>
                        <td class="td-center" style="padding: 0 0.5rem;">
                            <button class="btn btn-primary btn-sm" onclick="viewChecksheet('01/MT/15-07-2026')" style="padding: 0.35rem 0.75rem; font-size: 0.75rem; font-family: var(--font-body); font-weight: 600; border-radius: 6px;">Lihat Preview</button>
                            <button class="btn btn-secondary btn-sm" onclick="printChecksheet('01/MT/15-07-2026')" style="padding: 0.35rem 0.75rem; font-size: 0.75rem; margin-left: 0.25rem; font-family: var(--font-body); font-weight: 600; border-radius: 6px;">Cetak</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- ===== TAB PANE: INFO ===== --}}
<div id="info-pane" class="tab-pane no-print">
    <div style="display: grid; grid-template-columns: 3fr 2fr; gap: 1.5rem; max-width: 1200px; margin: 0 auto; align-items: start;">
        
        {{-- Left side: Stepper Timeline --}}
        <div class="glass-card" style="padding: 2rem;">
            <div style="display: flex; flex-direction: column; gap: 1.5rem;">
                <div>
                    <h3 style="font-family: var(--font-heading); font-weight: 700; color: var(--text-primary); margin-bottom: 0.5rem;">Info Status Approval Checksheet</h3>
                    <p style="color: var(--text-muted); font-size: 0.875rem;">Lacak tahapan persetujuan (approval flow) form pendaftaran consumable barang.</p>
                </div>
                
                <div class="form-group" style="margin-bottom: 1.5rem; max-width: 400px;">
                    <label for="select-approval-cs" style="font-weight: 600; font-size: 0.875rem; margin-bottom: 0.5rem; display: block;">Pilih No. Formulir:</label>
                    <select id="select-approval-cs" class="form-control" onchange="onSelectApprovalCs(this.value)" style="height: 42px;">
                        <option value="01/PR/{{ date('d-m-Y') }}" selected>01/PR/{{ date('d-m-Y') }} (Active Form - Draft)</option>
                        <option value="02/PR/22-07-2026">02/PR/22-07-2026 (Ahmad Fauzi - Approved)</option>
                        <option value="01/PC/20-07-2026">01/PC/20-07-2026 (Siti Rahma - Pending Warehouse)</option>
                        <option value="01/MT/15-07-2026">01/MT/15-07-2026 (Eko Prasetyo - Approved)</option>
                    </select>
                </div>
                
                <div style="border-top: 1px solid rgba(0,0,0,0.08); padding-top: 1.5rem;">
                    <h4 style="font-family: var(--font-heading); font-weight: 700; color: var(--text-primary); margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.5rem;">
                        Status Alur Approval: <span id="approval-status-badge" class="badge">DRAFT</span>
                    </h4>
                    
                    <div class="stepper-container">
                        <!-- Step 1: Pembuatan Form (User) -->
                        <div class="step-item" id="step-1">
                            <div class="step-indicator">1</div>
                            <div style="margin-left: 0.5rem;">
                                <h5 style="font-weight: 700; color: var(--text-primary); margin-bottom: 0.15rem; font-size: 0.95rem;">1. Pembuatan Form (Role: User)</h5>
                                <p style="color: var(--text-muted); font-size: 0.85rem;" id="step-1-details">-</p>
                                <p style="font-size: 0.8rem; font-weight: 600; margin-top: 0.25rem;" id="step-1-status">-</p>
                                <div class="step-comment-box" id="step-1-comment-box" style="margin-top: 0.5rem; background: rgba(0,0,0,0.02); padding: 0.5rem 0.75rem; border-radius: var(--radius-sm); border-left: 3px solid var(--color-primary); font-size: 0.8rem; display: none;">
                                    <strong>Catatan User:</strong> <span id="step-1-comment-text"></span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Step 2: Pemeriksaan & Persetujuan (Pemeriksa) -->
                        <div class="step-item" id="step-2">
                            <div class="step-indicator">2</div>
                            <div style="margin-left: 0.5rem;">
                                <h5 style="font-weight: 700; color: var(--text-primary); margin-bottom: 0.15rem; font-size: 0.95rem;">2. Persetujuan Kelayakan (Role: Pemeriksa)</h5>
                                <p style="color: var(--text-muted); font-size: 0.85rem;" id="step-2-details">-</p>
                                <p style="font-size: 0.8rem; font-weight: 600; margin-top: 0.25rem;" id="step-2-status">-</p>
                                <div class="step-comment-box" id="step-2-comment-box" style="margin-top: 0.5rem; background: rgba(0,0,0,0.02); padding: 0.5rem 0.75rem; border-radius: var(--radius-sm); border-left: 3px solid var(--color-primary); font-size: 0.8rem; display: none;">
                                    <strong>Catatan Pemeriksa:</strong> <span id="step-2-comment-text"></span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Step 3: Registrasi Kode Barang (Warehouse) -->
                        <div class="step-item" id="step-3">
                            <div class="step-indicator">3</div>
                            <div style="margin-left: 0.5rem;">
                                <h5 style="font-weight: 700; color: var(--text-primary); margin-bottom: 0.15rem; font-size: 0.95rem;">3. Registrasi Kode Barang (Role: Warehouse)</h5>
                                <p style="color: var(--text-muted); font-size: 0.85rem;" id="step-3-details">-</p>
                                <p style="font-size: 0.8rem; font-weight: 600; margin-top: 0.25rem;" id="step-3-status">-</p>
                                <div class="step-comment-box" id="step-3-comment-box" style="margin-top: 0.5rem; background: rgba(0,0,0,0.02); padding: 0.5rem 0.75rem; border-radius: var(--radius-sm); border-left: 3px solid var(--color-primary); font-size: 0.8rem; display: none;">
                                    <strong>Catatan Warehouse:</strong> <span id="step-3-comment-text"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right side: Simulation Verification & Comments --}}
        <div class="glass-card" style="padding: 2rem;">
            <h4 style="font-family: var(--font-heading); font-weight: 700; color: var(--text-primary); margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem;">
                <svg viewBox="0 0 24 24" width="18" height="18" stroke="currentColor" stroke-width="2.5" fill="none"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 1 1 3 3L12 15l-4 1 1-4z"></path></svg>
                Tulis Catatan & Tanda Tangan
            </h4>
            
            <div id="simulation-notice" style="background-color: var(--color-primary-light); color: var(--color-primary); padding: 0.75rem; border-radius: var(--radius-sm); font-size: 0.8rem; font-weight: 600; margin-bottom: 1.25rem;">
                Silakan isi data tanda tangan dan berikan komentar di bawah untuk mensimulasikan persetujuan secara real-time pada checksheet yang aktif.
            </div>
            
            <form id="form-simulation-approval" onsubmit="submitSimulatedApproval(event)">
                <div class="form-group" style="margin-bottom: 1rem;">
                    <label for="sim-role" style="font-weight: 600; font-size: 0.8rem; margin-bottom: 0.35rem; display: block;">Pilih Peran Anda (Role):</label>
                    <select id="sim-role" class="form-control" style="height: 42px;" onchange="onChangeSimRole(this.value)">
                        <option value="user">User (Pembuat Form)</option>
                        <option value="pemeriksa">Pemeriksa (Penyetuju)</option>
                        <option value="warehouse">Warehouse (Pendaftar Kode)</option>
                    </select>
                </div>
                
                <div class="form-group" style="margin-bottom: 1rem;">
                    <label for="sim-name" style="font-weight: 600; font-size: 0.8rem; margin-bottom: 0.35rem; display: block;">Nama Penandatangan:</label>
                    <input type="text" id="sim-name" class="form-control" placeholder="Masukkan nama pemeriksa/user..." required style="height: 42px;">
                </div>
                
                <div class="form-group" style="margin-bottom: 1.25rem;">
                    <label for="sim-comment" style="font-weight: 600; font-size: 0.8rem; margin-bottom: 0.35rem; display: block;">Tulis Catatan / Komentar:</label>
                    <textarea id="sim-comment" class="form-control" placeholder="Tuliskan komentar untuk checksheet ini..." required style="height: 90px; resize: none;"></textarea>
                </div>
                
                <button type="submit" class="btn btn-primary" style="width: 100%; height: 42px; display: flex; align-items: center; justify-content: center; gap: 0.5rem; font-weight: 600; border-radius: var(--radius-md);">
                    <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2.5" fill="none"><polyline points="20 6 9 17 4 12"></polyline></svg>
                    Kirim Catatan & Tanda Tangan
                </button>
            </form>
        </div>
        
    </div>
</div>

{{-- ===== TAB PANE: ACCOUNT MASTER ===== --}}
<div id="account-master-pane" class="tab-pane no-print">
    <div style="display: flex; flex-direction: column; gap: 1.5rem; max-width: 1200px; margin: 0 auto;">
        
        {{-- Header Card & Actions --}}
        <div class="glass-card" style="padding: 1.75rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
            <div>
                <h3 style="font-family: var(--font-heading); font-weight: 700; color: var(--text-primary); margin-bottom: 0.35rem; display: flex; align-items: center; gap: 0.5rem;">
                    <svg viewBox="0 0 24 24" width="22" height="22" stroke="var(--color-primary)" stroke-width="2.5" fill="none"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                    Account Master Management
                </h3>
                <p style="color: var(--text-muted); font-size: 0.875rem;">Kelola data pengguna, hak akses role (MASTER, USER, PEMERIKSA, WAREHOUSE), dan departemen.</p>
            </div>
            <button class="btn btn-primary" onclick="openModal('addAccountModal')" style="display: inline-flex; align-items: center; gap: 0.5rem; font-weight: 600; padding: 0.65rem 1.25rem; border-radius: var(--radius-md);">
                <svg viewBox="0 0 24 24" width="18" height="18" stroke="currentColor" stroke-width="2.5" fill="none"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                + Buat Akun Baru
            </button>
        </div>

        {{-- Stats Cards Row --}}
        <div class="dataview-stats">
            <div class="dataview-stat-card">
                <div style="background-color: var(--color-primary-light); color: var(--color-primary); padding: 0.75rem; border-radius: var(--radius-md); display: flex; align-items: center; justify-content: center;">
                    <svg viewBox="0 0 24 24" width="22" height="22" stroke="currentColor" stroke-width="2.5" fill="none"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle></svg>
                </div>
                <div>
                    <span style="font-size: 0.75rem; color: var(--text-muted); font-weight: 600; display: block; text-transform: uppercase;">Total Akun Terdaftar</span>
                    <span style="font-size: 1.4rem; font-weight: 800; color: var(--text-primary);" id="stat-total-accounts">4 Akun</span>
                </div>
            </div>
            
            <div class="dataview-stat-card">
                <div style="background-color: rgba(168, 85, 247, 0.1); color: #9333ea; padding: 0.75rem; border-radius: var(--radius-md); display: flex; align-items: center; justify-content: center;">
                    <svg viewBox="0 0 24 24" width="22" height="22" stroke="currentColor" stroke-width="2.5" fill="none"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"></path></svg>
                </div>
                <div>
                    <span style="font-size: 0.75rem; color: var(--text-muted); font-weight: 600; display: block; text-transform: uppercase;">Akun MASTER</span>
                    <span style="font-size: 1.4rem; font-weight: 800; color: #9333ea;">1 Akun</span>
                </div>
            </div>
            
            <div class="dataview-stat-card">
                <div style="background-color: rgba(59, 130, 246, 0.1); color: rgb(29, 78, 216); padding: 0.75rem; border-radius: var(--radius-md); display: flex; align-items: center; justify-content: center;">
                    <svg viewBox="0 0 24 24" width="22" height="22" stroke="currentColor" stroke-width="2.5" fill="none"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle></svg>
                </div>
                <div>
                    <span style="font-size: 0.75rem; color: var(--text-muted); font-weight: 600; display: block; text-transform: uppercase;">USER & PEMERIKSA</span>
                    <span style="font-size: 1.4rem; font-weight: 800; color: var(--text-primary);">2 Akun</span>
                </div>
            </div>

            <div class="dataview-stat-card">
                <div style="background-color: var(--color-success-light); color: var(--color-success); padding: 0.75rem; border-radius: var(--radius-md); display: flex; align-items: center; justify-content: center;">
                    <svg viewBox="0 0 24 24" width="22" height="22" stroke="currentColor" stroke-width="2.5" fill="none"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path></svg>
                </div>
                <div>
                    <span style="font-size: 0.75rem; color: var(--text-muted); font-weight: 600; display: block; text-transform: uppercase;">WAREHOUSE</span>
                    <span style="font-size: 1.4rem; font-weight: 800; color: var(--color-success);">1 Akun</span>
                </div>
            </div>
        </div>

        {{-- Accounts Table --}}
        <div class="glass-card" style="padding: 1.5rem;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem;">
                <h4 style="font-family: var(--font-heading); font-weight: 700; color: var(--text-primary);">Daftar Pengguna Sistem (Account List)</h4>
            </div>
            
            <div class="form-reg-table-wrap">
                <table class="form-reg-table" id="table-accounts">
                    <thead>
                        <tr>
                            <th class="th-center" style="width: 50px;">NO</th>
                            <th>USERNAME</th>
                            <th>DEPARTMENT</th>
                            <th class="th-center">ROLE HAK AKSES</th>
                            <th class="th-center">STATUS AKUN</th>
                            <th class="th-center">TANGGAL DIBUAT</th>
                            <th class="th-center" style="width: 120px;">AKSI</th>
                        </tr>
                    </thead>
                    <tbody id="accounts-tbody">
                        <!-- Populated dynamically via JS -->
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

{{-- ===== MODAL: BUAT AKUN BARU ===== --}}
<div class="modal no-print" id="addAccountModal">
    <div class="modal-content" style="max-width: 520px;">
        <div class="modal-header">
            <h3>Buat Akun Pengguna Baru</h3>
            <button class="btn-close" onclick="closeModal('addAccountModal')">&times;</button>
        </div>

        <form id="form-add-account" onsubmit="submitNewAccount(event)">
            <div class="form-group" style="margin-bottom: 1rem;">
                <label for="acc_username" style="font-weight: 600; font-size: 0.85rem; margin-bottom: 0.35rem; display: block;">Username / Nama Lengkap <span style="color: var(--color-danger);">*</span></label>
                <input type="text" id="acc_username" class="form-control" placeholder="Masukkan username atau nama..." required style="height: 42px;">
            </div>

            <div class="form-group" style="margin-bottom: 1rem;">
                <label for="acc_dept" style="font-weight: 600; font-size: 0.85rem; margin-bottom: 0.35rem; display: block;">Department <span style="color: var(--color-danger);">*</span></label>
                <select id="acc_dept" class="form-control" style="height: 42px;" required>
                    <option value="Production" selected>Production (PR)</option>
                    <option value="IT Department">IT Department (IT)</option>
                    <option value="Maintenance">Maintenance (MT)</option>
                    <option value="Procurement">Procurement (PC)</option>
                    <option value="Quality Assurance">Quality Assurance (QA)</option>
                    <option value="Warehouse Logistik">Warehouse Logistik (WH)</option>
                    <option value="Management / Executive">Management / Executive</option>
                </select>
            </div>

            <div class="form-group" style="margin-bottom: 1rem;">
                <label for="acc_role" style="font-weight: 600; font-size: 0.85rem; margin-bottom: 0.35rem; display: block;">Role Hak Akses <span style="color: var(--color-danger);">*</span></label>
                <select id="acc_role" class="form-control" style="height: 42px;" required>
                    <option value="MASTER">Akun MASTER (Akses Penuh)</option>
                    <option value="USER" selected>USER (Pembuat Form)</option>
                    <option value="PEMERIKSA">PEMERIKSA (Penyetuju Kelayakan)</option>
                    <option value="WAREHOUSE">WAREHOUSE (Pendaftar Kode Barang)</option>
                </select>
            </div>

            <div class="form-group" style="margin-bottom: 1.5rem;">
                <label for="acc_password" style="font-weight: 600; font-size: 0.85rem; margin-bottom: 0.35rem; display: block;">Password <span style="color: var(--color-danger);">*</span></label>
                <input type="password" id="acc_password" class="form-control" placeholder="Masukkan password akun..." required style="height: 42px;">
            </div>

            <div style="display: flex; gap: 0.75rem; justify-content: flex-end;">
                <button type="button" class="btn btn-secondary" onclick="closeModal('addAccountModal')">Batal</button>
                <button type="submit" class="btn btn-primary" style="display: flex; align-items: center; gap: 0.4rem;">
                    <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2.5" fill="none"><polyline points="20 6 9 17 4 12"></polyline></svg>
                    Simpan Akun
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ===== MODAL: TAMBAH DATA ===== --}}
<div class="modal no-print" id="addItemModal">
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
                    <label for="fi_usia">Estimasi Usia Pakai (Hari)</label>
                    <input type="number" id="fi_usia" name="estimasi_usia_pakai" class="form-control" placeholder="Cth: 730" min="0" value="{{ old('estimasi_usia_pakai') }}">
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

            {{-- Row 4: Min, Titik Order, Max --}}
            <div style="display:grid; grid-template-columns:1fr 1fr 1fr; gap:1rem; margin-bottom:1rem;">
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

    // TAB SYSTEM & MOCK DATA
    const activeFormNo = '01/PR/{{ date("d-m-Y") }}';
    let activeChecksheetHtml = '';
    let selectedChecksheetId = activeFormNo;
    let formCounter = 1;

    const checksheets = {
        '01/PR/{{ date("d-m-Y") }}': {
            docNo: 'No Doc : W1-CDS-PP-20/F1 Rev 2 &nbsp;|&nbsp; No. Form: <span style="font-weight:700; color:var(--color-primary);">01/PR/{{ date("d-m-Y") }}</span>',
            formNo: '01/PR/{{ date("d-m-Y") }}',
            date: '{{ date("d-m-Y") }}',
            requestor: '{{ Auth::user()->name ?? "Production User" }} / Production',
            status: 'Draft',
            items: [],
            signatures: {
                dibuat: '{{ Auth::user()->name ?? "Production User" }} ({{ date("d-m-Y") }})',
                diperiksa: '...................',
                disetujui: '...................'
            },
            comments: {
                user: 'Formulir pendaftaran barang consumable departemen Production.',
                pemeriksa: '',
                warehouse: ''
            }
        },
        '02/PR/22-07-2026': {
            docNo: 'No Doc : W1-CDS-PP-20/F1 Rev 2 &nbsp;|&nbsp; No. Form: <span style="font-weight:700; color:var(--color-primary);">02/PR/22-07-2026</span>',
            formNo: '02/PR/22-07-2026',
            date: '22-07-2026',
            requestor: 'Ahmad Fauzi / Production',
            status: 'Selesai',
            items: [
                { no: 1, kode: 'CDS-PRD-01', nama: 'Sarung Tangan Karet', harga: 'Rp 15.000', usia: '7 Hari', katPeng: 'Produksi', katUk: 'Sedang', min: 50, titik: 100, max: 200, aset: 'NO ASET', b3: true, non_b3: false },
                { no: 2, kode: 'CDS-PRD-02', nama: 'Masker Debu 3M', harga: 'Rp 35.000', usia: '3 Hari', katPeng: 'Produksi', katUk: 'Kecil', min: 100, titik: 150, max: 300, aset: 'NO ASET', b3: false, non_b3: true },
                { no: 3, kode: 'CDS-PRD-03', nama: 'Kacamata Safety Kings', harga: 'Rp 85.000', usia: '180 Hari', katPeng: 'Produksi', katUk: 'Sedang', min: 10, titik: 20, max: 50, aset: 'NO ASET', b3: false, non_b3: true },
                { no: 4, kode: 'CDS-PRD-04', nama: 'Earplug Orange', harga: 'Rp 8.000', usia: '5 Hari', katPeng: 'Produksi', katUk: 'Kecil', min: 200, titik: 300, max: 600, aset: 'NO ASET', b3: false, non_b3: true },
                { no: 5, kode: 'CDS-PRD-05', nama: 'Majun Putih Jahit', harga: 'Rp 12.000', usia: '2 Hari', katPeng: 'Produksi', katUk: 'Besar', min: 15, titik: 30, max: 60, aset: 'NO ASET', b3: false, non_b3: true }
            ],
            signatures: {
                dibuat: 'Ahmad Fauzi (22-07-2026)',
                diperiksa: 'Suherman (Supervisor - 22-07-2026)',
                disetujui: 'Joko Widodo (Warehouse - 22-07-2026)'
            },
            comments: {
                user: 'Kebutuhan mendesak alat pelindung diri untuk operator lini produksi A.',
                pemeriksa: 'APD kacamata kings dan masker 3M memenuhi standar keselamatan kerja perusahaan. Layak digunakan.',
                warehouse: 'Range barang consumable telah diregistrasikan di SAP dengan prefiks CDS-PRD-xx.'
            }
        },
        '01/PC/20-07-2026': {
            docNo: 'No Doc : W1-CDS-PP-20/F1 Rev 2 &nbsp;|&nbsp; No. Form: <span style="font-weight:700; color:var(--color-primary);">01/PC/20-07-2026</span>',
            formNo: '01/PC/20-07-2026',
            date: '20-07-2026',
            requestor: 'Siti Rahma / Procurement',
            status: 'Proses',
            items: [
                { no: 1, kode: 'CDS-OFF-01', nama: 'Kertas HVS A4 80gr', harga: 'Rp 54.000', usia: '30 Hari', katPeng: 'Kantor', katUk: 'Sedang', min: 20, titik: 30, max: 80, aset: 'NO ASET', b3: false, non_b3: true },
                { no: 2, kode: 'CDS-OFF-02', nama: 'Pulpen Pilot Ballliner', harga: 'Rp 15.000', usia: '14 Hari', katPeng: 'Kantor', katUk: 'Kecil', min: 50, titik: 80, max: 150, aset: 'NO ASET', b3: false, non_b3: true },
                { no: 3, kode: 'CDS-OFF-03', nama: 'Tinta Printer Epson L3110 Black', harga: 'Rp 115.000', usia: '90 Hari', katPeng: 'Kantor', katUk: 'Kecil', min: 5, titik: 10, max: 20, aset: 'NO ASET', b3: false, non_b3: true }
            ],
            signatures: {
                dibuat: 'Siti Rahma (20-07-2026)',
                diperiksa: 'Hendra (Supervisor - 20-07-2026)',
                disetujui: '...................'
            },
            comments: {
                user: 'ATK bulanan departemen procurement, stok kertas dan tinta hampir habis.',
                pemeriksa: 'Jumlah sudah sesuai batas konsumsi bulanan departemen procurement.',
                warehouse: ''
            }
        },
        '01/MT/15-07-2026': {
            docNo: 'No Doc : W1-CDS-PP-20/F1 Rev 2 &nbsp;|&nbsp; No. Form: <span style="font-weight:700; color:var(--color-primary);">01/MT/15-07-2026</span>',
            formNo: '01/MT/15-07-2026',
            date: '15-07-2026',
            requestor: 'Eko Prasetyo / Maintenance',
            status: 'Selesai',
            items: [
                { no: 1, kode: 'CDS-MTC-01', nama: 'WD-40 Anti Karat 400ml', harga: 'Rp 65.000', usia: '60 Hari', katPeng: 'Maintenance', katUk: 'Sedang', min: 10, titik: 15, max: 30, aset: 'NO ASET', b3: true, non_b3: false },
                { no: 2, kode: 'CDS-MTC-02', nama: 'Isolasi Listrik Nitto', harga: 'Rp 12.000', usia: '30 Hari', katPeng: 'Maintenance', katUk: 'Kecil', min: 30, titik: 50, max: 100, aset: 'NO ASET', b3: false, non_b3: true },
                { no: 3, kode: 'CDS-MTC-03', nama: 'Baterai Panasonic AAA 4pcs', harga: 'Rp 22.000', usia: '180 Hari', katPeng: 'Maintenance', katUk: 'Kecil', min: 15, titik: 25, max: 60, aset: 'NO ASET', b3: false, non_b3: true },
                { no: 4, kode: 'CDS-MTC-04', nama: 'Gunting Seng Tekiro', harga: 'Rp 125.000', usia: '365 Hari', katPeng: 'Maintenance', katUk: 'Sedang', min: 2, titik: 4, max: 8, aset: 'NO ASET', b3: false, non_b3: true }
            ],
            signatures: {
                dibuat: 'Eko Prasetyo (15-07-2026)',
                diperiksa: 'Dedi (Supervisor - 15-07-2026)',
                disetujui: 'Joko Widodo (Warehouse - 16-07-2026)'
            },
            comments: {
                user: 'Pengadaan pelumas karat WD-40 untuk preventive maintenance lini mesin forging.',
                pemeriksa: 'Sangat direkomendasikan untuk mencegah aus pada cetakan metal.',
                warehouse: 'Sudah masuk ke database ERP bagian maintenance consumable.'
            }
        }
    };

    const stepperData = {
        '01/PR/{{ date("d-m-Y") }}': {
            statusText: 'DRAFT',
            statusClass: 'badge-warning',
            steps: [
                { completed: true, active: false, details: '{{ Auth::user()->name ?? "Production User" }} - Production (Tanggal: {{ date("d-m-Y") }})', status: 'Selesai dibuat dan ditandatangani.', color: 'var(--color-success)' },
                { completed: false, active: true, details: 'Menunggu kelayakan disetujui Pemeriksa...', status: 'Pemeriksaan kelayakan.', color: 'var(--color-primary)' },
                { completed: false, active: false, details: 'Belum diisi.', status: 'Registrasi oleh Warehouse.', color: 'var(--text-muted)' }
            ]
        },
        '02/PR/22-07-2026': {
            statusText: 'APPROVED',
            statusClass: 'badge-success',
            steps: [
                { completed: true, active: false, details: 'Ahmad Fauzi (Tanggal: 22-07-2026)', status: 'Selesai dibuat dan ditandatangani.', color: 'var(--color-success)' },
                { completed: true, active: false, details: 'Suherman (Pemeriksa - Tanggal: 22-07-2026)', status: 'Disetujui. Kelayakan barang consumable terverifikasi.', color: 'var(--color-success)' },
                { completed: true, active: false, details: 'Joko Widodo (Warehouse - Tanggal: 22-07-2026)', status: 'Kode barang berhasil diregistrasikan ke database ERP.', color: 'var(--color-success)' }
            ]
        },
        '01/PC/20-07-2026': {
            statusText: 'PENDING WAREHOUSE',
            statusClass: 'badge-info',
            steps: [
                { completed: true, active: false, details: 'Siti Rahma (Tanggal: 20-07-2026)', status: 'Selesai dibuat dan ditandatangani.', color: 'var(--color-success)' },
                { completed: true, active: false, details: 'Hendra (Pemeriksa - Tanggal: 20-07-2026)', status: 'Disetujui. Kelayakan barang consumable terverifikasi.', color: 'var(--color-success)' },
                { completed: false, active: true, details: 'Menunggu registrasi oleh Warehouse...', status: 'Menunggu input kode barang.', color: 'var(--color-primary)' }
            ]
        },
        '01/MT/15-07-2026': {
            statusText: 'APPROVED',
            statusClass: 'badge-success',
            steps: [
                { completed: true, active: false, details: 'Eko Prasetyo (Tanggal: 15-07-2026)', status: 'Selesai dibuat dan ditandatangani.', color: 'var(--color-success)' },
                { completed: true, active: false, details: 'Dedi (Pemeriksa - Tanggal: 15-07-2026)', status: 'Disetujui. Kelayakan barang consumable terverifikasi.', color: 'var(--color-success)' },
                { completed: true, active: false, details: 'Joko Widodo (Warehouse - Tanggal: 16-07-2026)', status: 'Kode barang berhasil diregistrasikan ke database ERP.', color: 'var(--color-success)' }
            ]
        }
    };

    document.addEventListener('DOMContentLoaded', function() {
        // Save the active checksheet items HTML structure
        activeChecksheetHtml = document.getElementById('preview-table-body').innerHTML;
        
        // Initialize approval stepper view
        updateApprovalStepper(selectedChecksheetId);

        // Initialize Account Master table view
        renderAccountTable();

        // Check URL hash for sheet navigation
        if (window.location.hash === '#account-master') {
            switchSheet('account-master');
        }
    });

    function createNewForm() {
        formCounter++;
        const nextSeq = String(formCounter).padStart(2, '0');
        const todayStr = '{{ date("d-m-Y") }}';
        const nextFormNo = `${nextSeq}/PR/${todayStr}`;
        
        checksheets[nextFormNo] = {
            docNo: 'No Doc : W1-CDS-PP-20/F1 Rev 2 &nbsp;|&nbsp; No. Form: <span style="font-weight:700; color:var(--color-primary);">' + nextFormNo + '</span>',
            formNo: nextFormNo,
            date: todayStr,
            requestor: '{{ Auth::user()->name ?? "Production User" }} / Production',
            status: 'Draft',
            items: [],
            signatures: {
                dibuat: '{{ Auth::user()->name ?? "Production User" }} (Tgl: ' + todayStr + ')',
                diperiksa: '...................',
                disetujui: '...................'
            },
            comments: {
                user: 'Formulir pendaftaran barang consumable baru.',
                pemeriksa: '',
                warehouse: ''
            }
        };

        stepperData[nextFormNo] = {
            statusText: 'DRAFT',
            statusClass: 'badge-warning',
            steps: [
                { completed: true, active: false, details: '{{ Auth::user()->name ?? "Production User" }} - Production (Tanggal: ' + todayStr + ')', status: 'Selesai dibuat.', color: 'var(--color-success)' },
                { completed: false, active: true, details: 'Menunggu pemeriksaan Pemeriksa...', status: 'Pemeriksaan kelayakan.', color: 'var(--color-primary)' },
                { completed: false, active: false, details: 'Belum diisi.', status: 'Registrasi Warehouse.', color: 'var(--text-muted)' }
            ]
        };

        // Update Dropdown in Info Tab
        const select = document.getElementById('select-approval-cs');
        if (select) {
            const opt = document.createElement('option');
            opt.value = nextFormNo;
            opt.innerText = `${nextFormNo} (Production - Draft Baru)`;
            opt.selected = true;
            select.prepend(opt);
        }

        // Switch to Print Preview & view this new form
        viewChecksheet(nextFormNo);
        showToast(`Formulir Baru (${nextFormNo}) Berhasil Dibuat!`, 'success');
    }

    function switchSheet(tabId) {
        document.querySelectorAll('.tab-pane').forEach(pane => pane.classList.remove('active'));
        document.querySelectorAll('.sheet-tab').forEach(tab => tab.classList.remove('active'));

        const activePane = document.getElementById(tabId + '-pane');
        if (activePane) activePane.classList.add('active');
        
        const tabs = Array.from(document.querySelectorAll('.sheet-tab'));
        const matchingTab = tabs.find(t => t.getAttribute('onclick').includes(`'${tabId}'`));
        if (matchingTab) matchingTab.classList.add('active');

        const btnTambahData = document.getElementById('btn-tambah-data');
        if (btnTambahData) {
            btnTambahData.style.display = (tabId === 'print-preview' && selectedChecksheetId === activeFormNo) ? 'inline-flex' : 'none';
        }
    }

    function viewChecksheet(csId) {
        selectedChecksheetId = csId;
        switchSheet('print-preview');
        
        const cs = checksheets[csId];
        if (!cs) return;

        document.getElementById('preview-docno').innerHTML = cs.docNo;
        document.getElementById('preview-date').innerText = cs.date;
        document.getElementById('preview-requestor').innerText = cs.requestor;
        
        const signatureDibuat = document.getElementById('preview-sig-dibuat');
        const signatureDiperiksa = document.getElementById('preview-sig-diperiksa');
        const signatureDisetujui = document.getElementById('preview-sig-disetujui');
        
        if (cs.signatures.dibuat && cs.signatures.dibuat !== '...................') {
            signatureDibuat.innerHTML = `<div style="color: var(--color-success); font-weight: 700; margin-bottom: 0.25rem;">✓ USER SUBMITTED</div><div style="font-size: 0.65rem; color: var(--text-muted);">${cs.signatures.dibuat}</div>`;
        } else {
            signatureDibuat.innerHTML = `<span style="color: var(--text-muted); font-size: 0.75rem; font-style: italic;">...................</span>`;
        }

        if (cs.signatures.diperiksa && cs.signatures.diperiksa !== '...................') {
            signatureDiperiksa.innerHTML = `<div style="color: var(--color-success); font-weight: 700; margin-bottom: 0.25rem;">✓ APPROVED BY PEMERIKSA</div><div style="font-size: 0.65rem; color: var(--text-muted);">${cs.signatures.diperiksa}</div>`;
        } else {
            signatureDiperiksa.innerHTML = `<span style="color: var(--text-muted); font-size: 0.75rem; font-style: italic;">...................</span>`;
        }

        if (cs.signatures.disetujui && cs.signatures.disetujui !== '...................') {
            signatureDisetujui.innerHTML = `<div style="color: var(--color-success); font-weight: 700; margin-bottom: 0.25rem;">✓ REGISTERED BY WAREHOUSE</div><div style="font-size: 0.65rem; color: var(--text-muted);">${cs.signatures.disetujui}</div>`;
        } else {
            signatureDisetujui.innerHTML = `<span style="color: var(--text-muted); font-size: 0.75rem; font-style: italic;">...................</span>`;
        }

        const tbody = document.getElementById('preview-table-body');
        if (csId === activeFormNo && cs.items.length === 0) {
            tbody.innerHTML = activeChecksheetHtml;
        } else {
            let rowsHtml = '';
            cs.items.forEach((item, index) => {
                const isEven = index % 2 !== 0;
                const rowClass = isEven ? 'tr-even' : '';
                rowsHtml += `
                    <tr class="${rowClass}">
                        <td class="td-center td-no">${item.no}</td>
                        <td class="td-center" style="font-size:0.72rem; font-weight:600; color:var(--color-primary); padding:0 0.4rem;">${item.kode || '-'}</td>
                        <td style="padding:0 0.6rem; font-weight:600; font-size:0.8rem;">${item.nama}</td>
                        <td class="td-center" style="font-size:0.75rem;">${item.harga}</td>
                        <td class="td-center" style="font-size:0.75rem;">${item.usia}</td>
                        <td class="td-center" style="font-size:0.75rem;">${item.katPeng}</td>
                        <td class="td-center" style="font-size:0.75rem;">${item.katUk}</td>
                        <td class="td-center" style="font-size:0.75rem;">${item.min}</td>
                        <td class="td-center" style="font-size:0.75rem;">${item.titik}</td>
                        <td class="td-center" style="font-size:0.75rem;">${item.max}</td>
                        <td class="td-center" style="font-size:0.75rem; vertical-align: middle;">
                            <span style="background-color: #f1f5f9; color: var(--text-muted); padding: 0.2rem 0.5rem; border-radius: 4px; font-weight: 600; font-size: 0.7rem; display: inline-block;">${item.aset}</span>
                        </td>
                        <td class="td-center" style="font-size:0.85rem;">
                            ${item.b3 ? `<svg viewBox="0 0 24 24" width="14" height="14" stroke="var(--color-success)" stroke-width="3" fill="none"><polyline points="20 6 9 17 4 12"></polyline></svg>` : ''}
                        </td>
                        <td class="td-center" style="font-size:0.85rem;">
                            ${item.non_b3 ? `<svg viewBox="0 0 24 24" width="14" height="14" stroke="var(--color-success)" stroke-width="3" fill="none"><polyline points="20 6 9 17 4 12"></polyline></svg>` : ''}
                        </td>
                        <td class="td-center no-print"></td>
                    </tr>
                `;
            });

            if (cs.items.length < 13) {
                for (let i = cs.items.length; i < 13; i++) {
                    const isEven = i % 2 !== 0;
                    const rowClass = isEven ? 'tr-even' : '';
                    rowsHtml += `
                        <tr class="${rowClass}">
                            <td class="td-center td-no" style="color:#cbd5e1;">${i + 1}</td>
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
                    `;
                }
            }
            tbody.innerHTML = rowsHtml;
        }
        
        const select = document.getElementById('select-approval-cs');
        if (select) {
            select.value = csId;
            updateApprovalStepper(csId);
        }
    }

    function printChecksheet(csId) {
        viewChecksheet(csId);
        setTimeout(function() { window.print(); }, 150);
    }

    function printCurrentSheet() {
        switchSheet('print-preview');
        setTimeout(function() { window.print(); }, 150);
    }

    function onSelectApprovalCs(csId) {
        updateApprovalStepper(csId);
    }

    function submitSimulatedApproval(event) {
        event.preventDefault();
        
        const cs = checksheets[selectedChecksheetId];
        const steps = stepperData[selectedChecksheetId] ? stepperData[selectedChecksheetId].steps : null;

        if (!cs || !steps) {
            alert('Formulir tidak ditemukan.');
            return;
        }

        const role = document.getElementById('sim-role').value;
        const name = document.getElementById('sim-name').value.trim();
        const comment = document.getElementById('sim-comment').value.trim();

        if (!name || !comment) {
            alert('Harap isi nama penandatangan dan catatan!');
            return;
        }

        if (role === 'user') {
            cs.requestor = name + ' / Production';
            cs.signatures.dibuat = name + ' (Tgl: ' + cs.date + ')';
            cs.comments.user = comment;
            
            steps[0].completed = true;
            steps[0].active = false;
            steps[0].details = name + ' (Tanggal: ' + cs.date + ')';
            steps[0].status = 'Selesai dibuat dan ditandatangani.';
            steps[0].color = 'var(--color-success)';
            
            steps[1].active = true;
            steps[1].details = 'Menunggu persetujuan Pemeriksa...';
            steps[1].status = 'Kirim berkas untuk verifikasi kelayakan.';
            steps[1].color = 'var(--color-primary)';
            
            const dataviewReq = document.getElementById('dataview-req-004');
            if (dataviewReq) dataviewReq.innerText = name + ' / Production';
        } 
        else if (role === 'pemeriksa') {
            if (!steps[0].completed) {
                alert('Role Pembuat (User) harus bertanda tangan terlebih dahulu!');
                return;
            }
            cs.signatures.diperiksa = name + ' (Tgl: ' + cs.date + ')';
            cs.comments.pemeriksa = comment;
            
            steps[1].completed = true;
            steps[1].active = false;
            steps[1].details = name + ' (Pemeriksa - Tanggal: ' + cs.date + ')';
            steps[1].status = 'Disetujui. Kelayakan barang consumable terverifikasi.';
            steps[1].color = 'var(--color-success)';
            
            steps[2].active = true;
            steps[2].details = 'Menunggu registrasi oleh Warehouse...';
            steps[2].status = 'Kirim berkas untuk input kode barang.';
            steps[2].color = 'var(--color-primary)';
        } 
        else if (role === 'warehouse') {
            if (!steps[0].completed || !steps[1].completed) {
                alert('Persetujuan Pemeriksa harus diselesaikan terlebih dahulu!');
                return;
            }
            cs.signatures.disetujui = name + ' (Tgl: ' + cs.date + ')';
            cs.comments.warehouse = comment;
            
            steps[2].completed = true;
            steps[2].active = false;
            steps[2].details = name + ' (Warehouse - Tanggal: ' + cs.date + ')';
            steps[2].status = 'Kode barang berhasil diregistrasikan ke database ERP.';
            steps[2].color = 'var(--color-success)';
            
            cs.status = 'Selesai';
            stepperData[selectedChecksheetId].statusText = 'APPROVED';
            stepperData[selectedChecksheetId].statusClass = 'badge-success';
            
            updateDataViewTableStatus(selectedChecksheetId, 'APPROVED', 'var(--color-success-light)', 'var(--color-success)');
        }

        document.getElementById('sim-name').value = '';
        document.getElementById('sim-comment').value = '';
        
        viewChecksheet(selectedChecksheetId);
        updateApprovalStepper(selectedChecksheetId);
        showToast('Catatan verifikasi berhasil dikirim!', 'success');
    }

    function updateDataViewTableStatus(csId, text, bgColor, color) {
        const cells = Array.from(document.querySelectorAll('#data-view-pane table td'));
        const csCell = cells.find(c => c.innerText.trim() === csId);
        if (csCell) {
            const row = csCell.parentElement;
            const statusSpan = row.querySelector('td span');
            if (statusSpan) {
                statusSpan.innerText = text;
                statusSpan.style.backgroundColor = bgColor;
                statusSpan.style.color = color;
            }
        }
    }

    function onChangeSimRole(role) {}

    function updateApprovalStepper(csId) {
        const data = stepperData[csId];
        if (!data) return;

        const badge = document.getElementById('approval-status-badge');
        if (badge) {
            badge.innerText = data.statusText;
            badge.className = 'badge';
            if (data.statusText === 'APPROVED') {
                badge.style.backgroundColor = 'var(--color-success-light)';
                badge.style.color = 'var(--color-success)';
            } else if (data.statusText === 'DRAFT') {
                badge.style.backgroundColor = 'var(--color-warning-light)';
                badge.style.color = 'var(--color-warning)';
            } else if (data.statusText === 'PENDING WAREHOUSE') {
                badge.style.backgroundColor = 'rgba(59, 130, 246, 0.1)';
                badge.style.color = 'rgb(29, 78, 216)';
            }
        }

        for (let i = 1; i <= 3; i++) {
            const stepEl = document.getElementById('step-' + i);
            if (!stepEl) continue;
            const stepData = data.steps[i - 1];
            
            stepEl.classList.remove('completed', 'active', 'pending');
            
            const indicator = stepEl.querySelector('.step-indicator');
            const detailsEl = document.getElementById('step-' + i + '-details');
            const statusEl = document.getElementById('step-' + i + '-status');

            if (detailsEl) detailsEl.innerText = stepData.details;
            if (statusEl) {
                statusEl.innerText = stepData.status;
                statusEl.style.color = stepData.color;
            }

            const commentBox = document.getElementById('step-' + i + '-comment-box');
            const commentText = document.getElementById('step-' + i + '-comment-text');
            const roleKey = i === 1 ? 'user' : (i === 2 ? 'pemeriksa' : 'warehouse');
            const commentVal = checksheets[csId] && checksheets[csId].comments ? checksheets[csId].comments[roleKey] : '';

            if (commentBox && commentText) {
                if (commentVal) {
                    commentBox.style.display = 'block';
                    commentText.innerText = commentVal;
                } else {
                    commentBox.style.display = 'none';
                    commentText.innerText = '';
                }
            }

            if (stepData.completed) {
                stepEl.classList.add('completed');
                if (indicator) indicator.innerHTML = `<svg viewBox="0 0 24 24" width="12" height="12" stroke="currentColor" stroke-width="3" fill="none"><polyline points="20 6 9 17 4 12"></polyline></svg>`;
            } else if (stepData.active) {
                stepEl.classList.add('active');
                if (indicator) indicator.innerHTML = `<svg viewBox="0 0 24 24" width="12" height="12" stroke="currentColor" stroke-width="3" fill="none"><circle cx="12" cy="12" r="10"></circle></svg>`;
            } else {
                stepEl.classList.add('pending');
                if (indicator) indicator.innerHTML = i;
            }
        }
    }

    // ACCOUNT MASTER DATA ENGINE
    const userAccounts = [
        { id: 1, username: 'admin_master', name: 'Admin Master MAI', dept: 'Management / Executive', role: 'MASTER', status: 'Aktif', date: '15-07-2026' },
        { id: 2, username: 'budi_user', name: 'Budi Santoso', dept: 'Production', role: 'USER', status: 'Aktif', date: '16-07-2026' },
        { id: 3, username: 'suherman_spv', name: 'Suherman', dept: 'Quality Assurance', role: 'PEMERIKSA', status: 'Aktif', date: '18-07-2026' },
        { id: 4, username: 'joko_wh', name: 'Joko Widodo', dept: 'Warehouse Logistik', role: 'WAREHOUSE', status: 'Aktif', date: '20-07-2026' }
    ];

    function renderAccountTable() {
        const tbody = document.getElementById('accounts-tbody');
        if (!tbody) return;

        let html = '';
        userAccounts.forEach((acc, index) => {
            let roleBadge = '';
            if (acc.role === 'MASTER') {
                roleBadge = `<span style="background-color: rgba(168, 85, 247, 0.15); color: #9333ea; padding: 0.25rem 0.6rem; border-radius: 4px; font-weight: 700; font-size: 0.75rem;">AKUN MASTER</span>`;
            } else if (acc.role === 'USER') {
                roleBadge = `<span style="background-color: rgba(59, 130, 246, 0.15); color: rgb(29, 78, 216); padding: 0.25rem 0.6rem; border-radius: 4px; font-weight: 700; font-size: 0.75rem;">USER (PEMBUAT)</span>`;
            } else if (acc.role === 'PEMERIKSA') {
                roleBadge = `<span style="background-color: var(--color-warning-light); color: var(--color-warning); padding: 0.25rem 0.6rem; border-radius: 4px; font-weight: 700; font-size: 0.75rem;">PEMERIKSA</span>`;
            } else if (acc.role === 'WAREHOUSE') {
                roleBadge = `<span style="background-color: var(--color-success-light); color: var(--color-success); padding: 0.25rem 0.6rem; border-radius: 4px; font-weight: 700; font-size: 0.75rem;">WAREHOUSE</span>`;
            }

            html += `
                <tr>
                    <td class="td-center td-no">${index + 1}</td>
                    <td style="font-weight: 700; color: var(--text-primary); padding: 0 0.8rem;">${acc.username}</td>
                    <td style="padding: 0 0.8rem; font-size: 0.85rem; font-weight: 600;">${acc.dept}</td>
                    <td class="td-center">${roleBadge}</td>
                    <td class="td-center"><span style="background-color: var(--color-success-light); color: var(--color-success); padding: 0.2rem 0.5rem; border-radius: 4px; font-weight: 700; font-size: 0.7rem;">${acc.status}</span></td>
                    <td class="td-center" style="font-size: 0.85rem;">${acc.date}</td>
                    <td class="td-center">
                        <button class="btn btn-secondary btn-sm" onclick="alert('Detail akun ${acc.username} (Role: ${acc.role}, Dept: ${acc.dept})')" style="padding: 0.25rem 0.55rem; font-size: 0.75rem; border-radius: 4px;">Detail</button>
                    </td>
                </tr>
            `;
        });
        tbody.innerHTML = html;

        const statTotal = document.getElementById('stat-total-accounts');
        if (statTotal) statTotal.innerText = `${userAccounts.length} Akun`;
    }

    function submitNewAccount(event) {
        event.preventDefault();
        const username = document.getElementById('acc_username').value.trim();
        const dept = document.getElementById('acc_dept').value;
        const role = document.getElementById('acc_role').value;
        const pass = document.getElementById('acc_password').value;

        if (!username || !pass) {
            alert('Harap lengkapi username dan password!');
            return;
        }

        const newAcc = {
            id: userAccounts.length + 1,
            username: username,
            name: username,
            dept: dept,
            role: role,
            status: 'Aktif',
            date: '{{ date("d-m-Y") }}'
        };

        userAccounts.push(newAcc);
        renderAccountTable();
        closeModal('addAccountModal');
        document.getElementById('form-add-account').reset();
        showToast(`Akun '${username}' dengan Role [${role}] berhasil dibuat!`, 'success');
    }
</script>
@endsection
