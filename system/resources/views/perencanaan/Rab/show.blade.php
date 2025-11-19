<x-perencanaan>
    @php
        $rab = $rab ?? ($row->rab ?? null);
        $details = $rab?->details ?? collect();
        $pengajuan = $row->pengajuan ?? null;
        $survei = $row->survei ?? null;

        $fmt = fn($v) => $v ? \Illuminate\Support\Carbon::parse($v)->format('d/m/Y H:i') : '-';

        // mapping status -> badge
        $status = $rab->status ?? 'draft';
        $badgeMap = [
            'draft' => 'badge-secondary',
            'dikirim' => 'badge-info',
            'disetujui' => 'badge-success',
            'ditolak' => 'badge-danger',
        ];
        $statusBadge = $badgeMap[$status] ?? 'badge-light';
    @endphp

    <div class="content-header d-flex align-items-center justify-content-between mb-3">
        <div>
            <h1 class="m-0 text-primary fw-bold">
                <i class="fas fa-file-invoice-dollar"></i> Detail RAB
            </h1>
            <small class="text-muted">
                SPKO: <span class="text-monospace">{{ $row->nomor_spko }}</span>
                @if ($rab)
                    &mdash; RAB: <span class="text-monospace">{{ $rab->nomor_rab }}</span>
                    &mdash;
                    <span class="badge {{ $statusBadge }} text-uppercase">
                        {{ strtoupper($status) }}
                    </span>
                @endif
            </small>
        </div>
        <div class="d-flex align-items-center gap-2">
            @if ($rab)
                @if (in_array($status, ['draft', 'ditolak']))
                    {{-- Tombol kirim hanya jika draft/ditolak --}}
                    <form action="{{ url('perencanaan/rab/' . $row->id . '/kirim') }}" method="POST" class="d-inline"
                        onsubmit="return confirm('Kirim RAB ini ke Direktur?')">
                        @csrf
                        <button class="btn btn-warning mr-2">
                            <i class="fas fa-paper-plane"></i> Kirim ke Direktur
                        </button>
                    </form>
                @endif

                <a href="{{ url('perencanaan/rab/' . $row->id . '/edit') }}" class="btn btn-primary mr-2">
                    <i class="fas fa-edit"></i> Edit RAB
                </a>
            @endif
            @if ($rab && in_array($rab->status, ['draft', 'ditolak', null], true))
                <form action="{{ url('perencanaan/rab/' . $row->id) }}" method="POST" class="d-inline"
                    onsubmit="return confirm('Yakin ingin menghapus RAB ini?');">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-outline-danger">
                        <i class="fas fa-trash"></i> Hapus
                    </button>
                </form>
            @endif
            <a href="{{ url('perencanaan/rab') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    <section class="content">
        @if (!$rab)
            <div class="alert alert-warning">
                RAB untuk SPKO ini belum disusun.
            </div>
        @else
            <div class="row mb-3">
                {{-- IDENTITAS PELANGGAN --}}
                <div class="col-md-6 mb-3">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">
                            <h6 class="text-uppercase text-muted">Identitas Pelanggan</h6>
                            <dl class="row mb-0">
                                <dt class="col-sm-4">No. Pendaftaran</dt>
                                <dd class="col-sm-8 text-monospace">{{ $pengajuan->no_pendaftaran ?? '-' }}</dd>

                                <dt class="col-sm-4">Nama</dt>
                                <dd class="col-sm-8">
                                    {{ $rab->nama_pelanggan ?? ($pengajuan->pemohon_nama ?? ($row->pemilik_nama ?? '-')) }}
                                </dd>

                                <dt class="col-sm-4">Alamat</dt>
                                <dd class="col-sm-8">
                                    {{ $rab->alamat ?? ($pengajuan->alamat_pemasangan ?? ($row->alamat ?? '-')) }}
                                </dd>

                                <dt class="col-sm-4">Kategori Tarif</dt>
                                <dd class="col-sm-8">{{ $rab->kategori_tarif ?? '-' }}</dd>
                            </dl>
                        </div>
                        @if ($survei)
                            <div class="card-body">
                                <h6 class="text-uppercase text-muted">Ringkasan Hasil Survei</h6>
                                <dl class="row mb-0">
                                    <dt class="col-sm-3">Pipa Dinas</dt>
                                    <dd class="col-sm-9">
                                        {{ $survei->jenis_pipa_dinas ?? '-' }}
                                        @if (!is_null($survei->panjang_pipa_dinas))
                                            ({{ $survei->panjang_pipa_dinas }} m)
                                        @endif
                                    </dd>

                                    <dt class="col-sm-3">Pipa Persil</dt>
                                    <dd class="col-sm-9">
                                        {{ $survei->jenis_pipa_persil ?? '-' }}
                                        @if (!is_null($survei->panjang_pipa_persil))
                                            ({{ $survei->panjang_pipa_persil }} m)
                                        @endif
                                    </dd>

                                    @if ($survei->kendala_lapangan)
                                        <dt class="col-sm-3">Kendala Lapangan</dt>
                                        <dd class="col-sm-9">{{ $survei->kendala_lapangan }}</dd>
                                    @endif

                                    @if ($survei->catatan_teknis)
                                        <dt class="col-sm-3">Catatan Teknis</dt>
                                        <dd class="col-sm-9">{{ $survei->catatan_teknis }}</dd>
                                    @endif
                                </dl>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- RINGKASAN RAB + STATUS KIRIM/APPROVE --}}
                <div class="col-md-6 mb-3">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">
                            <h6 class="text-uppercase text-muted">Ringkasan RAB</h6>
                            <dl class="row mb-0">
                                <dt class="col-sm-5">Terobos</dt>
                                <dd class="col-sm-7">
                                    @if ($rab->pemasangan_terobos)
                                        <span class="badge badge-danger">Ya</span>
                                    @else
                                        <span class="badge badge-secondary">Tidak</span>
                                    @endif
                                </dd>

                                <dt class="col-sm-5">Subtotal Pipa Dinas</dt>
                                <dd class="col-sm-7">Rp {{ number_format($rab->subtotal_pipa_dinas, 0, ',', '.') }}
                                </dd>

                                <dt class="col-sm-5">Subtotal Pipa Persil</dt>
                                <dd class="col-sm-7">Rp {{ number_format($rab->subtotal_pipa_persil, 0, ',', '.') }}
                                </dd>

                                <dt class="col-sm-5">Biaya Pendaftaran</dt>
                                <dd class="col-sm-7">Rp {{ number_format($rab->biaya_pendaftaran, 0, ',', '.') }}</dd>

                                <dt class="col-sm-5">Biaya Admin</dt>
                                <dd class="col-sm-7">Rp {{ number_format($rab->biaya_admin, 0, ',', '.') }}</dd>

                                <dt class="col-sm-5">Total RAB</dt>
                                <dd class="col-sm-7 font-weight-bold text-primary">
                                    Rp {{ number_format($rab->total, 0, ',', '.') }}
                                </dd>
                            </dl>

                            <hr>

                            <h6 class="text-uppercase text-muted mb-2">Status Proses</h6>
                            <dl class="row mb-0 small">
                                <dt class="col-sm-5">Dikirim ke Direktur</dt>
                                <dd class="col-sm-7">{{ $fmt($rab->sent_to_director_at ?? null) }}</dd>

                                <dt class="col-sm-5">Disetujui</dt>
                                <dd class="col-sm-7">
                                    {{ $fmt($rab->approved_at ?? null) }}
                                    @if ($rab->approved_by)
                                        <small class="text-muted">oleh {{ $rab->approved_by }}</small>
                                    @endif
                                </dd>

                                <dt class="col-sm-5">Ditolak</dt>
                                <dd class="col-sm-7">
                                    {{ $fmt($rab->rejected_at ?? null) }}
                                    @if ($rab->rejected_by)
                                        <small class="text-muted">oleh {{ $rab->rejected_by }}</small>
                                    @endif
                                </dd>
                            </dl>

                            @if ($rab->rejection_note)
                                <div class="mt-2">
                                    <span class="badge badge-danger mb-1">Alasan Penolakan</span>
                                    <div class="border rounded p-2 bg-light">
                                        {!! nl2br(e($rab->rejection_note)) !!}
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- OPSIONAL: ringkasan hasil survei --}}


            {{-- Tabel detail RAB --}}
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <strong><i class="fas fa-list-ul"></i> Rincian Item RAB</strong>
                </div>
                <div class="card-body p-0 table-responsive">
                    <table class="table table-sm table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th style="width: 140px;">Kategori</th>
                                <th>Uraian</th>
                                <th style="width: 80px;">Satuan</th>
                                <th style="width: 100px;">Volume</th>
                                <th style="width: 140px;">Harga Satuan</th>
                                <th style="width: 140px;">Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($details as $d)
                                <tr>
                                    <td>{{ $d->kategori === 'pipa_persil' ? 'Pipa Persil' : 'Pipa Dinas' }}</td>
                                    <td>{{ $d->uraian }}</td>
                                    <td>{{ $d->satuan }}</td>
                                    <td>{{ rtrim(rtrim(number_format($d->volume, 3, ',', '.'), '0'), ',') }}</td>
                                    <td>Rp {{ number_format($d->harga_satuan, 0, ',', '.') }}</td>
                                    <td>Rp {{ number_format($d->jumlah, 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-3">Belum ada rincian RAB.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </section>
</x-perencanaan>
