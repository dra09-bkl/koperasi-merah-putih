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

    .profile-container { font-family: 'Inter', sans-serif; max-width: 950px; margin: 0 auto; }

    .page-heading {
        font-size: 1.5rem; font-weight: 700; color: var(--text-dark);
        margin-bottom: 2rem; display: flex; align-items: center;
        gap: 12px; letter-spacing: -0.02em;
    }
    .page-heading::before {
        content: ""; width: 4px; height: 24px;
        background: var(--maroon-prime); border-radius: 4px;
    }

    .section-card {
        background: #fff; border-radius: 20px;
        box-shadow: 0 10px 30px rgba(102,6,6,.05);
        border: 1px solid rgba(102,6,6,.08);
        overflow: hidden; margin-bottom: 2rem;
    }
    .section-header {
        background: linear-gradient(to right, var(--maroon-soft), transparent);
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid rgba(102,6,6,.05);
        font-size: .75rem; font-weight: 700;
        text-transform: uppercase; letter-spacing: .1em;
        color: var(--maroon-prime);
        display: flex; align-items: center; gap: 8px;
    }
    .section-body { padding: 1.5rem; }

    /* Stat mini cards */
    .stat-mini { border-radius: 14px; padding: 1.1rem 1.3rem; border: none; }

    /* Table */
    .table-penarikan th {
        font-size: .72rem; text-transform: uppercase;
        letter-spacing: .08em; color: var(--text-gray); font-weight: 600;
        border-bottom: 2px solid rgba(102,6,6,.08);
        padding: .85rem 1rem;
    }
    .table-penarikan td { padding: .85rem 1rem; vertical-align: middle; font-size: .875rem; }
    .table-penarikan tbody tr:hover { background: var(--maroon-soft); transition: background .15s; }

    .badge-status {
        padding: 5px 12px; border-radius: 20px;
        font-size: .72rem; font-weight: 600; letter-spacing: .04em;
    }
    .badge-pending   { background: #fff8e1; color: #b45309; }
    .badge-disetujui { background: #f0fdf4; color: #15803d; }
    .badge-ditolak   { background: #fff1f2; color: #be123c; }

    .btn-ajukan {
        background: var(--maroon-prime); color: #fff; border: none;
        padding: .65rem 1.4rem; border-radius: 12px;
        font-size: .875rem; font-weight: 600;
        display: inline-flex; align-items: center; gap: 8px;
        text-decoration: none; transition: all .2s;
    }
    .btn-ajukan:hover { background: var(--maroon-dark); color: #fff; box-shadow: 0 6px 16px rgba(102,6,6,.3); }

    .empty-state { text-align: center; padding: 3rem 1rem; color: var(--text-gray); }
    .empty-state i { font-size: 3rem; color: #d1d5db; margin-bottom: 1rem; display: block; }
</style>

<div class="py-12 bg-gray-50">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="profile-container">

            <div class="page-heading">
                <i class="fa-solid fa-money-bill-transfer" style="color:var(--maroon-prime)"></i>
                Penarikan Saldo
            </div>

            {{-- Alert --}}
            @if(session('success'))
                <div class="alert alert-minimal shadow-sm bg-white border-start border-success border-4 mb-4" role="alert">
                    <div class="d-flex justify-content-between align-items-center p-2">
                        <span><i class="fa-solid fa-circle-check text-success me-2"></i>{{ session('success') }}</span>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                </div>
            @endif

            {{-- Stat mini cards --}}
            <div class="row g-3 mb-4">
                <div class="col-12 col-md-4">
                    <div class="stat-mini card shadow-sm border-0 border-start border-warning border-4">
                        <div class="card-body">
                            <h6 class="text-muted text-uppercase small fw-bold mb-1">Menunggu</h6>
                            <h4 class="fw-bold mb-0 text-dark">
                                {{ $penarikan->where('status','pending')->count() }}
                            </h4>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="stat-mini card shadow-sm border-0 border-start border-success border-4">
                        <div class="card-body">
                            <h6 class="text-muted text-uppercase small fw-bold mb-1">Disetujui</h6>
                            <h4 class="fw-bold mb-0 text-dark">
                                {{ $penarikan->where('status','disetujui')->count() }}
                            </h4>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="stat-mini card shadow-sm border-0 border-start border-danger border-4">
                        <div class="card-body">
                            <h6 class="text-muted text-uppercase small fw-bold mb-1">Total Ditarik</h6>
                            <h4 class="fw-bold mb-0 text-dark">
                                Rp {{ number_format($penarikan->where('status','disetujui')->sum('jumlah'), 0, ',', '.') }}
                            </h4>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tabel Riwayat --}}
            <div class="section-card">
                <div class="section-header d-flex justify-content-between align-items-center">
                    <span><i class="fa-solid fa-clock-rotate-left me-1"></i> Riwayat Penarikan</span>
                    <a href="{{ route('penarikan.create') }}" class="btn-ajukan">
                        <i class="fa-solid fa-plus"></i> Ajukan Penarikan
                    </a>
                </div>
                <div class="section-body p-0">
                    @if($penarikan->isEmpty())
                        <div class="empty-state">
                            <i class="fa-solid fa-inbox"></i>
                            <p class="fw-semibold text-dark mb-1">Belum ada riwayat penarikan</p>
                            <p class="small">Klik tombol <strong>Ajukan Penarikan</strong> untuk memulai.</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-borderless table-penarikan mb-0">
                                <thead>
                                    <tr>
                                        <th>Kode</th>
                                        <th>Tanggal</th>
                                        <th>Jumlah</th>
                                        <th>Bank Tujuan</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($penarikan as $item)
                                    <tr>
                                        <td>
                                            <span class="fw-semibold" style="color:var(--maroon-prime)">
                                                {{ $item->kode_penarikan }}
                                            </span>
                                        </td>
                                        <td class="text-muted">
                                            {{ $item->created_at->format('d M Y') }}
                                        </td>
                                        <td class="fw-semibold">
                                            Rp {{ number_format($item->jumlah, 0, ',', '.') }}
                                        </td>
                                        <td>
                                            <span class="d-block fw-semibold">{{ $item->nama_bank }}</span>
                                            <span class="text-muted small">{{ $item->rekening_tujuan }}</span>
                                        </td>
                                        <td>
                                            <span class="badge-status badge-{{ $item->status }}">
                                                {{ $item->status_label }}
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="px-3 py-3">
                            {{ $penarikan->links() }}
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</div>
@endsection