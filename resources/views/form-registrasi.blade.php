@extends('layouts.app')

@section('title', 'Form Pendaftaran Barang Consumable')

@section('content')
@php
    $userDeptTag = strtoupper(Auth::user()->department ?? Auth::user()->name ?? 'PRODUCTION');
    $defaultFormNo = '01/' . $userDeptTag . '/' . date('m-Y');

    $existingFormNumbers = $formItems->pluck('form_number')->filter()->unique()->values();

    if ($activeFormNoParam && $existingFormNumbers->contains($activeFormNoParam)) {
        $currentFormNo = $activeFormNoParam;
    } else if ($existingFormNumbers->isNotEmpty()) {
        $currentFormNo = $existingFormNumbers->first();
    } else {
        $currentFormNo = $activeFormNoParam ?? $defaultFormNo;
    }

    $currentFormItems = $formItems->filter(function($item) use ($currentFormNo, $defaultFormNo) {
        $itemFormNo = $item->form_number ?: $defaultFormNo;
        return $itemFormNo === $currentFormNo;
    });
@endphp
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
    <div style="display: flex; gap: 0.75rem; align-items: center;">
        <button class="btn btn-primary" id="btn-form-baru" onclick="createNewForm()" style="font-weight: 600; font-family: var(--font-body); display: inline-flex; align-items: center; gap: 0.4rem; padding: 0.6rem 1.1rem; border-radius: var(--radius-md); cursor: pointer;">
            <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2.5" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                <polyline points="14 2 14 8 20 8"></polyline>
                <line x1="12" y1="18" x2="12" y2="12"></line>
                <line x1="9" y1="15" x2="15" y2="15"></line>
            </svg>
            + Form Baru
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
                <p class="form-reg-nodoc" id="preview-docno">No Doc : W1-CDS-PP-20/F1 Rev 2 &nbsp;|&nbsp; No. Form: <span id="form-number-display" style="font-weight: 700; color: var(--color-primary);">01/{{ strtoupper(Auth::user()->department ?? Auth::user()->name ?? 'PRODUCTION') }}/{{ date('m-Y') }}</span></p>
            </div>
            <div class="form-reg-header-right"></div>
        </div>

        {{-- ===== META INFO ROW ===== --}}
        <div class="form-reg-meta">
            <div class="form-reg-meta-item">
                <span class="form-reg-meta-label">TANGGAL :</span>
                <span class="form-reg-meta-line" id="preview-date">{{ date('d-m-Y') }}</span>
            </div>
            <div class="form-reg-meta-item">
                <span class="form-reg-meta-label">User Dept / Requestor :</span>
                <span class="form-reg-meta-line" id="preview-requestor">{{ Auth::user()->name ?? 'Production User' }} / {{ Auth::user()->department ?? 'Production' }}</span>
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
                        <th rowspan="2" class="th-center" style="width:95px;">ASET / NO ASET</th>
                        <th colspan="2" class="th-center">KATEGORI</th>
                    </tr>
                    <tr>
                        <th class="th-center" style="width:45px;">B3</th>
                        <th class="th-center" style="width:55px;">NON B3</th>
                    </tr>
                </thead>
                <tbody id="preview-table-body">
                    @forelse($currentFormItems->values() as $index => $item)
                    <tr class="data-row {{ $index % 2 != 0 ? 'tr-even' : '' }}">
                        <td class="td-center td-no">{{ $index + 1 }}</td>
                        <td class="td-center" style="padding:0 0.4rem;">
                            @if($item->kode_barang)
                                <span class="item-code-badge">{{ $item->kode_barang }}</span>
                            @else
                                <span style="color:var(--text-muted); font-size:0.75rem;">-</span>
                            @endif
                        </td>
                        <td style="padding:0 0.6rem; font-weight:700; font-size:0.82rem; color:var(--text-primary);">
                            {{ $item->nama_barang }}
                        </td>
                        <td class="td-center">
                            @if($item->harga)
                                <span class="item-price-tag">Rp {{ number_format($item->harga, 0, ',', '.') }}</span>
                            @else
                                <span style="color:var(--text-muted); font-size:0.75rem;">-</span>
                            @endif
                        </td>
                        <td class="td-center" style="font-size:0.75rem; font-weight:600; color:var(--text-muted);">
                            {{ $item->estimasi_usia_pakai ? (is_numeric(trim($item->estimasi_usia_pakai)) ? $item->estimasi_usia_pakai . ' Hari' : $item->estimasi_usia_pakai) : '-' }}
                        </td>
                        <td class="td-center" style="font-size:0.75rem; font-weight:600;">{{ $item->kategori_penggunaan ?? '-' }}</td>
                        <td class="td-center" style="font-size:0.75rem; font-weight:600;">{{ $item->kategori_ukuran ?? '-' }}</td>
                        <td class="td-center">
                            @if($item->min !== null && $item->min !== '')
                                <span class="badge-stock-min">{{ $item->min }}</span>
                            @else
                                <span style="color:var(--text-muted); font-size:0.75rem;">-</span>
                            @endif
                        </td>
                        <td class="td-center">
                            @if($item->titik_order !== null && $item->titik_order !== '')
                                <span class="badge-stock-titik">{{ $item->titik_order }}</span>
                            @else
                                <span style="color:var(--text-muted); font-size:0.75rem;">-</span>
                            @endif
                        </td>
                        <td class="td-center">
                            @if($item->max !== null && $item->max !== '')
                                <span class="badge-stock-max">{{ $item->max }}</span>
                            @else
                                <span style="color:var(--text-muted); font-size:0.75rem;">-</span>
                            @endif
                        </td>
                        <td class="td-center" style="font-size:0.75rem; font-weight:600;">
                            {{ $item->lead_time ?? '-' }}
                        </td>
                        <td class="td-center" style="vertical-align: middle;">
                            @if($item->kategori_aset === 'ASET')
                                <span class="badge-asset-yes">
                                    <svg viewBox="0 0 24 24" width="11" height="11" stroke="currentColor" stroke-width="3" fill="none"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                    ASET
                                </span>
                            @else
                                <span class="badge-asset-no">NO ASET</span>
                            @endif
                        </td>
                        <td class="td-center">
                            @if($item->is_b3)
                                <div class="check-icon-b3" title="Bahan Berbahaya Beracun">
                                    <svg viewBox="0 0 24 24" width="13" height="13" stroke="currentColor" stroke-width="3" fill="none"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                </div>
                            @endif
                        </td>
                        <td class="td-center">
                            @if($item->is_non_b3)
                                <div class="check-icon-b3" title="NON B3">
                                    <svg viewBox="0 0 24 24" width="13" height="13" stroke="currentColor" stroke-width="3" fill="none"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                </div>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <!-- Web View Empty State -->
                    <tr class="empty-state-row no-print">
                        <td colspan="14" style="padding: 0; border: none;">
                            <div class="empty-state-wrapper">
                                <div class="empty-state-card">
                                    <div class="empty-state-icon-container">
                                        <svg viewBox="0 0 24 24" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6">
                                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                            <polyline points="14 2 14 8 20 8"></polyline>
                                            <line x1="12" y1="18" x2="12" y2="12"></line>
                                            <line x1="9" y1="15" x2="15" y2="15"></line>
                                        </svg>
                                        <div class="empty-state-pulse"></div>
                                    </div>
                                    <div>
                                        <h4 class="empty-state-title">Belum Ada Data Barang Consumable</h4>
                                        <p class="empty-state-desc">Formulir pendaftaran ini masih kosong. Klik tombol di bawah atau gunakan tombol <strong>Tambah Data</strong> untuk mengisi lembar barang.</p>
                                    </div>
                                    <div class="empty-state-actions">
                                        <button type="button" class="empty-state-btn" onclick="openModal('addItemModal')">
                                            <svg viewBox="0 0 24 24" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                <line x1="12" y1="5" x2="12" y2="19"></line>
                                                <line x1="5" y1="12" x2="19" y2="12"></line>
                                            </svg>
                                            + Tambah Data Pertama
                                        </button>
                                    </div>
                                    <div class="empty-state-pills">
                                        <div class="empty-state-pill">
                                            <svg viewBox="0 0 24 24" stroke="var(--color-success)" stroke-width="2.5" fill="none"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                            Kategori B3 / Non-B3
                                        </div>
                                        <div class="empty-state-pill">
                                            <svg viewBox="0 0 24 24" stroke="var(--color-primary)" stroke-width="2.5" fill="none"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path></svg>
                                            No. Form Otomatis
                                        </div>
                                        <div class="empty-state-pill">
                                            <svg viewBox="0 0 24 24" stroke="var(--color-warning)" stroke-width="2.5" fill="none"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                                            Approval Real-time
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>

                    <!-- Print-only blank lined rows when no data is present -->
                    @for($p = 0; $p < 13; $p++)
                    <tr class="print-only-row {{ $p % 2 != 0 ? 'tr-even' : '' }}">
                        <td class="td-center td-no" style="color:#cbd5e1;">{{ $p + 1 }}</td>
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
                        <td class="td-input"></td>
                    </tr>
                    @endfor
                    @endforelse

                    {{-- Baris kosong sisa jika ada data tetapi kurang dari 13 --}}
                    @if($currentFormItems->count() > 0 && $currentFormItems->count() < 13)
                        @for($i = $currentFormItems->count(); $i < 13; $i++)
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
                            <td class="td-input"></td>
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
                <span style="font-size: 1.5rem; font-weight: 800; color: var(--text-primary);" id="stat-total-checksheets">0</span>
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
                <span style="font-size: 1.5rem; font-weight: 800; color: var(--text-primary);" id="stat-approved-checksheets">0</span>
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
                <span style="font-size: 1.5rem; font-weight: 800; color: var(--text-primary);" id="stat-process-checksheets">0</span>
            </div>
        </div>
    </div>

    {{-- Checksheet list glass container --}}
    <div class="glass-card" style="padding: 1.5rem;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem; flex-wrap: wrap; gap: 1rem;">
            <h3 style="font-family: var(--font-heading); font-weight: 700; color: var(--text-primary); margin: 0;">Daftar Form Registrasi (Print Preview)</h3>
            
            <div style="display: flex; align-items: center; gap: 0.75rem; flex-wrap: wrap;">
                <span style="font-size: 0.85rem; font-weight: 700; color: var(--text-muted); display: flex; align-items: center; gap: 0.35rem;">
                    <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2" fill="none"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                    Filter Bulan & Tahun:
                </span>
                <div style="display: flex; align-items: center; gap: 0.35rem;">
                    <select id="filter-month-dataview" class="form-control" style="width: 140px; height: 38px; font-size: 0.85rem; padding: 0 0.6rem;" onchange="renderDataViewTable()">
                        <option value="">Semua Bulan</option>
                        <option value="01">Januari</option>
                        <option value="02">Februari</option>
                        <option value="03">Maret</option>
                        <option value="04">April</option>
                        <option value="05">Mei</option>
                        <option value="06">Juni</option>
                        <option value="07">Juli</option>
                        <option value="08">Agustus</option>
                        <option value="09">September</option>
                        <option value="10">Oktober</option>
                        <option value="11">November</option>
                        <option value="12">Desember</option>
                    </select>
                </div>
                <div style="display: flex; align-items: center; gap: 0.35rem;">
                    <select id="filter-year-dataview" class="form-control" style="width: 130px; height: 38px; font-size: 0.85rem; padding: 0 0.6rem;" onchange="renderDataViewTable()">
                        <option value="">Semua Tahun</option>
                    </select>
                </div>
            </div>
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
                        <th class="th-center" style="width: 170px;">AKSI</th>
                        <th class="th-center" style="width: 170px;">HAPUS</th>
                    </tr>
                </thead>
                <tbody id="dataview-tbody">
                    <!-- Dynamically rendered via renderDataViewTable() -->
                </tbody>
            </table>
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
                    <svg viewBox="0 0 24 24" width="22" height="22" stroke="var(--color-primary)" stroke-width="2.5" fill="none"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                    Account Master Management
                </h3>
                <p style="color: var(--text-muted); font-size: 0.875rem;">Kelola data pengguna, hak akses role (User, Staff, Accounting, Warehouse Consumable), dan department.</p>
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
                    <span style="font-size: 1.4rem; font-weight: 800; color: var(--text-primary);" id="stat-total-accounts">{{ isset($users) ? $users->count() : 0 }} Akun</span>
                </div>
            </div>
            
            <div class="dataview-stat-card">
                <div style="background-color: rgba(59, 130, 246, 0.1); color: rgb(29, 78, 216); padding: 0.75rem; border-radius: var(--radius-md); display: flex; align-items: center; justify-content: center;">
                    <svg viewBox="0 0 24 24" width="22" height="22" stroke="currentColor" stroke-width="2.5" fill="none"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle></svg>
                </div>
                <div>
                    <span style="font-size: 0.75rem; color: var(--text-muted); font-weight: 600; display: block; text-transform: uppercase;">Akun USER</span>
                    <span style="font-size: 1.4rem; font-weight: 800; color: rgb(29, 78, 216);" id="stat-user-accounts-count">{{ isset($users) ? $users->where('role', 'User')->count() : 0 }} Akun</span>
                </div>
            </div>
            
            <div class="dataview-stat-card">
                <div style="background-color: var(--color-warning-light); color: var(--color-warning); padding: 0.75rem; border-radius: var(--radius-md); display: flex; align-items: center; justify-content: center;">
                    <svg viewBox="0 0 24 24" width="22" height="22" stroke="currentColor" stroke-width="2.5" fill="none"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"></path></svg>
                </div>
                <div>
                    <span style="font-size: 0.75rem; color: var(--text-muted); font-weight: 600; display: block; text-transform: uppercase;">Akun STAFF</span>
                    <span style="font-size: 1.4rem; font-weight: 800; color: var(--color-warning);" id="stat-staff-accounts-count">{{ isset($users) ? $users->where('role', 'Staff')->count() : 0 }} Akun</span>
                </div>
            </div>

            <div class="dataview-stat-card">
                <div style="background-color: var(--color-success-light); color: var(--color-success); padding: 0.75rem; border-radius: var(--radius-md); display: flex; align-items: center; justify-content: center;">
                    <svg viewBox="0 0 24 24" width="22" height="22" stroke="currentColor" stroke-width="2.5" fill="none"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path></svg>
                </div>
                <div>
                    <span style="font-size: 0.75rem; color: var(--text-muted); font-weight: 600; display: block; text-transform: uppercase;">ACCOUNTING & WH</span>
                    <span style="font-size: 1.4rem; font-weight: 800; color: var(--color-success);" id="stat-acc-wh-accounts-count">{{ isset($users) ? $users->whereIn('role', ['Accounting', 'Warehouse Consumable'])->count() : 0 }} Akun</span>
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
                            <th>USER NAME / LOGIN ID</th>
                            <th>DEPARTMENT</th>
                            <th class="th-center">ROLE HAK AKSES</th>
                            <th class="th-center">STATUS AKUN</th>
                            <th class="th-center">TANGGAL DIBUAT</th>
                            <th class="th-center" style="width: 140px;">AKSI</th>
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

        <form action="{{ route('users.store') }}" method="POST">
            @csrf
            <div class="form-group" style="margin-bottom: 1rem;">
                <label for="acc_add_name" style="font-weight: 600; font-size: 0.85rem; margin-bottom: 0.35rem; display: block;">User Name <span style="color: var(--color-danger);">*</span></label>
                <input type="text" id="acc_add_name" name="name" class="form-control" placeholder="Masukkan nama pengguna..." required style="height: 42px;">
            </div>

            <div class="form-group" style="margin-bottom: 1rem;">
                <label for="acc_add_username" style="font-weight: 600; font-size: 0.85rem; margin-bottom: 0.35rem; display: block;">Username / Login ID</label>
                <input type="text" id="acc_add_username" name="username" class="form-control" placeholder="Cth: budi_santoso (opsional)" style="height: 42px;">
            </div>

            <div class="form-group" style="margin-bottom: 1rem;">
                <label for="acc_add_dept" style="font-weight: 600; font-size: 0.85rem; margin-bottom: 0.35rem; display: block;">Department <span style="color: var(--color-danger);">*</span></label>
                <select id="acc_add_dept" name="department" class="form-control" style="height: 42px;" required>
                    <option value="" disabled selected>-- Pilih Department --</option>
                    <option value="HRGA">HRGA</option>
                    <option value="PPIC Finish Good">PPIC Finish Good</option>
                    <option value="PPIC Warehouse">PPIC Warehouse</option>
                    <option value="QA">QA</option>
                    <option value="QC">QC</option>
                    <option value="Production">Production</option>
                    <option value="Die Shop">Die Shop</option>
                    <option value="Dies Assy">Dies Assy</option>
                    <option value="Maintenance">Maintenance</option>
                    <option value="Accounting">Accounting</option>
                </select>
            </div>

            <div class="form-group" style="margin-bottom: 1rem;">
                <label for="acc_add_role" style="font-weight: 600; font-size: 0.85rem; margin-bottom: 0.35rem; display: block;">Role Hak Akses <span style="color: var(--color-danger);">*</span></label>
                <select id="acc_add_role" name="role" class="form-control" style="height: 42px;" required>
                    <option value="" disabled selected>-- Pilih Role --</option>
                    <option value="User">User</option>
                    <option value="Staff">Staff</option>
                    <option value="Accounting">Accounting</option>
                    <option value="Warehouse Consumable">Warehouse Consumable</option>
                </select>
            </div>

            <div class="form-group" style="margin-bottom: 1.5rem;">
                <label for="acc_add_password" style="font-weight: 600; font-size: 0.85rem; margin-bottom: 0.35rem; display: block;">Password <span style="color: var(--color-danger);">*</span></label>
                <input type="password" id="acc_add_password" name="password" class="form-control" placeholder="Masukkan password akun..." required style="height: 42px;">
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

