@extends('layouts.app')

@section('title', 'Dashboard Registrasi Barang')

@section('content')
<div class="header">
    <div class="header-title">
        <h1>Registrasi & Unregistrasi Barang</h1>
        <p>Dashboard monitoring dan pengelolaan status pendaftaran barang.</p>
    </div>
</div>

<!-- Statistics Cards -->
<div class="stats-grid">
    <div class="stat-card total">
        <div class="stat-icon">
            <svg viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
            </svg>
        </div>
        <div class="stat-info">
            <span class="stat-label">Total Barang</span>
            <span class="stat-value">{{ $stats['total'] }}</span>
        </div>
    </div>
    
    <div class="stat-card registered">
        <div class="stat-icon">
            <svg viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                <polyline points="22 4 12 14.01 9 11.01"></polyline>
            </svg>
        </div>
        <div class="stat-info">
            <span class="stat-label">Barang Terdaftar</span>
            <span class="stat-value">{{ $stats['registered'] }}</span>
        </div>
    </div>
    
    <div class="stat-card unregistered">
        <div class="stat-icon">
            <svg viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="15" y1="9" x2="9" y2="15"></line>
                <line x1="9" y1="9" x2="15" y2="15"></line>
            </svg>
        </div>
        <div class="stat-info">
            <span class="stat-label">Unregistrasi (Batal)</span>
            <span class="stat-value">{{ $stats['unregistered'] }}</span>
        </div>
    </div>
    
    <div class="stat-card consumables">
        <div class="stat-icon">
            <svg viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"></path>
            </svg>
        </div>
        <div class="stat-info">
            <span class="stat-label">Kategori Consumable</span>
            <span class="stat-value">{{ $stats['consumables'] }}</span>
        </div>
    </div>
</div>

