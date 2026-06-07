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

    .profile-container { font-family: 'Inter', sans-serif; max-width: 700px; margin: 0 auto; }

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
    .section-body { padding: 2rem 1.5rem; }

    .form-label-custom {
        font-size: .8rem; font-weight: 600;
        text-transform: uppercase; letter-spacing: .06em;
        color: var(--text-gray); margin-bottom: .4rem;
    }
    .form-control-custom {
        border: 1.5px solid #e5e7eb; border-radius: 12px;
        padding: .7rem 1rem; font-size: .9rem;
        transition: border-color .2s, box-shadow .2s;
    }
    .form-control-custom:focus {
        border-color: var(--maroon-prime);
        box-shadow: 0 0 0 3px rgba(102,6,6,.1);
        outline: none;
    }
    .form-control-custom.is-invalid { border-color: #dc2626; }

    .input-prefix {
        background: var(--maroon-soft); color: var(--maroon-prime);
        font-weight: 600; border: 1.5px solid #e5e7eb;
        border-right: none; border-radius: 12px 0 0 12px;
        padding: .7rem 1rem; font-size: .9rem;
    }
    .input-with-prefix .form-control-custom { border-radius: 0 12px 12px 0; }

    .divider-label {
        font-size: .72rem; font-weight: 700;
        text-transform: uppercase; letter-spacing: .1em;
        color: var(--maroon-prime); margin: 1.5rem 0 1rem;
        display: flex; align-items: center; gap: 10px;
    }
    .divider-label::after {
        content: ""; flex: 1; height: 1px;
        background: rgba(102,6,6,.12);
    }

    .btn-submit {
        background: var(--maroon-prime); color: #fff; border: none;
        padding: .75rem 2rem; border-radius: 14px;
        font-size: .9rem; font-weight: 600;
        display: inline-flex; align-items: center; gap: 10px;
        transition: all .2s; width: 100%;
        justify-content: center;
    }
    .btn-submit:hover { background: var(--maroon-dark); box-shadow: 0 8px 20px rgba(102,6,6,.3); }

    .btn-back {
        color: var(--maroon-prime); font-size: .875rem;
        font-weight: 500; text-decoration: none;
        display: inline-flex; align-items: center; gap: 6px;
    }
    .btn-back:hover { color: var(--maroon-dark); }

    .info-box {
        background: #fffbeb; border: 1px solid #fde68a;
        border-radius: 12px; padding: 1rem 1.25rem;
        font-size: .85rem; color: #92400e;
    }
</style>

<div class="py-12 bg-gray-50">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="profile-container">

            <div class="page-heading">
                <i class="fa-solid fa-money-bill-transfer" style="color:var(--maroon-prime)"></i>
                Ajukan Penarikan
            </div>

            <div class="section-card">
                <div class="section-header">
                    <i class="fa-solid fa-file-invoice me-1"></i> Form Pengajuan Penarikan
                </div>
                <div class="section-body">

                    {{-- Info box --}}
                    <div class="info-box mb-4">
                        <i class="fa-solid fa-circle-info me-2"></i>
                        Penarikan akan diproses oleh admin dalam <strong>1×24 jam</strong> hari kerja.
                        Pastikan data rekening yang kamu masukkan sudah benar.
                    </div>

                    <form method="POST" action="{{ route('penarikan.store') }}">
                        @csrf

                        {{-- Jumlah --}}
                        <div class="mb-4">
                            <label class="form-label-custom">Jumlah Penarikan <span class="text-danger">*</span></label>
                            <div class="input-group input-with-prefix">
                                <span class="input-prefix">Rp</span>
                                <input type="number"
                                    name="jumlah"
                                    value="{{ old('jumlah') }}"
                                    class="form-control form-control-custom @error('jumlah') is-invalid @enderror"
                                    placeholder="Contoh: 500000"
                                    min="10000"
                                    step="1000">
                            </div>
                            @error('jumlah')
                                <div class="invalid-feedback d-block mt-1" style="font-size:.8rem">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Minimal penarikan Rp 10.000</small>
                        </div>

                        {{-- Divider: Info Rekening --}}
                        <div class="divider-label">
                            <i class="fa-solid fa-building-columns"></i> Rekening Tujuan
                        </div>

                        <div class="row g-3">
                            {{-- Nama Bank --}}
                            <div class="col-12 col-md-6">
                                <label class="form-label-custom">Nama Bank <span class="text-danger">*</span></label>
                                <select name="nama_bank"
                                    class="form-control form-control-custom @error('nama_bank') is-invalid @enderror">
                                    <option value="" disabled {{ old('nama_bank') ? '' : 'selected' }}>-- Pilih Bank --</option>
                                    @foreach(['BRI','BNI','BCA','Mandiri','BSI','BTPN','Permata','CIMB Niaga','Danamon','BTN'] as $bank)
                                        <option value="{{ $bank }}" {{ old('nama_bank') == $bank ? 'selected' : '' }}>
                                            {{ $bank }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('nama_bank')
                                    <div class="invalid-feedback d-block mt-1" style="font-size:.8rem">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Nomor Rekening --}}
                            <div class="col-12 col-md-6">
                                <label class="form-label-custom">Nomor Rekening <span class="text-danger">*</span></label>
                                <input type="text"
                                    name="rekening_tujuan"
                                    value="{{ old('rekening_tujuan') }}"
                                    class="form-control form-control-custom @error('rekening_tujuan') is-invalid @enderror"
                                    placeholder="Contoh: 1234567890">
                                @error('rekening_tujuan')
                                    <div class="invalid-feedback d-block mt-1" style="font-size:.8rem">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Nama Rekening --}}
                            <div class="col-12">
                                <label class="form-label-custom">Nama Pemilik Rekening <span class="text-danger">*</span></label>
                                <input type="text"
                                    name="nama_rekening"
                                    value="{{ old('nama_rekening', auth()->user()->name) }}"
                                    class="form-control form-control-custom @error('nama_rekening') is-invalid @enderror"
                                    placeholder="Sesuai nama di buku tabungan">
                                @error('nama_rekening')
                                    <div class="invalid-feedback d-block mt-1" style="font-size:.8rem">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Keterangan --}}
                        <div class="mt-3">
                            <label class="form-label-custom">Keterangan <span class="text-muted">(opsional)</span></label>
                            <textarea name="keterangan" rows="3"
                                class="form-control form-control-custom @error('keterangan') is-invalid @enderror"
                                placeholder="Tambahkan catatan jika perlu...">{{ old('keterangan') }}</textarea>
                            @error('keterangan')
                                <div class="invalid-feedback d-block mt-1" style="font-size:.8rem">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mt-4 d-flex flex-column gap-2">
                            <button type="submit" class="btn-submit">
                                <i class="fa-solid fa-paper-plane"></i> Kirim Pengajuan
                            </button>
                            <div class="text-center mt-2">
                                <a href="{{ route('penarikan.index') }}" class="btn-back">
                                    <i class="fa-solid fa-arrow-left"></i> Kembali ke Riwayat
                                </a>
                            </div>
                        </div>
                    </form>

                </div>
            </div>

        </div>
    </div>
</div>
@endsection