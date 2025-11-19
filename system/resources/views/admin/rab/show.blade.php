<x-admin-dashboard>
  @php
    /** @var \App\Models\RabHeader $row */
    $spko      = $row->spko;
    $pengajuan = $spko->pengajuan ?? null;
    $details   = $row->details ?? collect();

    $fmt = fn($v) => $v ? \Illuminate\Support\Carbon::parse($v)->format('d/m/Y H:i') : '-';
  @endphp

  <div class="content-header d-flex align-items-center justify-content-between mb-3">
    <div>
      <h1 class="m-0 text-primary fw-bold">
        <i class="fas fa-file-invoice-dollar"></i> Detail RAB Disetujui
      </h1>
      <small class="text-muted">
        SPKO: <span class="text-monospace">{{ $spko->nomor_spko ?? '-' }}</span> —
        RAB: <span class="text-monospace">{{ $row->nomor_rab }}</span>
      </small>
    </div>
    <div>
      <a href="{{ url('admin/rab') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Kembali
      </a>
    </div>
  </div>

  <section class="content">
    <div class="row mb-3">
      <div class="col-md-6">
        <div class="card shadow-sm h-100">
          <div class="card-body">
            <h6 class="text-uppercase text-muted">Identitas Pelanggan</h6>
            <dl class="row mb-0">
              <dt class="col-sm-4">No. Pendaftaran</dt>
              <dd class="col-sm-8 text-monospace">
                {{ $pengajuan->no_pendaftaran ?? '-' }}
              </dd>

              <dt class="col-sm-4">Nama</dt>
              <dd class="col-sm-8">
                {{ $row->nama_pelanggan ?? $pengajuan->pemohon_nama ?? '-' }}
              </dd>

              <dt class="col-sm-4">Alamat</dt>
              <dd class="col-sm-8">
                {{ $row->alamat ?? $pengajuan->alamat_pemasangan ?? '-' }}
              </dd>

              <dt class="col-sm-4">Kategori Tarif</dt>
              <dd class="col-sm-8">{{ $row->kategori_tarif ?? '-' }}</dd>
            </dl>
          </div>
        </div>
      </div>

      <div class="col-md-6">
        <div class="card shadow-sm h-100">
          <div class="card-body">
            <h6 class="text-uppercase text-muted">Ringkasan RAB</h6>
            <dl class="row mb-0">
              <dt class="col-sm-5">Terobos</dt>
              <dd class="col-sm-7">
                @if($row->pemasangan_terobos)
                  <span class="badge badge-danger">Ya</span>
                @else
                  <span class="badge badge-secondary">Tidak</span>
                @endif
              </dd>

              <dt class="col-sm-5">Subtotal Pipa Dinas</dt>
              <dd class="col-sm-7">Rp {{ number_format($row->subtotal_pipa_dinas, 0, ',', '.') }}</dd>

              <dt class="col-sm-5">Subtotal Pipa Persil</dt>
              <dd class="col-sm-7">Rp {{ number_format($row->subtotal_pipa_persil, 0, ',', '.') }}</dd>

              <dt class="col-sm-5">Biaya Pendaftaran</dt>
              <dd class="col-sm-7">Rp {{ number_format($row->biaya_pendaftaran, 0, ',', '.') }}</dd>

              <dt class="col-sm-5">Biaya Admin</dt>
              <dd class="col-sm-7">Rp {{ number_format($row->biaya_admin, 0, ',', '.') }}</dd>

              <dt class="col-sm-5">Total RAB</dt>
              <dd class="col-sm-7 font-weight-bold text-primary">
                Rp {{ number_format($row->total, 0, ',', '.') }}
              </dd>

              <dt class="col-sm-5">Disetujui</dt>
              <dd class="col-sm-7">{{ $fmt($row->approved_at) }}</dd>
            </dl>
          </div>
        </div>
      </div>
    </div>

    {{-- Tabel rincian item --}}
    <div class="card shadow-sm">
      <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <strong><i class="fas fa-list-ul"></i> Rincian Item RAB</strong>
        {{-- Di sini nanti bisa ditambah tombol "Buat RNA" / "Cetak Persetujuan" --}}
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
                <td colspan="6" class="text-center text-muted py-3">
                  Belum ada rincian item RAB.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </section>
</x-admin-dashboard>
