<x-perencanaan>
    <div class="content-header d-flex align-items-center justify-content-between mb-2">
        <div>
            <h1 class="m-0 text-primary fw-bold">
                <i class="fas fa-file-invoice-dollar"></i> Susun RAB
            </h1>
            <small class="text-muted">Daftar SPKO yang siap disusun RAB</small>
        </div>
    </div>

    <section class="content">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        @if (session('info'))
            <div class="alert alert-info">{{ session('info') }}</div>
        @endif

        <div class="card shadow-sm">
            <div class="card-body table-responsive p-0">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th style="width:60px;">#</th>
                            <th>No. SPKO</th>
                            <th>Pemohon</th>
                            <th>Alamat</th>
                            <th>Status RAB</th>
                            <th>Total RAB</th>
                            <th style="width:320px;" class="text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($spko as $i => $row)
                            @php
                                $rab = $row->rab;

                                // --- nama & alamat ---
                                $namaPemohon = $row->pengajuan->pemohon_nama ?? ($row->pemilik_nama ?? '-');

                                $alamat = $row->pengajuan->alamat_pemasangan ?? ($row->alamat ?? '-');

                                // --- status ---
                                // kalau kolom status masih NULL -> anggap 'draft'
                                $status = $rab ? $rab->status ?? 'draft' : null;

                                $badgeMap = [
                                    'draft' => 'badge-secondary',
                                    'dikirim' => 'badge-info',
                                    'disetujui' => 'badge-success',
                                    'ditolak' => 'badge-danger',
                                ];
                                $statusBadge = $status ? $badgeMap[$status] ?? 'badge-light' : null;
                            @endphp

                            <tr>
                                <td>{{ $spko->firstItem() + $i }}</td>

                                <td class="text-monospace">{{ $row->nomor_spko }}</td>

                                <td>{{ $namaPemohon }}</td>

                                <td>{{ $alamat }}</td>

                                <td>
                                    @if ($rab)
                                        <span class="badge {{ $statusBadge }}">
                                            {{ strtoupper($status) }}
                                        </span>
                                    @else
                                        <span class="badge badge-light">BELUM DISUSUN</span>
                                    @endif
                                </td>

                                <td>
                                    @if ($rab)
                                        Rp {{ number_format($rab->total, 0, ',', '.') }}
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>

                                <td class="text-right">
                                    {{-- Susun / Edit RAB --}}
                                    @if ($rab)
                                        <a href="{{ url('perencanaan/rab/' . $row->id . '/edit') }}"
                                            class="btn btn-xs btn-info">
                                            <i class="fas fa-edit"></i> Edit RAB
                                        </a>
                                    @else
                                        <a href="{{ url('perencanaan/rab/' . $row->id . '/create') }}"
                                            class="btn btn-xs btn-primary">
                                            <i class="fas fa-plus"></i> Susun RAB
                                        </a>
                                    @endif

                                    {{-- Detail --}}
                                    <a href="{{ url('perencanaan/rab/' . $row->id) }}" class="btn btn-xs btn-light">
                                        <i class="fas fa-eye"></i> Detail
                                    </a>

                                    {{-- Kirim ke Direktur: kalau sudah ada RAB & status draft/ditolak (null juga dianggap draft) --}}
                                    @if ($rab && in_array($status, ['draft', 'ditolak']))
                                        <form action="{{ url('perencanaan/rab/' . $row->id . '/kirim') }}" method="POST"
                                            class="d-inline" onsubmit="return confirm('Kirim RAB ini ke Direktur?')">
                                            @csrf
                                            <button class="btn btn-xs btn-warning">
                                                <i class="fas fa-paper-plane"></i> Kirim
                                            </button>
                                        </form>
                                    @endif

                                    @if ($row->rab)
                                        <form action="{{ url('perencanaan/rab/' . $row->rab->id) }}" method="POST"
                                            onsubmit="return confirm('Yakin ingin menghapus RAB ini?');"
                                            style="display:inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm"
                                                title="Hapus RAB">
                                                <i class="fas fa-trash-alt"></i> Hapus
                                            </button>
                                        </form>
                                    @endif

                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    Belum ada SPKO dalam antrian penyusunan RAB.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($spko->hasPages())
                <div class="card-footer pb-0">
                    {{ $spko->links() }}
                </div>
            @endif
        </div>
    </section>
</x-perencanaan>
