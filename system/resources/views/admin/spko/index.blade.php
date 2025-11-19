<x-admin-dashboard>
    {{-- STYLE KECIL (khusus halaman ini) --}}
    <style>
        .table thead th {
            position: sticky;
            top: 0;
            z-index: 7;
            background: #f8f9fa;
        }

        .btn-icon {
            width: 28px;
            height: 28px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .badge-lightblue {
            background: #e8f3ff;
            color: #0b69c7;
        }

        .badge-gray {
            background: #f1f3f5;
            color: #5f6b7a;
        }

        .truncate {
            max-width: 360px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
    </style>

    <div class="content-header">
        <div class="container-fluid">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <div>
                    <h1 class="m-0 text-primary fw-bold">
                        <i class="fas fa-file-alt"></i> Daftar SPKO
                    </h1>
                    <small class="text-muted">Kelola Surat Perintah Kerja Opname</small>
                </div>
                <a href="{{ url('admin/spko/create') }}" class="btn btn-primary">
                    <i class="fas fa-plus-circle mr-1"></i> Buat SPKO
                </a>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">

            {{-- Flash message --}}
            @if (session('success'))
                <div class="alert alert-success shadow-sm">
                    <i class="fas fa-check-circle mr-1"></i>{{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger shadow-sm">
                    <i class="fas fa-exclamation-triangle mr-1"></i>{{ session('error') }}
                </div>
            @endif

            {{-- Toolbar --}}
            <div class="card shadow-sm mb-2">
                <div class="card-body py-2">
                    <form action="{{ url()->current() }}" method="GET" class="form-inline flex-wrap">
                        {{-- Cari nomor / pemohon / alamat --}}
                        <div class="input-group mr-2 mb-2">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-search"></i></span>
                            </div>
                            <input type="text" name="q" value="{{ request('q') }}" class="form-control"
                                placeholder="Cari nomor/pemohon/alamat…">
                        </div>

                        {{-- Filter status --}}
                        <div class="input-group mr-2 mb-2">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-filter"></i></span>
                            </div>
                            <select name="status" class="form-control">
                                <option value="">— Semua Status —</option>
                                @foreach (\App\Models\Spko::statuses() as $st)
                                    @php $tmp = new \App\Models\Spko(['status' => $st]); @endphp
                                    <option value="{{ $st }}"
                                        {{ request('status') == $st ? 'selected' : '' }}>
                                        {{ $tmp->status_label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Per page --}}
                        <div class="input-group mr-2 mb-2">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-list-ol"></i></span>
                            </div>
                            <select name="per_page" class="form-control" onchange="this.form.submit()">
                                @foreach ([10, 15, 25, 50] as $pp)
                                    <option value="{{ $pp }}"
                                        {{ request('per_page', $data->perPage()) == $pp ? 'selected' : '' }}>
                                        {{ $pp }}/hal
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <button class="btn btn-light mb-2">
                            <i class="fas fa-sync-alt mr-1"></i> Terapkan
                        </button>
                        @if (request()->hasAny(['q', 'status', 'per_page']))
                            <a href="{{ url()->current() }}" class="btn btn-outline-secondary ml-2 mb-2">Reset</a>
                        @endif
                    </form>
                </div>
            </div>

            {{-- Tabel --}}
            <div class="card shadow-sm">
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th style="width:70px;">#</th>
                                <th>Nomor</th>
                                <th>Tanggal</th>
                                <th>Pemohon</th>
                                <th>Status</th>
                                <th class="text-right" style="width:280px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($data as $i => $row)
                                <tr>
                                    <td class="text-muted">{{ $data->firstItem() + $i }}</td>

                                    <td class="text-monospace">
                                        <a href="{{ url('admin/spko/' . $row->id) }}" class="font-weight-600">
                                            {{ $row->nomor_spko }}
                                        </a>
                                        <div class="small text-muted truncate">
                                            {{ $row->alamat ?? ($row->pengajuan->alamat_pemasangan ?? '') }}
                                        </div>
                                    </td>

                                    <td>
                                        {{ optional($row->tanggal_spko)->format('d/m/Y') ?: '-' }}
                                        <div class="small text-muted">
                                            <i class="far fa-clock"></i>
                                            {{ optional($row->created_at)->format('d/m H:i') }}
                                        </div>
                                    </td>

                                    <td>
                                        {{ $row->pemilik_nama ?? ($row->pengajuan->pemohon_nama ?? '-') }}
                                        @if ($row->pengajuan && $row->pengajuan->no_pendaftaran)
                                            <div class="small text-muted text-monospace">
                                                PD: {{ $row->pengajuan->no_pendaftaran }}
                                            </div>
                                        @endif
                                    </td>

                                    <td>
                                        <span class="badge {{ $row->status_badge_class }}">
                                            {{ $row->status_label }}
                                        </span>
                                    </td>

                                    <td class="text-right">

                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-light dropdown-toggle" type="button"
                                                data-toggle="dropdown">
                                                <i class="fas fa-bars mr-1"></i> Aksi
                                            </button>

                                            <div class="dropdown-menu dropdown-menu-right shadow-sm">

                                                {{-- =======================
                 TOMBOL: LIHAT
            ======================= --}}
                                                <a href="{{ url('admin/spko/' . $row->id) }}" class="dropdown-item">
                                                    <i class="fas fa-eye text-primary mr-2"></i> Lihat SPKO
                                                </a>

                                                {{-- =======================
                KONDISI: DRAFT
            ======================= --}}
                                                @if ($row->status === \App\Models\Spko::ST_DRAFT)
                                                    <a href="{{ url('admin/spko/' . $row->id . '/edit') }}"
                                                        class="dropdown-item">
                                                        <i class="fas fa-edit text-info mr-2"></i> Edit SPKO
                                                    </a>

                                                    <form
                                                        action="{{ url('admin/spko/' . $row->id . '/kirim-perencanaan') }}"
                                                        method="POST"
                                                        onsubmit="return confirm('Kirim SPKO ini ke Tim Perencanaan?')">
                                                        @csrf
                                                        <button class="dropdown-item">
                                                            <i class="fas fa-paper-plane text-success mr-2"></i> Kirim
                                                            ke Perencanaan
                                                        </button>
                                                    </form>

                                                    <form action="{{ url('admin/spko/' . $row->id) }}" method="POST"
                                                        onsubmit="return confirm('Hapus SPKO ini?')">
                                                        @csrf @method('DELETE')
                                                        <button class="dropdown-item">
                                                            <i class="fas fa-trash text-danger mr-2"></i> Hapus SPKO
                                                        </button>
                                                    </form>

                                                    {{-- =======================
                KONDISI: SENT TO PLANNING
            ======================= --}}
                                                @elseif($row->status === \App\Models\Spko::ST_SENT_PLANNING)
                                                    <div class="dropdown-item text-muted small">
                                                        <i class="fas fa-check text-success mr-1"></i> Sudah dikirim ke
                                                        Perencanaan
                                                    </div>
                                                @endif

                                            </div>
                                        </div>

                                    </td>

                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="p-5">
                                        <div class="text-center text-muted">
                                            <div class="mb-2"><i class="far fa-folder-open fa-2x"></i></div>
                                            Belum ada SPKO.
                                            <div class="mt-2">
                                                <a href="{{ url('admin/spko/create') }}"
                                                    class="btn btn-sm btn-primary">
                                                    <i class="fas fa-plus-circle mr-1"></i> Buat SPKO Pertama
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($data->hasPages())
                    <div class="card-footer d-flex justify-content-between align-items-center">
                        <div class="small text-muted">
                            Menampilkan {{ $data->firstItem() }}–{{ $data->lastItem() }} dari {{ $data->total() }}
                        </div>
                        <div class="pb-0 mb-0">
                            {{ $data->appends(request()->query())->links() }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>
</x-admin-dashboard>