<!-- Main Layout Grid -->
<div class="dashboard-grid">
    
    <!-- Left Column: Registration Form -->
    <div class="glass-card">
        <div class="glass-card-title">
            <span>Pendaftaran Barang Baru</span>
            <svg viewBox="0 0 24 24" width="20" height="20" stroke="var(--color-primary)" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 5v14M5 12h14"></path>
            </svg>
        </div>
        
        <form action="{{ route('items.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="name">Nama Barang</label>
                <input type="text" id="name" name="name" class="form-control" placeholder="Contoh: Kertas A4, Mouse, Keyboard" value="{{ old('name') }}" required>
            </div>
            
            <div class="form-group">
                <label for="category">Kategori</label>
                <select id="category" name="category" class="form-control" required>
                    <option value="" disabled selected>Pilih Kategori</option>
                    <option value="Consumable" {{ old('category') == 'Consumable' ? 'selected' : '' }}>Consumable (Habis Pakai)</option>
                    <option value="Asset" {{ old('category') == 'Asset' ? 'selected' : '' }}>Asset (Inventaris)</option>
                    <option value="Electronics" {{ old('category') == 'Electronics' ? 'selected' : '' }}>Electronics</option>
                    <option value="Office Stationery" {{ old('category') == 'Office Stationery' ? 'selected' : '' }}>Office Stationery (ATK)</option>
                    <option value="Others" {{ old('category') == 'Others' ? 'selected' : '' }}>Lainnya</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="description">Deskripsi / Catatan</label>
                <textarea id="description" name="description" class="form-control" placeholder="Spesifikasi, serial number, atau detail lainnya (Opsional)">{{ old('description') }}</textarea>
            </div>
            
            <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 1rem;">
                <svg viewBox="0 0 24 24" width="18" height="18" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                    <polyline points="17 21 17 13 7 13 7 21"></polyline>
                    <polyline points="7 3 7 8 15 8"></polyline>
                </svg>
                Daftarkan Barang
            </button>
        </form>
    </div>
    
    <!-- Right Column: Database List -->
    <div class="glass-card">
        <div class="glass-card-title">
            <span>Daftar Registrasi Barang</span>
            <svg viewBox="0 0 24 24" width="20" height="20" stroke="var(--text-muted)" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <line x1="8" y1="6" x2="21" y2="6"></line>
                <line x1="8" y1="12" x2="21" y2="12"></line>
                <line x1="8" y1="18" x2="21" y2="18"></line>
                <line x1="3" y1="6" x2="3.01" y2="6"></line>
                <line x1="3" y1="12" x2="3.01" y2="12"></line>
                <line x1="3" y1="18" x2="3.01" y2="18"></line>
            </svg>
        </div>
        
        <!-- Search and Filters -->
        <form action="{{ route('dashboard') }}" method="GET" class="table-toolbar">
            <div class="search-wrapper">
                <svg viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
                <input type="text" name="search" class="form-control" placeholder="Cari nama atau kode barang..." value="{{ $search }}">
            </div>
            
            <div class="filters-wrapper">
                <select name="status" class="select-filter" onchange="this.form.submit()">
                    <option value="">Semua Status</option>
                    <option value="registered" {{ $status == 'registered' ? 'selected' : '' }}>Terdaftar</option>
                    <option value="unregistered" {{ $status == 'unregistered' ? 'selected' : '' }}>Unregistrasi</option>
                </select>

                <select name="category" class="select-filter" onchange="this.form.submit()">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat }}" {{ $category == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                    @endforeach
                </select>
                
                @if($search || $status || $category)
                    <a href="{{ route('dashboard') }}" class="btn btn-secondary btn-sm" style="height: 42px; display: inline-flex; align-items: center; justify-content: center; font-family: var(--font-body); font-weight: 500;">
                        Reset
                    </a>
                @endif
            </div>
        </form>
        
        <!-- Items Table -->
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Kode Barang</th>
                        <th>Nama Barang</th>
                        <th>Kategori</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $item)
                        <tr>
                            <td style="font-family: var(--font-heading); font-weight: 600; color: var(--color-primary);">
                                {{ $item->item_code }}
                            </td>
                            <td>
                                <div style="font-weight: 600;">{{ $item->name }}</div>
                                <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 0.15rem;">
                                    Reg: {{ $item->registered_at->timezone('Asia/Jakarta')->format('d M Y, H:i') }}
                                </div>
                            </td>
                            <td>
                                <span class="badge" style="background-color: rgba(0,0,0,0.03); color: var(--text-primary); border: 1px solid rgba(0,0,0,0.05); font-weight: 600;">
                                    {{ $item->category }}
                                </span>
                            </td>
                            <td>
                                @if($item->status == 'registered')
                                    <span class="badge badge-success">
                                        <svg viewBox="0 0 24 24" width="10" height="10" stroke="currentColor" stroke-width="3" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <polyline points="20 6 9 17 4 12"></polyline>
                                        </svg>
                                        Terdaftar
                                    </span>
                                @else
                                    <span class="badge badge-danger">
                                        <svg viewBox="0 0 24 24" width="10" height="10" stroke="currentColor" stroke-width="3" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <line x1="18" y1="6" x2="6" y2="18"></line>
                                            <line x1="6" y1="6" x2="18" y2="18"></line>
                                        </svg>
                                        Unregistrasi
                                    </span>
                                @endif
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn btn-secondary btn-sm" 
                                            onclick="openDetailModal(this)"
                                            data-code="{{ $item->item_code }}"
                                            data-name="{{ $item->name }}"
                                            data-category="{{ $item->category }}"
                                            data-desc="{{ $item->description ?? '-' }}"
                                            data-status="{{ $item->status }}"
                                            data-reg="{{ $item->registered_at->timezone('Asia/Jakarta')->format('d F Y, H:i') }}"
                                            data-unreg="{{ $item->unregistered_at ? $item->unregistered_at->timezone('Asia/Jakarta')->format('d F Y, H:i') : '-' }}"
                                            data-reason="{{ $item->unregistration_reason ?? '-' }}">
                                        Detail
                                    </button>
                                    
                                    @if($item->status == 'registered')
                                        <button class="btn btn-danger btn-sm" 
                                                onclick="openUnregisterModal('{{ $item->id }}', '{{ addslashes($item->name) }}', '{{ $item->item_code }}')">
                                            Unregistrasi
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="padding: 0; border: none;">
                                <div class="empty-state-wrapper">
                                    <div class="empty-state-card">
                                        <div class="empty-state-icon-container">
                                            <svg viewBox="0 0 24 24" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6">
                                                <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                                                <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                                                <line x1="12" y1="22.08" x2="12" y2="12"></line>
                                            </svg>
                                            <div class="empty-state-pulse"></div>
                                        </div>
                                        <div>
                                            <h4 class="empty-state-title">Tidak Ada Data Barang Consumable</h4>
                                            <p class="empty-state-desc">Belum ada barang consumable yang terdaftar dalam sistem. Gunakan form pendaftaran untuk menambahkan barang baru.</p>
                                        </div>
                                        <div class="empty-state-actions">
                                            <a href="{{ route('form-registrasi') }}" class="empty-state-btn" style="text-decoration: none;">
                                                <svg viewBox="0 0 24 24" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                    <line x1="12" y1="5" x2="12" y2="19"></line>
                                                    <line x1="5" y1="12" x2="19" y2="12"></line>
                                                </svg>
                                                + Buka Form Pendaftaran
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination Links -->
        @if($items->hasPages())
            <div class="pagination-wrapper">
                <nav>
                    <div class="pagination-info">
                        Menampilkan {{ $items->firstItem() ?? 0 }} - {{ $items->lastItem() ?? 0 }} dari {{ $items->total() }} barang
                    </div>
                    
                    <ul class="pagination-links">
                        {{-- Previous Page Link --}}
                        @if ($items->onFirstPage())
                            <li class="disabled"><span>&laquo;</span></li>
                        @else
                            <li><a href="{{ $items->previousPageUrl() }}" rel="prev">&laquo;</a></li>
                        @endif

                        {{-- Pagination Elements --}}
                        @foreach ($items->getUrlRange(1, $items->lastPage()) as $page => $url)
                            @if ($page == $items->currentPage())
                                <li class="active"><span>{{ $page }}</span></li>
                            @else
                                <li><a href="{{ $url }}">{{ $page }}</a></li>
                            @endif
                        @endforeach

                        {{-- Next Page Link --}}
                        @if ($items->hasMorePages())
                            <li><a href="{{ $items->nextPageUrl() }}" rel="next">&raquo;</a></li>
                        @else
                            <li class="disabled"><span>&raquo;</span></li>
                        @endif
                    </ul>
                </nav>
            </div>
        @endif
    </div>
