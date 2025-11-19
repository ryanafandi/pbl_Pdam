<x-kasir>
  <div class="content-header">
    <div class="container-fluid">
      <div class="d-flex justify-content-between align-items-center mb-2">
        <div>
          <h1 class="m-0 text-primary fw-bold">
            <i class="fas fa-receipt"></i> Pembayaran Tagihan
          </h1>
          <small class="text-muted">
            Proses pembayaran dokumen biaya sambungan baru.
          </small>
        </div>
        <div>
          <a href="{{ url('kasir/tagihan') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
          </a>
        </div>
      </div>
    </div>
  </div>

  <section class="content">
    <div class="container-fluid">

      {{-- Flash message --}}
      @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
      @endif
      @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          <i class="fas fa-exclamation-triangle mr-1"></i> {{ session('error') }}
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
      @endif

      {{-- Error validasi --}}
      @if($errors->any())
        <div class="alert alert-danger">
          <ul class="mb-0">
            @foreach($errors->all() as $err)
              <li>{{ $err }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      @php
        $status = $row->billing_status;
        $label  = $row->billing_status_label ?? ($status ?? 'Draft Dokumen');
        $cls    = $status === 'PAID'
                    ? 'badge-success'
                    : ($status === 'SENT' ? 'badge-primary' : 'badge-secondary');
        $canPay = $status === 'SENT';
        $isPaid = $status === 'PAID';
      @endphp

      <div class="row">
        {{-- IDENTITAS TAGIHAN --}}
        <div class="col-md-7 mb-3">
          <div class="card shadow-sm">
            <div class="card-body">
              <h5 class="mb-3">Identitas Tagihan</h5>
              <dl class="row small mb-0">
                <dt class="col-sm-4">No. RAB</dt>
                <dd class="col-sm-8 text-monospace">{{ $row->nomor_rab ?? '-' }}</dd>

                <dt class="col-sm-4">No. RNA</dt>
                <dd class="col-sm-8 text-monospace">{{ $row->rna_nomor ?? '-' }}</dd>

                <dt class="col-sm-4">No. Persetujuan</dt>
                <dd class="col-sm-8 text-monospace">{{ $row->persetujuan_nomor ?? '-' }}</dd>

                <dt class="col-sm-4">Nama Pelanggan</dt>
                <dd class="col-sm-8">
                  {{ $row->nama_pelanggan ?? ($row->spko->pengajuan->pemohon_nama ?? '-') }}
                </dd>

                <dt class="col-sm-4">Alamat</dt>
                <dd class="col-sm-8">
                  {{ $row->alamat ?? ($row->spko->pengajuan->alamat_pemasangan ?? '-') }}
                </dd>

                <dt class="col-sm-4">Total Tagihan</dt>
                <dd class="col-sm-8 font-weight-bold">
                  Rp {{ number_format($row->total ?? 0, 0, ',', '.') }}
                </dd>

                <dt class="col-sm-4">Terbilang</dt>
                <dd class="col-sm-8">
                  {{ ucfirst($row->total_terbilang ?? '-') }}
                </dd>
              </dl>
            </div>
          </div>
        </div>

        {{-- STATUS & FORM PEMBAYARAN --}}
        <div class="col-md-5 mb-3">
          <div class="card shadow-sm">
            <div class="card-body">
              <h5 class="mb-3">Status & Pembayaran</h5>

              <p>
                Status:
                <span class="badge {{ $cls }}">{{ $label }}</span>
              </p>

              <dl class="row small mb-2">
                <dt class="col-sm-4">Dikirim Admin</dt>
                <dd class="col-sm-8">
                  {{ $row->billing_sent_at ? $row->billing_sent_at->format('d/m/Y H:i') : '-' }}
                </dd>

                <dt class="col-sm-4">Dibayar</dt>
                <dd class="col-sm-8">
                  {{ $row->billing_paid_at ? $row->billing_paid_at->format('d/m/Y H:i') : '-' }}
                </dd>
              </dl>

              @if($isPaid)
                <div class="alert alert-success py-2 mb-0">
                  Tagihan ini sudah <strong>LUNAS</strong>.
                </div>
              @elseif(!$canPay)
                <div class="alert alert-warning py-2 mb-3">
                  Tagihan belum dikirim oleh admin. Kasir belum dapat memproses pembayaran.
                </div>
              @else
                {{-- FORM PEMBAYARAN (hanya jika status = SENT) --}}
                <form action="{{ url('kasir/tagihan/'.$row->id.'/pay') }}" method="POST">
                  @csrf

                  <div class="form-group">
                    <label>Tanggal/Jam Pembayaran</label>
                    <input type="datetime-local"
                           name="paid_at"
                           class="form-control form-control-sm"
                           value="{{ old('paid_at') }}">
                    <small class="form-text text-muted">
                      Kosongkan untuk otomatis pakai waktu saat ini.
                    </small>
                  </div>

                  <div class="form-group">
                    <label>Catatan Kasir (opsional)</label>
                    <textarea name="payment_note"
                              rows="2"
                              class="form-control form-control-sm">{{ old('payment_note') }}</textarea>
                  </div>

                  <button type="submit"
                          class="btn btn-success btn-block"
                          onclick="return confirm('Yakin ingin menandai tagihan ini LUNAS?');">
                    <i class="fas fa-check"></i> Tandai Lunas
                  </button>
                </form>
              @endif
            </div>
          </div>
        </div>
      </div>

    </div>
  </section>
</x-kasir>
