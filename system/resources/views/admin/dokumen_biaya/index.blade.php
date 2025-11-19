{{-- resources/views/admin/dokumen_biaya/index.blade.php --}}
<x-admin-dashboard>
    <div class="content-header">
        <div class="container-fluid">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <div>
                    <h1 class="m-0 text-primary fw-bold">
                        <i class="fas fa-file-invoice-dollar"></i> Dokumen Biaya
                    </h1>
                    <small class="text-muted">
                        RAB yang sudah disetujui dan dibuatkan RNA & bukti persetujuan.
                    </small>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">

            {{-- Flash message --}}
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                    <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                    <i class="fas fa-exclamation-triangle mr-1"></i> {{ session('error') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <div class="card shadow-sm">

                {{-- FILTER & SEARCH --}}
                <div class="card-header bg-white border-bottom-0 pb-2">
                    <div class="d-flex justify-content-between align-items-center flex-wrap">
                        <h3 class="card-title mb-2 mb-md-0 small text-muted">
                            Filter & Pencarian
                        </h3>

                        <form action="{{ url('admin/dokumen_biaya') }}" method="GET"
                            class="form-inline ml-md-auto w-100 w-md-auto justify-content-end">

                            {{-- STATUS --}}
                            <div class="form-group mr-2 mb-2 mb-md-0">
                                <select name="status" class="form-control form-control-sm">
                                    <option value="">Semua Status Billing</option>
                                    @foreach ($statuses as $key => $labelStatus)
                                        <option value="{{ $key }}"
                                            {{ ($status ?? '') === $key ? 'selected' : '' }}>
                                            {{ $labelStatus }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- SEARCH --}}
                            <div class="input-group input-group-sm" style="max-width: 320px;">
                                <input type="text" name="q" class="form-control"
                                    placeholder="Cari RAB / SPKO / No. PD / Nama / RNA" value="{{ $q ?? '' }}">
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="submit">
                                        <i class="fas fa-search"></i>
                                    </button>
                                    @if (!empty($q) || !empty($status))
                                        <a href="{{ url('admin/dokumen_biaya') }}" class="btn btn-outline-secondary">
                                            <i class="fas fa-times"></i>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- TABEL --}}
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover table-striped table-sm mb-0">
                        <thead class="thead-light">
                            <tr class="text-nowrap">
                                <th style="width:55px;" class="text-center">#</th>
                                <th style="width:190px;">No. RAB / SPKO</th>
                                <th style="width:150px;">No. Pendaftaran</th>
                                <th>Nama & Alamat Pelanggan</th>

                                {{-- >>> yang kita rapikan <<< --}}
                                <th style="width:140px;" class="text-right">Total RAB</th>
                                <th style="width:140px;" class="text-center">RNA</th>
                                <th style="width:160px;" class="text-center">Persetujuan</th>
                                <th style="width:170px;" class="text-center">Status Billing</th>
                                {{-- ---------------------------------- --}}

                                <th style="width:150px;" class="text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($rows as $i => $row)
                                @php
                                    $billingStatus = $row->billing_status ?: 'DRAFT';
                                    $billingLabel = $row->billing_status_label ?? $billingStatus;
                                    $billingClass = match ($billingStatus) {
                                        'PAID' => 'badge-success',
                                        'SENT' => 'badge-primary',
                                        default => 'badge-secondary',
                                    };
                                    $hasRna = !empty($row->rna_nomor);
                                    $hasPersetujuan = !empty($row->persetujuan_nomor);
                                @endphp

                                <tr>
                                    {{-- kolom 1–4 tetap seperti sebelumnya --}}
                                    <td class="align-middle text-center text-muted">
                                        {{ $rows->firstItem() + $i }}
                                    </td>

                                    <td class="align-middle text-monospace">
                                        <strong>{{ $row->nomor_rab ?? '-' }}</strong>
                                        <div class="small text-muted">
                                            SPKO: {{ $row->spko->nomor_spko ?? '-' }}
                                        </div>
                                    </td>

                                    <td class="align-middle text-monospace">
                                        <strong>{{ $row->spko->pengajuan->no_pendaftaran ?? '-' }}</strong>
                                    </td>

                                    <td class="align-middle">
                                        <strong>
                                            {{ $row->nama_pelanggan ?? ($row->spko->pengajuan->pemohon_nama ?? '-') }}
                                        </strong>
                                        <div class="small text-muted">
                                            {{ $row->alamat ?? ($row->spko->pengajuan->alamat_pemasangan ?? '-') }}
                                        </div>
                                    </td>

                                    {{-- >>> 4 kolom yang dirapikan <<< --}}

                                    {{-- TOTAL RAB --}}
                                    <td class="align-middle text-right text-nowrap pr-3">
                                        <div class="font-weight-bold">
                                            Rp {{ number_format($row->total ?? 0, 0, ',', '.') }}
                                        </div>
                                    </td>

                                    {{-- RNA --}}
                                    <td class="align-middle text-center">
                                        @if ($hasRna)
                                            <div class="d-inline-block text-left">
                                                <span class="badge badge-success mb-1 d-block text-center">Sudah</span>
                                                <span class="small text-monospace text-muted d-block">
                                                    {{ $row->rna_nomor }}
                                                </span>
                                            </div>
                                        @else
                                            <span class="badge badge-secondary">Belum</span>
                                        @endif
                                    </td>

                                    {{-- PERSETUJUAN --}}
                                    <td class="align-middle text-center">
                                        @if ($hasPersetujuan)
                                            <div class="d-inline-block text-left">
                                                <span class="badge badge-success mb-1 d-block text-center">Sudah</span>
                                                <span class="small text-monospace text-muted d-block">
                                                    {{ $row->persetujuan_nomor }}
                                                </span>
                                            </div>
                                        @else
                                            <span class="badge badge-secondary">Belum</span>
                                        @endif
                                    </td>

                                    {{-- STATUS BILLING --}}
                                    <td class="align-middle text-center">
                                        <div class="d-inline-block text-left">
                                            <span class="badge {{ $billingClass }} mb-1 d-block text-center">
                                                {{ $billingLabel }}
                                            </span>
                                            <span class="small text-muted d-block">
                                                @if ($billingStatus === 'SENT')
                                                    Dikirim: {{ $row->billing_sent_at?->format('d/m H:i') ?? '-' }}
                                                @elseif($billingStatus === 'PAID')
                                                    Lunas: {{ $row->billing_paid_at?->format('d/m H:i') ?? '-' }}
                                                @else
                                                    Draft dokumen
                                                @endif
                                            </span>
                                        </div>
                                    </td>

                                    {{-- >>> akhir 4 kolom yang dirapikan <<< --}}

                                    {{-- Aksi --}}
                                    <td class="align-middle text-right text-nowrap">
                                        <a href="{{ url('admin/dokumen_biaya/' . $row->id) }}"
                                            class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-edit"></i> Proses
                                        </a>

                                        {{-- SPK: tampilkan tombol sesuai kondisi --}}
                                        @php $spk = $row->spk; @endphp

                                        @if ($row->billing_status === 'PAID')
                                            @if ($spk)
                                                {{-- SPK sudah ada --}}
                                                <a href="{{ url('admin/spk/' . $spk->id) }}"
                                                    class="btn btn-sm btn-outline-success mb-1">
                                                    <i class="fas fa-file-alt"></i> Detail SPK
                                                </a>
                                            @else
                                                {{-- Belum ada SPK, tapi sudah lunas -> boleh buat SPK --}}
                                                <a href="{{ url('admin/spk/create/' . $row->id) }}"
                                                    class="btn btn-sm btn-outline-success mb-1">
                                                    <i class="fas fa-file-signature"></i> Buat SPK
                                                </a>
                                            @endif
                                        @endif

                                        @if ($billingStatus !== 'PAID')
                                            <form action="{{ url('admin/dokumen_biaya/' . $row->id . '/hapus') }}"
                                                method="POST" class="d-inline"
                                                onsubmit="return confirm('Hapus dokumen RNA & persetujuan untuk RAB ini? Data RAB tetap ada.');">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-danger"
                                                    title="Hapus dokumen biaya (RNA & persetujuan)">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @else
                                            <button type="button" class="btn btn-sm btn-outline-secondary" disabled
                                                title="Tagihan sudah lunas, dokumen tidak bisa dihapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center text-muted py-4">
                                        <i class="far fa-folder-open fa-2x mb-2"></i><br>
                                        Belum ada RAB yang siap dibuatkan dokumen biaya.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>


                    </table>
                </div>

                {{-- PAGINASI --}}
                @if ($rows->hasPages())
                    <div class="card-footer d-flex justify-content-between align-items-center">
                        <div class="small text-muted">
                            Menampilkan {{ $rows->firstItem() }}–{{ $rows->lastItem() }}
                            dari {{ $rows->total() }} data
                        </div>
                        <div class="mb-0">
                            {{ $rows->links() }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>
</x-admin-dashboard>