</div>

<!-- Modal: Unregister Item -->
<div class="modal" id="unregisterModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Batalkan Registrasi Barang</h3>
            <button class="btn-close" onclick="closeModal('unregisterModal')">&times;</button>
        </div>
        <form id="unregisterForm" method="POST">
            @csrf
            <p style="font-size: 0.95rem; line-height: 1.5; margin-bottom: 1.25rem; color: #475569;">
                Apakah Anda yakin ingin membatalkan registrasi untuk barang <strong id="unregItemName" style="color: var(--text-primary);"></strong> (<span id="unregItemCode" style="color: var(--color-primary); font-weight: 600;"></span>)?
            </p>
            <div class="form-group">
                <label for="unregistration_reason">Alasan Pembatalan Registrasi</label>
                <textarea id="unregistration_reason" name="unregistration_reason" class="form-control" placeholder="Tulis alasan pembatalan registrasi barang..." required></textarea>
            </div>
            <div style="display: flex; gap: 0.75rem; justify-content: flex-end; margin-top: 1.5rem;">
                <button type="button" class="btn btn-secondary" onclick="closeModal('unregisterModal')">Kembali</button>
                <button type="submit" class="btn btn-danger">Ya, Batalkan</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal: Item Detail -->
<div class="modal" id="detailModal">
    <div class="modal-content" style="max-width: 550px;">
        <div class="modal-header">
            <h3 style="display: flex; align-items: center; gap: 0.5rem;">
                <span>Detail Informasi Barang</span>
            </h3>
            <button class="btn-close" onclick="closeModal('detailModal')">&times;</button>
        </div>
        
        <div style="display: flex; flex-direction: column; gap: 1rem; margin-top: 0.5rem;">
            <div style="display: grid; grid-template-columns: 1fr 2fr; border-bottom: 1px solid rgba(0,0,0,0.05); padding-bottom: 0.75rem;">
                <span style="font-weight: 700; color: var(--text-muted); font-size: 0.85rem;">KODE BARANG</span>
                <strong id="detCode" style="font-family: var(--font-heading); color: var(--color-primary); font-size: 1.1rem;"></strong>
            </div>
            
            <div style="display: grid; grid-template-columns: 1fr 2fr; border-bottom: 1px solid rgba(0,0,0,0.05); padding-bottom: 0.75rem;">
                <span style="font-weight: 700; color: var(--text-muted); font-size: 0.85rem;">NAMA BARANG</span>
                <span id="detName" style="font-weight: 600;"></span>
            </div>
            
            <div style="display: grid; grid-template-columns: 1fr 2fr; border-bottom: 1px solid rgba(0,0,0,0.05); padding-bottom: 0.75rem;">
                <span style="font-weight: 700; color: var(--text-muted); font-size: 0.85rem;">KATEGORI</span>
                <span><span id="detCategory" class="badge" style="background-color: rgba(0,0,0,0.04); font-weight: 600;"></span></span>
            </div>
            
            <div style="display: grid; grid-template-columns: 1fr 2fr; border-bottom: 1px solid rgba(0,0,0,0.05); padding-bottom: 0.75rem;">
                <span style="font-weight: 700; color: var(--text-muted); font-size: 0.85rem;">STATUS</span>
                <span id="detStatusBadge"></span>
            </div>
            
            <div style="display: grid; grid-template-columns: 1fr 2fr; border-bottom: 1px solid rgba(0,0,0,0.05); padding-bottom: 0.75rem;">
                <span style="font-weight: 700; color: var(--text-muted); font-size: 0.85rem;">TANGGAL DAFTAR</span>
                <span id="detRegDate"></span>
            </div>
            
            <div style="display: grid; grid-template-columns: 1fr 2fr; border-bottom: 1px solid rgba(0,0,0,0.05); padding-bottom: 0.75rem;">
                <span style="font-weight: 700; color: var(--text-muted); font-size: 0.85rem;">DESKRIPSI</span>
                <span id="detDesc" style="color: #475569; line-height: 1.5; white-space: pre-line;"></span>
            </div>

            <!-- Conditional Unregistration Details Section -->
            <div id="unregDetailsSection" style="display: none; background-color: var(--color-danger-light); padding: 1rem; border-radius: var(--radius-md); border: 1px solid rgba(239, 68, 68, 0.15);">
                <div style="display: grid; grid-template-columns: 1fr 2fr; border-bottom: 1px solid rgba(239, 68, 68, 0.1); padding-bottom: 0.5rem; margin-bottom: 0.5rem;">
                    <span style="font-weight: 700; color: var(--color-danger); font-size: 0.85rem;">TANGGAL BATAL</span>
                    <span id="detUnregDate" style="font-weight: 600; color: var(--color-danger);"></span>
                </div>
                <div style="display: flex; flex-direction: column; gap: 0.25rem;">
                    <span style="font-weight: 700; color: var(--color-danger); font-size: 0.85rem;">ALASAN PEMBATALAN</span>
                    <p id="detReason" style="font-size: 0.9rem; color: #7f1d1d; line-height: 1.4; white-space: pre-line;"></p>
                </div>
            </div>
        </div>
        
        <div style="display: flex; justify-content: flex-end; margin-top: 1.5rem;">
            <button type="button" class="btn btn-secondary" onclick="closeModal('detailModal')">Tutup</button>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    // Modal Helpers
    function openModal(id) {
        document.getElementById(id).classList.add('show');
    }

    function closeModal(id) {
        document.getElementById(id).classList.remove('show');
    }

    // Close modal if user clicks outside content
    window.onclick = function(event) {
        if (event.target.classList.contains('modal')) {
            event.target.classList.remove('show');
        }
    }

    // Configure and Open Unregistration Modal
    function openUnregisterModal(itemId, itemName, itemCode) {
        const form = document.getElementById('unregisterForm');
        form.action = `/items/${itemId}/unregister`;
        document.getElementById('unregItemName').innerText = itemName;
        document.getElementById('unregItemCode').innerText = itemCode;
        document.getElementById('unregistration_reason').value = '';
        openModal('unregisterModal');
    }

    // Configure and Open Detail Modal
    function openDetailModal(button) {
        const code = button.getAttribute('data-code');
        const name = button.getAttribute('data-name');
        const category = button.getAttribute('data-category');
        const desc = button.getAttribute('data-desc');
        const status = button.getAttribute('data-status');
        const reg = button.getAttribute('data-reg');
        const unreg = button.getAttribute('data-unreg');
        const reason = button.getAttribute('data-reason');

        document.getElementById('detCode').innerText = code;
        document.getElementById('detName').innerText = name;
        document.getElementById('detCategory').innerText = category;
        document.getElementById('detRegDate').innerText = reg;
        document.getElementById('detDesc').innerText = desc;

        const badgeContainer = document.getElementById('detStatusBadge');
        const unregSection = document.getElementById('unregDetailsSection');

        if (status === 'registered') {
            badgeContainer.innerHTML = `
                <span class="badge badge-success">
                    <svg viewBox="0 0 24 24" width="10" height="10" stroke="currentColor" stroke-width="3" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="20 6 9 17 4 12"></polyline>
                    </svg>
                    Terdaftar
                </span>
            `;
            unregSection.style.display = 'none';
        } else {
            badgeContainer.innerHTML = `
                <span class="badge badge-danger">
                    <svg viewBox="0 0 24 24" width="10" height="10" stroke="currentColor" stroke-width="3" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                    Unregistrasi
                </span>
            `;
            document.getElementById('detUnregDate').innerText = unreg;
            document.getElementById('detReason').innerText = reason;
            unregSection.style.display = 'block';
        }

        openModal('detailModal');
    }
</script>
@endsection