{{-- ===== MODAL: EDIT AKUN ===== --}}
<div class="modal no-print" id="editAccountModal">
    <div class="modal-content" style="max-width: 520px;">
        <div class="modal-header">
            <h3>Edit Akun Pengguna</h3>
            <button class="btn-close" onclick="closeModal('editAccountModal')">&times;</button>
        </div>

        <form id="form-edit-account" action="" method="POST">
            @csrf
            @method('PUT')
            
            <div class="form-group" style="margin-bottom: 1rem;">
                <label for="acc_edit_name" style="font-weight: 600; font-size: 0.85rem; margin-bottom: 0.35rem; display: block;">User Name <span style="color: var(--color-danger);">*</span></label>
                <input type="text" id="acc_edit_name" name="name" class="form-control" required style="height: 42px;">
            </div>

            <div class="form-group" style="margin-bottom: 1rem;">
                <label for="acc_edit_username" style="font-weight: 600; font-size: 0.85rem; margin-bottom: 0.35rem; display: block;">Username / Login ID</label>
                <input type="text" id="acc_edit_username" name="username" class="form-control" style="height: 42px;">
            </div>

            <div class="form-group" style="margin-bottom: 1rem;">
                <label for="acc_edit_dept" style="font-weight: 600; font-size: 0.85rem; margin-bottom: 0.35rem; display: block;">Department <span style="color: var(--color-danger);">*</span></label>
                <select id="acc_edit_dept" name="department" class="form-control" style="height: 42px;" required>
                    <option value="HRGA">HRGA</option>
                    <option value="PPIC Finish Good">PPIC Finish Good</option>
                    <option value="PPIC Warehouse">PPIC Warehouse</option>
                    <option value="QA">QA</option>
                    <option value="QC">QC</option>
                    <option value="Production">Production</option>
                    <option value="Die Shop">Die Shop</option>
                    <option value="Dies Assy">Dies Assy</option>
                    <option value="Maintenance">Maintenance</option>
                </select>
            </div>

            <div class="form-group" style="margin-bottom: 1rem;">
                <label for="acc_edit_role" style="font-weight: 600; font-size: 0.85rem; margin-bottom: 0.35rem; display: block;">Role Hak Akses <span style="color: var(--color-danger);">*</span></label>
                <select id="acc_edit_role" name="role" class="form-control" style="height: 42px;" required>
                    <option value="User">User</option>
                    <option value="Staff">Staff</option>
                    <option value="Accounting">Accounting</option>
                    <option value="Warehouse Consumable">Warehouse Consumable</option>
                </select>
            </div>

            <div class="form-group" style="margin-bottom: 1.5rem;">
                <label for="acc_edit_password" style="font-weight: 600; font-size: 0.85rem; margin-bottom: 0.35rem; display: block;">Password Baru <span style="color: var(--text-muted); font-size: 0.75rem;">(Opsional)</span></label>
                <input type="password" id="acc_edit_password" name="password" class="form-control" placeholder="Kosongkan jika tidak ingin mengubah password" style="height: 42px;">
            </div>

            <div style="display: flex; gap: 0.75rem; justify-content: flex-end;">
                <button type="button" class="btn btn-secondary" onclick="closeModal('editAccountModal')">Batal</button>
                <button type="submit" class="btn btn-primary" style="display: flex; align-items: center; gap: 0.4rem;">
                    <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2.5" fill="none"><polyline points="20 6 9 17 4 12"></polyline></svg>
                    Simpan Perubahan
                </button>
            </div>
        </form>
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
            <input type="hidden" name="form_number" id="modal_form_number" value="{{ $currentFormNo }}">

            {{-- Row 1: Kode & Nama --}}
            <div style="display:grid; grid-template-columns:1fr 2fr; gap:1rem; margin-bottom:1rem;">
                <div class="form-group" style="margin-bottom:0;">
                    <label for="fi_kode">Kode Barang <span style="color:var(--color-danger);">*</span></label>
                    <input type="text" id="fi_kode" name="kode_barang" class="form-control @error('kode_barang') is-invalid @enderror" placeholder="Cth: CDS-001" value="{{ old('kode_barang') }}" required>
                    @error('kode_barang')<div class="error-text">{{ $message }}</div>@enderror
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
                    <label for="fi_harga">Harga (Rp) <span style="color:var(--color-danger);">*</span></label>
                    <input type="number" id="fi_harga" name="harga" class="form-control @error('harga') is-invalid @enderror" placeholder="0" min="0" value="{{ old('harga') }}" required>
                    @error('harga')<div class="error-text">{{ $message }}</div>@enderror
                </div>
                <div class="form-group" style="margin-bottom:0;">
                    <label for="fi_usia">Estimasi Usia Pakai <span style="color:var(--color-danger);">*</span></label>
                    <input type="text" id="fi_usia" name="estimasi_usia_pakai" class="form-control @error('estimasi_usia_pakai') is-invalid @enderror" placeholder="Cth: 730 Hari, 6 Bulan, 1 Tahun" value="{{ old('estimasi_usia_pakai') }}" required>
                    @error('estimasi_usia_pakai')<div class="error-text">{{ $message }}</div>@enderror
                </div>
            </div>

            {{-- Row 3: Kategori Penggunaan & Ukuran --}}
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem; margin-bottom:1rem;">
                <div class="form-group" style="margin-bottom:0;">
                    <label for="fi_katpenggunaan">Kategori Penggunaan <span style="color:var(--color-danger);">*</span></label>
                    <input type="text" id="fi_katpenggunaan" name="kategori_penggunaan" class="form-control @error('kategori_penggunaan') is-invalid @enderror" placeholder="Cth: Produksi, Kantor" value="{{ old('kategori_penggunaan') }}" required>
                    @error('kategori_penggunaan')<div class="error-text">{{ $message }}</div>@enderror
                </div>
                <div class="form-group" style="margin-bottom:0;">
                    <label for="fi_katukuran">Kategori Ukuran <span style="color:var(--color-danger);">*</span></label>
                    <input type="text" id="fi_katukuran" name="kategori_ukuran" class="form-control @error('kategori_ukuran') is-invalid @enderror" placeholder="Cth: Kecil, Sedang, Besar" value="{{ old('kategori_ukuran') }}" required>
                    @error('kategori_ukuran')<div class="error-text">{{ $message }}</div>@enderror
                </div>
            </div>

            {{-- Row 4: Min, Titik Order, Max, Lead Time --}}
            <div style="display:grid; grid-template-columns:1fr 1fr 1fr 1fr; gap:1rem; margin-bottom:1rem;">
                <div class="form-group" style="margin-bottom:0;">
                    <label for="fi_min">Min <span style="color:var(--color-danger);">*</span></label>
                    <input type="number" id="fi_min" name="min" class="form-control @error('min') is-invalid @enderror" placeholder="0" min="0" value="{{ old('min') }}" required>
                    @error('min')<div class="error-text">{{ $message }}</div>@enderror
                </div>
                <div class="form-group" style="margin-bottom:0;">
                    <label for="fi_titik">Titik Order <span style="color:var(--color-danger);">*</span></label>
                    <input type="number" id="fi_titik" name="titik_order" class="form-control @error('titik_order') is-invalid @enderror" placeholder="0" min="0" value="{{ old('titik_order') }}" required>
                    @error('titik_order')<div class="error-text">{{ $message }}</div>@enderror
                </div>
                <div class="form-group" style="margin-bottom:0;">
                    <label for="fi_max">Max <span style="color:var(--color-danger);">*</span></label>
                    <input type="number" id="fi_max" name="max" class="form-control @error('max') is-invalid @enderror" placeholder="0" min="0" value="{{ old('max') }}" required>
                    @error('max')<div class="error-text">{{ $message }}</div>@enderror
                </div>
                <div class="form-group" style="margin-bottom:0;">
                    <label for="fi_lead">Lead Time <span style="color:var(--color-danger);">*</span></label>
                    <input type="text" id="fi_lead" name="lead_time" class="form-control @error('lead_time') is-invalid @enderror" placeholder="Cth: 3 Hari" value="{{ old('lead_time') }}" required>
                    @error('lead_time')<div class="error-text">{{ $message }}</div>@enderror
                </div>
            </div>

            {{-- Row 5: Kategori B3 / NON B3 --}}
            <div style="margin-bottom:1.5rem;">
                <label style="display:block; font-size:0.875rem; font-weight:600; margin-bottom:0.75rem;">Kategori <span style="color:var(--color-danger);">*</span></label>
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
                @error('kategori')<div class="error-text" style="margin-top:0.35rem;">{{ $message }}</div>@enderror
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

