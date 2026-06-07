@extends('layouts.dashboard')

@section('content')
<div class="py-12 bg-gray-50">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="profile-container">
            <div class="page-heading">
                <i class="fa-solid fa-money-bill-transfer" style="color:var(--maroon-prime)"></i>
                Daftar Penarikan
            </div>

            <div class="detail-card">
                <div class="mb-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <div>
                        <strong>Total Pending:</strong> {{ $totalPending }}<br>
                        <strong>Total Disetujui:</strong> {{ $totalDisetujui }}<br>
                        <strong>Total Ditolak:</strong> {{ $totalDitolak }}
                    </div>
                    <form method="GET" class="d-flex gap-2">
                        <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Cari kode atau nama anggota...">
                        <button type="submit" class="btn btn-primary">Cari</button>
                    </form>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Anggota</th>
                                <th>Jumlah</th>
                                <th>Status</th>
                                <th>Tanggal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($penarikan as $item)
                                <tr>
                                    <td>{{ $item->kode_penarikan }}</td>
                                    <td>{{ $item->user->name ?? 'N/A' }}</td>
                                    <td>Rp {{ number_format($item->jumlah, 0, ',', '.') }}</td>
                                    <td>{{ ucfirst($item->status) }}</td>
                                    <td>{{ $item->created_at->format('d M Y') }}</td>
                                    <td>
                                        <a href="{{ route('admin.penarikan.show', $item->id) }}" class="btn btn-sm btn-primary">Detail</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-secondary py-4">Tidak ada data penarikan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-end">
                    {{ $penarikan->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection