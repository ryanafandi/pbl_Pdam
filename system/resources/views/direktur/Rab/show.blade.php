<x-direktur>
  @php
    $rab      = $rab ?? $row->rab;
    $details  = $rab?->details ?? collect();
    $pengajuan = $row->pengajuan;
    $fmt = fn($v) => $v ? \Illuminate\Support\Carbon::parse($v)->format('d/m/Y H:i') : '-';
  @endphp

  <div class="content-header d-flex align-items-center justify-content-between mb-3">
    <div>
      <h1 class="m-0 text-primary fw-bold">
        <i class="fas fa-file-invoice-dollar"></i> Detail RAB
      </h1>
      <small class="text-muted">
        SPKO: <span class="text-monospace">{{ $row->nomor_spko }}</span>
        @if($rab)
          &mdash; RAB: <span class="text-monospace">{{ $rab->nomor_rab }}</span>
        @endif
      </small>
    </div>

    @if($rab && $rab->status === 'dikirim')
      <div>
        <form action="{{ url('direktur/rab/'.$row->id.'/approve') }}"
              method="POST"
              class="d-inline"
              onsubmit="return confirm('Setujui RAB ini?')">
          @csrf
          <button class="btn btn-success">
            <i class="fas fa-check"></i> Setujui
          </button>
        </form>

        <a href="#form-reject" class="btn btn-danger">
          <i class="fas fa-times"></i> Tolak
        </a>
      </div>
    @endif
  </div>

  <section class="content">
    @if(session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
      <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @if(!$rab)
      <div class="alert alert-warning">
        RAB untuk SPKO ini belum disusun.
      </div>
    @else
      <div class="row mb-3">
        <div class="col-md-6">
          <div class="card shadow-sm h-100">
            <div class="card-body">
              <h6 class="text-uppercase text-muted">Identitas Pelanggan</h6>
              <dl class="row mb-0">
                <dt class="col-sm-4">No. Pendaftaran</dt>
                <dd class="col-sm-8 text-monospace">{{ $pengajuan->no_pendaftaran ?? '-' }}</dd>

                <dt class="col-sm-4">Nama</dt>
                <dd class="col-sm-8">{{ $rab->nama_pelanggan }}</dd>

                <dt class="col-sm-4">Alamat</dt>
                <dd class="col-sm-8">{{ $rab->alamat }}</dd>

                <dt class="col-sm-4">Kategori Tarif</dt>
                <dd class="col-sm-8">{{ $rab->kategori_tarif ?? '-' }}</dd>
              </dl>
            </div>
          </div>
        </div>

        <div class="col-md-6">
          <div class="card shadow-sm h-100">
            <div class="card-body">
              <h6 class="text-uppercase text-muted">Ringkasan RAB</h6>
              <dl class="row mb-0">
                <dt class="col-sm-5">Status</dt>
                <dd class="col-sm-7">
                  <span class="badge badge-info">{{ strtoupper($rab->status ?? 'draft') }}</span>
                </dd>

                <dt class="col-sm-5">Subtotal Pipa Dinas</dt>
                <dd class="col-sm-7">Rp {{ number_format($rab->subtotal_pipa_dinas, 0, ',', '.') }}</dd>

                <dt class="col-sm-5">Subtotal Pipa Persil</dt>
                <dd class="col-sm-7">Rp {{ number_format($rab->subtotal_pipa_persil, 0, ',', '.') }}</dd>

                <dt class="col-sm-5">Biaya Pendaftaran</dt>
                <dd class="col-sm-7">Rp {{ number_format($rab->biaya_pendaftaran, 0, ',', '.') }}</dd>

                <dt class="col-sm-5">Biaya Admin</dt>
                <dd class="col-sm-7">Rp {{ number_format($rab->biaya_admin, 0, ',', '.') }}</dd>

                <dt class="col-sm-5">Total RAB</dt>
                <dd class="col-sm-7 font-weight-bold text-primary">
                  Rp {{ number_format($rab->total, 0, ',', '.') }}
                </dd>

                <dt class="col-sm-5">Dikirim ke Direktur</dt>
                <dd class="col-sm-7">{{ $fmt($rab->sent_to_director_at) }}</dd>

                <dt class="col-sm-5">Disetujui</dt>
                <dd class="col-sm-7">
                  {{ $rab->approved_by ? $rab->approved_by.' ('.$fmt($rab->approved_at).')' : '-' }}
                </dd>

                <dt class="col-sm-5">Ditolak</dt>
                <dd class="col-sm-7">
                  {{ $rab->rejected_by ? $rab->rejected_by.' ('.$fmt($rab->rejected_at).')' : '-' }}
                </dd>
              </dl>
            </div>
          </div>
        </div>
      </div>

      {{-- Tabel detail --}}
      <div class="card shadow-sm mb-3">
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

      {{-- Form tolak --}}
      @if($rab->status === 'dikirim')
        <div class="card shadow-sm" id="form-reject">
          <div class="card-header bg-white">
            <strong><i class="fas fa-times"></i> Form Penolakan RAB</strong>
          </div>
          <div class="card-body">
            <form action="{{ url('direktur/rab/'.$row->id.'/reject') }}" method="POST">
              @csrf
              <div class="form-group">
                <label for="alasan">Alasan penolakan</label>
                <textarea name="alasan" id="alasan" rows="3"
                          class="form-control @error('alasan') is-invalid @enderror"
                          required>{{ old('alasan') }}</textarea>
                @error('alasan')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
              <button class="btn btn-danger">
                <i class="fas fa-times"></i> Kirim Penolakan
              </button>
            </form>
          </div>
        </div>
      @endif
    @endif
  </section>
</x-direktur>