{{-- ===== MODAL: KONFIRMASI TAMBAH ITEM LAGI ===== --}}
<div class="modal" id="addMorePromptModal">
    <div class="modal-content" style="max-width: 480px; text-align: center; padding: 2.25rem 1.75rem; border-radius: var(--radius-lg);">
        <div style="width: 68px; height: 68px; border-radius: 50%; background: var(--color-success-light); color: var(--color-success); display: flex; align-items: center; justify-content: center; margin: 0 auto 1.25rem; box-shadow: 0 0 20px rgba(16, 185, 129, 0.2);">
            <svg viewBox="0 0 24 24" width="36" height="36" stroke="currentColor" stroke-width="2.5" fill="none">
                <polyline points="20 6 9 17 4 12"></polyline>
            </svg>
        </div>
        <h3 style="font-family: var(--font-heading); font-weight: 700; color: var(--text-primary); margin-bottom: 0.5rem; font-size: 1.3rem;">
            Data Barang Berhasil Disimpan!
        </h3>
        <p style="color: var(--text-muted); font-size: 0.9rem; line-height: 1.5; margin-bottom: 1.75rem;">
            Apakah Anda ingin menambahkan item barang consumable lainnya ke dalam form ini?
        </p>
        <div style="display: flex; gap: 0.75rem; justify-content: center;">
            <button type="button" class="btn btn-secondary" onclick="closeModal('addMorePromptModal')" style="padding: 0.65rem 1.25rem; font-weight: 600; min-width: 120px;">
                Tidak, Selesai
            </button>
            <button type="button" class="btn btn-primary" onclick="closeModal('addMorePromptModal'); openModal('addItemModal');" style="padding: 0.65rem 1.25rem; font-weight: 700; display: inline-flex; align-items: center; gap: 0.4rem; min-width: 170px;">
                <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2.5" fill="none"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                Ya, Tambah Item Lagi
            </button>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    function openModal(id) {
        if (id === 'addItemModal') {
            const hiddenFormNo = document.getElementById('modal_form_number');
            if (hiddenFormNo) {
                hiddenFormNo.value = selectedChecksheetId;
            }
        }
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

    // Auto-open prompt modal konfirmasi tambah item lagi setelah simpan
    @if(session('show_add_more_prompt'))
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                openModal('addMorePromptModal');
            }, 300);
        });
    @endif

    // Auto-open modal jika ada validation error dari form tambah data
    @if($errors->any())
        document.addEventListener('DOMContentLoaded', function() {
            openModal('addItemModal');
        });
    @endif

    // TAB SYSTEM & MULTI-FORM ENGINE
    const serverFormItems = @json($formItems);
    const urlFormParam = '{{ $activeFormNoParam ?? "" }}';
    const userTag = '{{ strtoupper(Auth::user()->department ?? Auth::user()->name ?? "PRODUCTION") }}';
    const monthYearStr = '{{ date("m-Y") }}';
    const defaultFormNo = `01/${userTag}/${monthYearStr}`;
    
    // Distinct existing form numbers in server items
    const existingForms = [...new Set(serverFormItems.map(i => i.form_number).filter(Boolean))];
    const activeFormNo = (urlFormParam && (existingForms.includes(urlFormParam) || serverFormItems.length === 0))
        ? urlFormParam
        : (existingForms[0] || defaultFormNo);

    let activeChecksheetHtml = '';
    let selectedChecksheetId = activeFormNo;

    const emptyTableHtml = `
        <tr class="empty-state-row no-print">
            <td colspan="15" style="padding: 0; border: none;">
                <div class="empty-state-wrapper">
                    <div class="empty-state-card">
                        <div class="empty-state-icon-container">
                            <svg viewBox="0 0 24 24" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                <polyline points="14 2 14 8 20 8"></polyline>
                                <line x1="12" y1="18" x2="12" y2="12"></line>
                                <line x1="9" y1="15" x2="15" y2="15"></line>
                            </svg>
                            <div class="empty-state-pulse"></div>
                        </div>
                        <div>
                            <h4 class="empty-state-title">Belum Ada Data Barang Consumable</h4>
                            <p class="empty-state-desc">Formulir pendaftaran ini masih kosong. Klik tombol di bawah atau gunakan tombol <strong>Tambah Data</strong> untuk mengisi lembar barang.</p>
                        </div>
                        <div class="empty-state-actions">
                            <button type="button" class="empty-state-btn" onclick="openModal('addItemModal')">
                                <svg viewBox="0 0 24 24" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="12" y1="5" x2="12" y2="19"></line>
                                    <line x1="5" y1="12" x2="19" y2="12"></line>
                                </svg>
                                + Tambah Data Pertama
                            </button>
                        </div>
                        <div class="empty-state-pills">
                            <div class="empty-state-pill">
                                <svg viewBox="0 0 24 24" stroke="var(--color-success)" stroke-width="2.5" fill="none"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                Kategori B3 / Non-B3
                            </div>
                            <div class="empty-state-pill">
                                <svg viewBox="0 0 24 24" stroke="var(--color-primary)" stroke-width="2.5" fill="none"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path></svg>
                                No. Form Otomatis
                            </div>
                            <div class="empty-state-pill">
                                <svg viewBox="0 0 24 24" stroke="var(--color-warning)" stroke-width="2.5" fill="none"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                                Approval Real-time
                            </div>
                        </div>
                    </div>
                </div>
            </td>
        </tr>
        ${Array.from({length: 13}, (_, p) => `
            <tr class="print-only-row ${p % 2 !== 0 ? 'tr-even' : ''}">
                <td class="td-center td-no" style="color:#cbd5e1;">${p + 1}</td>
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
                <td class="td-input"></td>
            </tr>
        `).join('')}
    `;

    const checksheets = {};
    const stepperData = {};

    // Populate checksheets dynamically from server database items
    if (serverFormItems.length > 0) {
        serverFormItems.forEach(item => {
            const fNo = item.form_number || defaultFormNo;
            if (!checksheets[fNo]) {
                checksheets[fNo] = {
                    docNo: 'No Doc : W1-CDS-PP-20/F1 Rev 2 &nbsp;|&nbsp; No. Form: <span style="font-weight:700; color:var(--color-primary);">' + fNo + '</span>',
                    formNo: fNo,
                    date: '{{ date("d-m-Y") }}',
                    requestor: '{{ Auth::user()->name ?? "Production User" }} / {{ Auth::user()->department ?? "Production" }}',
                    status: 'Draft',
                    items: [],
                    signatures: {
                        dibuat: '{{ Auth::user()->name ?? "Production User" }} (Tgl: {{ date("d-m-Y") }})',
                        diperiksa: '...................',
                        disetujui: '...................'
                    },
                    comments: { user: 'Formulir pendaftaran barang consumable.', pemeriksa: '', warehouse: '' }
                };

                stepperData[fNo] = {
                    statusText: 'DRAFT',
                    statusClass: 'badge-warning',
                    steps: [
                        { completed: true, active: false, details: '{{ Auth::user()->name ?? "Production User" }} - Production (Tanggal: {{ date("d-m-Y") }})', status: 'Selesai dibuat.', color: 'var(--color-success)' },
                        { completed: false, active: true, details: 'Menunggu pemeriksaan Pemeriksa...', status: 'Pemeriksaan kelayakan.', color: 'var(--color-primary)' },
                        { completed: false, active: false, details: 'Belum diisi.', status: 'Registrasi Warehouse.', color: 'var(--text-muted)' }
                    ]
                };
            }

            checksheets[fNo].items.push({
                no: checksheets[fNo].items.length + 1,
                kode: item.kode_barang || '-',
                nama: item.nama_barang,
                harga: item.harga ? 'Rp ' + Number(item.harga).toLocaleString('id-ID') : '-',
                usia: item.estimasi_usia_pakai ? (!isNaN(Number(item.estimasi_usia_pakai)) ? item.estimasi_usia_pakai + ' Hari' : item.estimasi_usia_pakai) : '-',
                katPeng: item.kategori_penggunaan || '-',
                katUk: item.kategori_ukuran || '-',
                min: item.min || '-',
                titik: item.titik_order || '-',
                max: item.max || '-',
                lead: item.lead_time || '-',
                aset: item.kategori_aset || 'NO ASET',
                b3: item.is_b3,
                non_b3: item.is_non_b3
            });
        });
    } else {
        checksheets[defaultFormNo] = {
            docNo: 'No Doc : W1-CDS-PP-20/F1 Rev 2 &nbsp;|&nbsp; No. Form: <span style="font-weight:700; color:var(--color-primary);">' + defaultFormNo + '</span>',
            formNo: defaultFormNo,
            date: '{{ date("d-m-Y") }}',
            requestor: '{{ Auth::user()->name ?? "Production User" }} / {{ Auth::user()->department ?? "Production" }}',
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
        };

        stepperData[defaultFormNo] = {
            statusText: 'DRAFT',
            statusClass: 'badge-warning',
            steps: [
                { completed: true, active: false, details: '{{ Auth::user()->name ?? "Production User" }} - Production (Tanggal: {{ date("d-m-Y") }})', status: 'Selesai dibuat dan ditandatangani.', color: 'var(--color-success)' },
                { completed: false, active: true, details: 'Menunggu kelayakan disetujui Pemeriksa...', status: 'Pemeriksaan kelayakan.', color: 'var(--color-primary)' },
                { completed: false, active: false, details: 'Belum diisi.', status: 'Registrasi oleh Warehouse.', color: 'var(--text-muted)' }
            ]
        };
    }

    const monthNamesIndo = {
        '01': 'Januari', '02': 'Februari', '03': 'Maret', '04': 'April',
        '05': 'Mei', '06': 'Juni', '07': 'Juli', '08': 'Agustus',
        '09': 'September', '10': 'Oktober', '11': 'November', '12': 'Desember'
    };

    function populateDateFilterOptions() {
        const yearSelect = document.getElementById('filter-year-dataview');
        if (!yearSelect) return;

        const currentYearVal = yearSelect.value;
        const yearsFound = new Set();

        for (const formNo in checksheets) {
            const cs = checksheets[formNo];
            if (!cs.items || cs.items.length === 0) continue;

            let y = '';
            if (cs.date && cs.date.includes('-')) {
                const parts = cs.date.split('-');
                if (parts.length === 3) y = parts[2];
            }
            if (!y && cs.formNo) {
                const parts = cs.formNo.split('/');
                if (parts.length >= 3) {
                    const myParts = parts[2].split('-');
                    if (myParts.length === 2) y = myParts[1];
                }
            }
            if (y) yearsFound.add(y);
        }

        const nowYr = '{{ date("Y") }}';
        yearsFound.add(nowYr);

        const sortedYears = Array.from(yearsFound).sort((a, b) => b.localeCompare(a));

        yearSelect.innerHTML = '<option value="">Semua Tahun</option>';
        sortedYears.forEach(yr => {
            const opt = document.createElement('option');
            opt.value = yr;
            opt.innerText = yr;
            if (yr === currentYearVal) opt.selected = true;
            yearSelect.appendChild(opt);
        });
    }

    function updateApprovalSelect() {
        const select = document.getElementById('select-approval-cs');
        if (!select) return;

        select.innerHTML = '';
        for (const formNo in checksheets) {
            const cs = checksheets[formNo];
            if ((cs.items && cs.items.length > 0) || formNo === selectedChecksheetId) {
                const opt = document.createElement('option');
                opt.value = formNo;
                const itemLabel = (cs.items && cs.items.length > 0) ? `${cs.items.length} Item` : 'Draft Baru';
                opt.innerText = `${formNo} (${userTag} - ${itemLabel})`;
                if (formNo === selectedChecksheetId) {
                    opt.selected = true;
                }
                select.appendChild(opt);
            }
        }
    }

    function renderDataViewTable() {
        const tbody = document.getElementById('dataview-tbody');
        if (!tbody) return;

        const monthSelect = document.getElementById('filter-month-dataview');
        const yearSelect = document.getElementById('filter-year-dataview');
        const selectedMonth = monthSelect ? monthSelect.value : '';
        const selectedYear = yearSelect ? yearSelect.value : '';

        let html = '';
        let no = 1;
        let totalChecksheets = 0;
        let approvedChecksheets = 0;
        let processChecksheets = 0;

        for (const formNo in checksheets) {
            const cs = checksheets[formNo];

            // Filter out empty checksheets (0 items)
            if (!cs.items || cs.items.length === 0) {
                continue;
            }

            // Extract month and year from cs.date or cs.formNo
            let csM = '';
            let csY = '';
            if (cs.date && cs.date.includes('-')) {
                const parts = cs.date.split('-');
                if (parts.length === 3) {
                    csM = parts[1];
                    csY = parts[2];
                }
            }
            if ((!csM || !csY) && cs.formNo) {
                const parts = cs.formNo.split('/');
                if (parts.length >= 3) {
                    const myParts = parts[2].split('-');
                    if (myParts.length === 2) {
                        if (!csM) csM = myParts[0];
                        if (!csY) csY = myParts[1];
                    }
                }
            }

            // Month and Year filtering
            if (selectedMonth && csM !== selectedMonth) {
                continue;
            }
            if (selectedYear && csY !== selectedYear) {
                continue;
            }

            totalChecksheets++;

            let statusBg = 'var(--color-warning-light)';
            let statusColor = 'var(--color-warning)';
            let displayStatus = cs.status.toUpperCase();

            if (cs.status === 'Approved' || cs.status === 'Selesai') {
                statusBg = 'var(--color-success-light)';
                statusColor = 'var(--color-success)';
                approvedChecksheets++;
            } else {
                statusBg = 'rgba(59, 130, 246, 0.1)';
                statusColor = 'rgb(29, 78, 216)';
                processChecksheets++;
            }

            const itemCount = cs.items.length + ' Item';

            const actionButtonsHtml = `
                <button class="btn btn-primary btn-sm" onclick="viewChecksheet('${cs.formNo}')" style="padding: 0.35rem 0.75rem; font-size: 0.75rem; font-family: var(--font-body); font-weight: 600; border-radius: 6px;">Lihat Preview</button>
                <button class="btn btn-secondary btn-sm" onclick="printChecksheet('${cs.formNo}')" style="padding: 0.35rem 0.75rem; font-size: 0.75rem; margin-left: 0.25rem; font-family: var(--font-body); font-weight: 600; border-radius: 6px;">Cetak</button>
            `;
            const deleteColumnHtml = `
                <button class="btn btn-sm" onclick="deleteChecksheetForm('${cs.formNo}')" style="padding: 0.35rem 0.75rem; font-size: 0.75rem; font-family: var(--font-body); font-weight: 600; border-radius: 6px; background-color: #ef4444; color: white; border: none; cursor: pointer; display: inline-flex; align-items: center; gap: 0.25rem;">
                    <svg viewBox="0 0 24 24" width="13" height="13" stroke="currentColor" stroke-width="2.5" fill="none"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6l-1 14H6L5 6"></path><path d="M10 11v6M14 11v6"></path><path d="M9 6V4h6v2"></path></svg>
                    Hapus Form
                </button>
            `;

            html += `
                <tr>
                    <td class="td-center td-no">${no}</td>
                    <td style="font-weight: 700; color: var(--color-primary); padding: 0 0.8rem;">${cs.formNo}</td>
                    <td style="padding: 0 0.8rem; font-size: 0.85rem;">${cs.date}</td>
                    <td style="padding: 0 0.8rem; font-size: 0.85rem; font-weight: 600;">${cs.requestor}</td>
                    <td class="td-center" style="font-weight: 700;">${itemCount}</td>
                    <td class="td-center">
                        <span style="background-color: ${statusBg}; color: ${statusColor}; padding: 0.25rem 0.5rem; border-radius: 4px; font-weight: 700; font-size: 0.75rem; display: inline-block;">${displayStatus}</span>
                    </td>
                    <td class="td-center" style="padding: 0 0.5rem;">${actionButtonsHtml}</td>
                    <td class="td-center" style="padding: 0 0.5rem;">${deleteColumnHtml}</td>
                </tr>
            `;
            no++;
        }

        const isFiltered = Boolean(selectedMonth || selectedYear);

        if (totalChecksheets === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="8" style="padding: 0; border: none;">
                        <div class="empty-state-wrapper" style="margin: 1.25rem 0;">
                            <div class="empty-state-card" style="gap: 0.75rem;">
                                <div class="empty-state-icon-container" style="width: 60px; height: 60px;">
                                    <svg viewBox="0 0 24 24" width="28" height="28" fill="none" stroke="currentColor" stroke-width="1.6">
                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                        <polyline points="14 2 14 8 20 8"></polyline>
                                    </svg>
                                </div>
                                <h4 class="empty-state-title" style="font-size: 1rem;">${isFiltered ? 'Tidak Ada Form Registrasi pada Periode Ini' : 'Belum Ada Form Registrasi Berisi Data'}</h4>
                                <p class="empty-state-desc" style="font-size: 0.8rem;">${isFiltered ? 'Tidak ditemukan data formulir pendaftaran barang untuk filter Bulan / Tahun yang dipilih.' : 'Formulir registrasi akan tampil secara otomatis di sini setelah Anda menambahkan item barang.'}</p>
                            </div>
                        </div>
                    </td>
                </tr>
            `;
        } else {
            tbody.innerHTML = html;
        }

        const statTotal = document.getElementById('stat-total-checksheets');
        if (statTotal) statTotal.innerText = totalChecksheets;

        const statApproved = document.getElementById('stat-approved-checksheets');
        if (statApproved) statApproved.innerText = approvedChecksheets;

        const statProcess = document.getElementById('stat-process-checksheets');
        if (statProcess) statProcess.innerText = processChecksheets;
    }

    function deleteChecksheetForm(formNo) {
        if (!confirm(`Apakah Anda yakin ingin menghapus PERMANEN seluruh Form Registrasi "${formNo}" ini?`)) {
            return;
        }

        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("form-registrasi.delete-checksheet") }}';

        const csrf = document.createElement('input');
        csrf.type = 'hidden';
        csrf.name = '_token';
        csrf.value = '{{ csrf_token() }}';
        form.appendChild(csrf);

        const method = document.createElement('input');
        method.type = 'hidden';
        method.name = '_method';
        method.value = 'DELETE';
        form.appendChild(method);

        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'form_number';
        input.value = formNo;
        form.appendChild(input);

        const tabInput = document.createElement('input');
        tabInput.type = 'hidden';
        tabInput.name = 'tab';
        tabInput.value = 'data-view';
        form.appendChild(tabInput);

        document.body.appendChild(form);
        form.submit();
    }

    document.addEventListener('DOMContentLoaded', function() {
        activeChecksheetHtml = document.getElementById('preview-table-body').innerHTML;
        populateDateFilterOptions();
        renderDataViewTable();
        updateApprovalSelect();
        updateApprovalStepper(selectedChecksheetId);
        renderAccountTable();

        const urlParams = new URLSearchParams(window.location.search);
        const activeTabParam = urlParams.get('tab') || '{{ request()->query("tab") }}';

        if (urlFormParam && urlFormParam !== defaultFormNo && activeTabParam !== 'data-view') {
            viewChecksheet(urlFormParam);
        }

        if (activeTabParam === 'data-view' || window.location.hash === '#data-view') {
            switchSheet('data-view');
        } else if (activeTabParam === 'account-master' || window.location.hash === '#account-master') {
            switchSheet('account-master');
        }
    });

    function createNewForm() {
        // Clean up any empty draft forms that have no items
        for (const fNo in checksheets) {
            if (fNo !== activeFormNo && (!checksheets[fNo].items || checksheets[fNo].items.length === 0)) {
                delete checksheets[fNo];
                delete stepperData[fNo];
            }
        }

        const totalFormsCount = Object.keys(checksheets).length;
        const nextSeq = String(totalFormsCount + 1).padStart(2, '0');
        const todayStr = '{{ date("d-m-Y") }}';
        const nextFormNo = `${nextSeq}/${userTag}/${monthYearStr}`;
        
        checksheets[nextFormNo] = {
            docNo: 'No Doc : W1-CDS-PP-20/F1 Rev 2 &nbsp;|&nbsp; No. Form: <span style="font-weight:700; color:var(--color-primary);">' + nextFormNo + '</span>',
            formNo: nextFormNo,
            date: todayStr,
            requestor: '{{ Auth::user()->name ?? "Production User" }} / {{ Auth::user()->department ?? "Production" }}',
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

        updateApprovalSelect();
        renderDataViewTable();
        viewChecksheet(nextFormNo);
        openModal('addItemModal');

        showToast(`Formulir Baru (${nextFormNo}) Berhasil Dibuat! Silakan isi data barang.`, 'success');
    }

    function switchSheet(tabId) {
        document.querySelectorAll('.tab-pane').forEach(pane => pane.classList.remove('active'));
        document.querySelectorAll('.sheet-tab').forEach(tab => tab.classList.remove('active'));

        const activePane = document.getElementById(tabId + '-pane');
        if (activePane) activePane.classList.add('active');
        
        const tabs = Array.from(document.querySelectorAll('.sheet-tab'));
        const matchingTab = tabs.find(t => {
            const attr = t.getAttribute('onclick');
            return attr && attr.includes(`'${tabId}'`);
        });
        if (matchingTab) matchingTab.classList.add('active');
    }

    function viewChecksheet(csId) {
        // Clean up previous empty draft form if user switched away without adding items
        if (selectedChecksheetId && selectedChecksheetId !== csId && selectedChecksheetId !== activeFormNo) {
            const prevCs = checksheets[selectedChecksheetId];
            if (prevCs && (!prevCs.items || prevCs.items.length === 0)) {
                delete checksheets[selectedChecksheetId];
                delete stepperData[selectedChecksheetId];
                updateApprovalSelect();
            }
        }

        selectedChecksheetId = csId;
        switchSheet('print-preview');
        
        const cs = checksheets[csId];
        if (!cs) return;

        const hiddenFormNo = document.getElementById('modal_form_number');
        if (hiddenFormNo) hiddenFormNo.value = csId;

        const displayEl = document.getElementById('form-number-display');
        if (displayEl) displayEl.innerText = csId;
        
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
        if (!cs.items || cs.items.length === 0) {
            if (csId === activeFormNo) {
                tbody.innerHTML = activeChecksheetHtml;
            } else {
                tbody.innerHTML = emptyTableHtml;
            }
        } else {
            let rowsHtml = '';
            cs.items.forEach((item, index) => {
                const isEven = index % 2 !== 0;
                const rowClass = isEven ? 'tr-even' : '';
                rowsHtml += `
                    <tr class="data-row ${rowClass}">
                        <td class="td-center td-no">${item.no}</td>
                        <td class="td-center" style="padding:0 0.4rem;">
                            ${item.kode ? `<span class="item-code-badge">${item.kode}</span>` : '<span style="color:var(--text-muted); font-size:0.75rem;">-</span>'}
                        </td>
                        <td style="padding:0 0.6rem; font-weight:700; font-size:0.82rem; color:var(--text-primary);">${item.nama}</td>
                        <td class="td-center">
                            ${item.harga ? `<span class="item-price-tag">${item.harga}</span>` : '<span style="color:var(--text-muted); font-size:0.75rem;">-</span>'}
                        </td>
                        <td class="td-center" style="font-size:0.75rem; font-weight:600; color:var(--text-muted);">${item.usia || '-'}</td>
                        <td class="td-center" style="font-size:0.75rem; font-weight:600;">${item.katPeng || '-'}</td>
                        <td class="td-center" style="font-size:0.75rem; font-weight:600;">${item.katUk || '-'}</td>
                        <td class="td-center">${item.min ? `<span class="badge-stock-min">${item.min}</span>` : '-'}</td>
                        <td class="td-center">${item.titik ? `<span class="badge-stock-titik">${item.titik}</span>` : '-'}</td>
                        <td class="td-center">${item.max ? `<span class="badge-stock-max">${item.max}</span>` : '-'}</td>
                        <td class="td-center" style="font-size:0.75rem; font-weight:600;">${item.lead || '-'}</td>
                        <td class="td-center" style="vertical-align: middle;">
                            ${item.aset === 'ASET' ? '<span class="badge-asset-yes"><svg viewBox="0 0 24 24" width="11" height="11" stroke="currentColor" stroke-width="3" fill="none"><polyline points="20 6 9 17 4 12"></polyline></svg> ASET</span>' : '<span class="badge-asset-no">NO ASET</span>'}
                        </td>
                        <td class="td-center">
                            ${item.b3 ? `<div class="check-icon-b3" title="B3"><svg viewBox="0 0 24 24" width="13" height="13" stroke="currentColor" stroke-width="3" fill="none"><polyline points="20 6 9 17 4 12"></polyline></svg></div>` : ''}
                        </td>
                        <td class="td-center">
                            ${item.non_b3 ? `<div class="check-icon-b3" title="NON B3"><svg viewBox="0 0 24 24" width="13" height="13" stroke="currentColor" stroke-width="3" fill="none"><polyline points="20 6 9 17 4 12"></polyline></svg></div>` : ''}
                        </td>
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
                            <td class="td-input"></td>
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

    window.addEventListener('beforeprint', function() {
        switchSheet('print-preview');
    });

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

    // ACCOUNT MASTER DATA ENGINE - Dynamic from Database
    const userAccounts = [
        @if(isset($users) && $users->count() > 0)
            @foreach($users as $u)
            {
                id: {{ $u->id }},
                username: '{{ $u->email }}',
                name: '{{ $u->name }}',
                dept: '{{ $u->department ?? "Production" }}',
                role: '{{ $u->role ?? "User" }}',
                status: '{{ $u->status ?? "Aktif" }}',
                date: '{{ $u->created_at ? $u->created_at->format("d-m-Y") : date("d-m-Y") }}'
            },
            @endforeach
        @endif
    ];

    function renderAccountTable() {
        const tbody = document.getElementById('accounts-tbody');
        if (!tbody) return;

        let html = '';
        let userCount = 0;
        let staffCount = 0;
        let accCount = 0;
        let whCount = 0;

        userAccounts.forEach((acc, index) => {
            let roleBadge = '';
            const rLower = (acc.role || '').toLowerCase();

            if (rLower === 'user') {
                userCount++;
                roleBadge = `<span style="background-color: rgba(59, 130, 246, 0.15); color: rgb(29, 78, 216); padding: 0.25rem 0.6rem; border-radius: 4px; font-weight: 700; font-size: 0.75rem;">User</span>`;
            } else if (rLower === 'staff') {
                staffCount++;
                roleBadge = `<span style="background-color: var(--color-warning-light); color: var(--color-warning); padding: 0.25rem 0.6rem; border-radius: 4px; font-weight: 700; font-size: 0.75rem;">Staff</span>`;
            } else if (rLower === 'accounting') {
                accCount++;
                roleBadge = `<span style="background-color: rgba(168, 85, 247, 0.15); color: #9333ea; padding: 0.25rem 0.6rem; border-radius: 4px; font-weight: 700; font-size: 0.75rem;">Accounting</span>`;
            } else if (rLower === 'warehouse consumable' || rLower === 'warehouse') {
                whCount++;
                roleBadge = `<span style="background-color: var(--color-success-light); color: var(--color-success); padding: 0.25rem 0.6rem; border-radius: 4px; font-weight: 700; font-size: 0.75rem;">Warehouse Consumable</span>`;
            } else {
                roleBadge = `<span style="background-color: rgba(100, 116, 139, 0.15); color: #475569; padding: 0.25rem 0.6rem; border-radius: 4px; font-weight: 700; font-size: 0.75rem;">${acc.role}</span>`;
            }

            const jsonAcc = JSON.stringify(acc).replace(/'/g, "&apos;").replace(/"/g, "&quot;");

            html += `
                <tr>
                    <td class="td-center td-no">${index + 1}</td>
                    <td style="font-weight: 700; color: var(--text-primary); padding: 0 0.8rem;">
                        ${acc.name}
                        <div style="font-size:0.75rem; color:var(--text-muted); font-weight:normal;">Username: <strong>${acc.username}</strong></div>
                    </td>
                    <td style="padding: 0 0.8rem; font-size: 0.85rem; font-weight: 600;">${acc.dept}</td>
                    <td class="td-center">${roleBadge}</td>
                    <td class="td-center"><span style="background-color: var(--color-success-light); color: var(--color-success); padding: 0.2rem 0.5rem; border-radius: 4px; font-weight: 700; font-size: 0.7rem;">${acc.status}</span></td>
                    <td class="td-center" style="font-size: 0.85rem;">${acc.date}</td>
                    <td class="td-center" style="padding: 0 0.5rem;">
                        <button class="btn btn-secondary btn-sm" onclick="openEditUserModal(${jsonAcc})" style="padding: 0.25rem 0.55rem; font-size: 0.75rem; border-radius: 4px;">Edit</button>
                        <form action="/users/${acc.id}" method="POST" style="display:inline;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun user &quot;${acc.name}&quot;?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" style="padding: 0.25rem 0.55rem; font-size: 0.75rem; border-radius: 4px; background-color: #ef4444; color: white; border: none; margin-left: 0.2rem;">Hapus</button>
                        </form>
                    </td>
                </tr>
            `;
        });

        if (userAccounts.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="7" style="text-align: center; padding: 1.5rem; color: var(--text-muted);">Belum ada akun pengguna terdaftar. Klik "+ Buat Akun Baru" untuk menambahkan pengguna.</td>
                </tr>
            `;
        } else {
            tbody.innerHTML = html;
        }

        const statTotal = document.getElementById('stat-total-accounts');
        if (statTotal) statTotal.innerText = `${userAccounts.length} Akun`;

        const statUser = document.getElementById('stat-user-accounts-count');
        if (statUser) statUser.innerText = `${userCount} Akun`;

        const statStaff = document.getElementById('stat-staff-accounts-count');
        if (statStaff) statStaff.innerText = `${staffCount} Akun`;

        const statAccWh = document.getElementById('stat-acc-wh-accounts-count');
        if (statAccWh) statAccWh.innerText = `${accCount + whCount} Akun`;
    }

    function openEditUserModal(acc) {
        const form = document.getElementById('form-edit-account');
        if (!form) return;
        form.action = `/users/${acc.id}`;
        document.getElementById('acc_edit_name').value = acc.name || '';
        document.getElementById('acc_edit_username').value = acc.username || '';

        const deptSelect = document.getElementById('acc_edit_dept');
        if (deptSelect) deptSelect.value = acc.dept || 'Production';

        const roleSelect = document.getElementById('acc_edit_role');
        if (roleSelect) roleSelect.value = acc.role || 'User';

        document.getElementById('acc_edit_password').value = '';
        openModal('editAccountModal');
    }
</script>
@endsection
