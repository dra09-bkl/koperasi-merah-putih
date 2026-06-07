@extends('layouts.dashboard')

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

    :root {
        --maroon-prime: #660606;
        --maroon-dark:  #4b0404;
        --maroon-light: #8c1414;
        --maroon-soft:  #fff5f5;
        --text-dark:    #1f2937;
        --text-gray:    #6b7280;
    }

    body, .profile-container { font-family: 'Inter', sans-serif; }
    .profile-container { max-width: 1000px; margin: 0 auto; }

    .page-heading {
        font-size: 1.5rem; font-weight: 700; color: var(--text-dark);
        margin-bottom: 2rem; display: flex; align-items: center;
        gap: 12px; letter-spacing: -0.02em;
    }
    .page-heading::before {
        content: ""; width: 4px; height: 24px;
        background: var(--maroon-prime); border-radius: 4px;
    }

    .detail-card {
        background: #fff; border-radius: 12px;
        box-shadow: 0 10px 30px rgba(102,6,6,.05);
        border: 1px solid rgba(102,6,6,.08);
        padding: 2rem; margin-bottom: 1.5rem;
    }

    .detail-row {
        display: grid; grid-template-columns: 200px 1fr;
        gap: 1.5rem; padding: 1rem 0;
        border-bottom: 1px solid #e5e7eb;
    }

    .detail-row:last-child { border-bottom: none; }

    .detail-label {
        font-weight: 600; color: var(--text-gray);
        font-size: 0.875rem; text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .detail-value {
        color: var(--text-dark); font-size: 1rem;
        font-weight: 500;
    }

    .status-badge {
        display: inline-block; padding: 0.5rem 1rem;
        border-radius: 20px; font-weight: 600; font-size: 0.875rem;
    }

    .status-pending {
        background: #fef08a; color: #92400e;
    }

    .status-disetujui {
        background: #dcfce7; color: #166534;
    }

    .status-ditolak {
        background: #fee2e2; color: #991b1b;
    }

    .action-buttons {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-top: 2rem;
    padding-top: 2rem;
    border-top: 1px solid #e5e7eb;
    flex-wrap: wrap;
}

.btn-action {
    padding: 0.75rem 1.5rem;
    border-radius: 8px;
    font-weight: 600;
    border: none;
    cursor: pointer;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-height: 48px;           /* penting untuk keseragaman tinggi */
}

.btn-approve { background: #10b981; color: white; }
.btn-approve:hover { background: #059669; }

.btn-reject { background: #ef4444; color: white; }
.btn-reject:hover { background: #dc2626; }

.btn-back { 
    background: #e5e7eb; 
    color: #1f2937; 
    text-decoration: none;
}
.btn-back:hover { background: #d1d5db; }
</style>

<div class="py-12 bg-gray-50">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="profile-container">

            <div class="page-heading">
                <i class="fa-solid fa-money-bill-transfer" style="color:var(--maroon-prime)"></i>
                Detail Penarikan
            </div>

            <div class="detail-card">

                {{-- Kode Penarikan --}}
                <div class="detail-row">
                    <div class="detail-label">Kode Penarikan</div>
                    <div class="detail-value">{{ $penarikan->kode_penarikan }}</div>
                </div>

                {{-- Status --}}
                <div class="detail-row">
                    <div class="detail-label">Status</div>
                    <div>
                        <span class="status-badge status-{{ $penarikan->status }}">
                            {{ ucfirst($penarikan->status) }}
                        </span>
                    </div>
                </div>

                {{-- Anggota --}}
                <div class="detail-row">
                    <div class="detail-label">Anggota</div>
                    <div class="detail-value">{{ $penarikan->user->name ?? 'N/A' }}</div>
                </div>

                {{-- Jumlah Penarikan --}}
                <div class="detail-row">
                    <div class="detail-label">Jumlah</div>
                    <div class="detail-value">Rp {{ number_format($penarikan->jumlah, 0, ',', '.') }}</div>
                </div>

                {{-- Bank Tujuan --}}
                <div class="detail-row">
                    <div class="detail-label">Bank Tujuan</div>
                    <div class="detail-value">{{ $penarikan->nama_bank }}</div>
                </div>

                {{-- Nomor Rekening --}}
                <div class="detail-row">
                    <div class="detail-label">Nomor Rekening</div>
                    <div class="detail-value">{{ $penarikan->rekening_tujuan }}</div>
                </div>

                {{-- Nama Pemilik Rekening --}}
                <div class="detail-row">
                    <div class="detail-label">Nama Pemilik</div>
                    <div class="detail-value">{{ $penarikan->nama_rekening }}</div>
                </div>

                {{-- Keterangan --}}
                <div class="detail-row">
                    <div class="detail-label">Keterangan</div>
                    <div class="detail-value">{{ $penarikan->keterangan ?? '-' }}</div>
                </div>

                {{-- Tanggal Pengajuan --}}
                <div class="detail-row">
                    <div class="detail-label">Tanggal Pengajuan</div>
                    <div class="detail-value">{{ $penarikan->created_at->format('d M Y H:i') }}</div>
                </div>

                {{-- Diproses Oleh --}}
                @if($penarikan->diproses_oleh)
                <div class="detail-row">
                    <div class="detail-label">Diproses Oleh</div>
                    <div class="detail-value">{{ $penarikan->diprosesoleh->name ?? 'Admin' }}</div>
                </div>

                <div class="detail-row">
                    <div class="detail-label">Tanggal Proses</div>
                    <div class="detail-value">{{ $penarikan->diproses_at->format('d M Y H:i') }}</div>
                </div>
                @endif

                {{-- Catatan Admin --}}
                @if($penarikan->catatan_admin)
                <div class="detail-row">
                    <div class="detail-label">Catatan Admin</div>
                    <div class="detail-value">{{ $penarikan->catatan_admin }}</div>
                </div>
                @endif

                {{-- Action Buttons --}}
                @if($penarikan->status === 'pending')
                <div class="action-buttons">
                    <form action="{{ route('admin.penarikan.approve', $penarikan->id) }}" method="POST" class="flex-1">
                        @csrf
                        <button type="submit" class="btn-action btn-approve w-full">
                            <i class="fa-solid fa-check me-1"></i> Setujui
                        </button>
                    </form>

                    <form action="{{ route('admin.penarikan.reject', $penarikan->id) }}" method="POST" class="flex-[2]" onsubmit="return confirm('Yakin ingin menolak penarikan ini?')">
                        @csrf
                        <div class="flex gap-3 w-full">
                            <input 
                                type="text" 
                                name="catatan_admin" 
                                placeholder="Alasan penolakan (wajib)" 
                                required
                                class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-red-500 text-sm">
                            
                            <button type="submit" class="btn-action btn-reject whitespace-nowrap">
                                <i class="fa-solid fa-times me-1"></i> Tolak
                            </button>
                        </div>
                    </form>

                    <a href="{{ route('admin.penarikan.index') }}" 
                    class="btn-action btn-back whitespace-nowrap">
                        <i class="fa-solid fa-arrow-left me-1"></i> Kembali
                    </a>
                </div>
                @else
                <div class="action-buttons">
                    <a href="{{ route('admin.penarikan.index') }}" class="btn-action btn-back">
                        <i class="fa-solid fa-arrow-left me-1"></i> Kembali
                    </a>
                </div>
                @endif

            </div>

        </div>
    </div>
</div>

@endsection