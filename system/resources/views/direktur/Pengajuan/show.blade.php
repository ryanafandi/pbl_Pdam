<x-direktur>

<style>
  /* --------- Aesthetic tweaks ---------- */
  .page-header {
    background: linear-gradient(135deg, #1e88e5, #1565c0);
    color: #fff;
    border-radius: .5rem .5rem 0 0;
  }
  .btn-pill {
    border-radius: 999px;
    padding-inline: 16px;
    transition: all .15s ease;
  }
  .btn-white-outline {
    background: transparent;
    border: 1px solid rgba(255,255,255,.85);
    color: #fff;
  }
  .btn-white-outline:hover {
    background: #fff;
    color: #1565c0;
  }
  .section-title {
    font-weight: 600;
    color: #1565c0;
    border-left: 4px solid #1565c0;
    padding-left: 10px;
    margin-top: .25rem;
    margin-bottom: .75rem;
    text-transform: uppercase;
    letter-spacing: .5px;
  }
  .info-box {
    background: #f8fafc;
    border: 1px solid #eef2f7;
    border-radius: 12px;
    padding: 18px;
    box-shadow: 0 2px 6px rgba(21,101,192,.06);
    margin-bottom: 1rem;
  }
  .info-box dt { color:#334155; font-weight:600; }
  .info-box dd { color:#475569; margin-bottom:.5rem; }
  .doc-card {
    border: 1px dashed #cbd5e1;
    border-radius: 12px;
    padding: 14px;
    text-align: center;
    background: #fff;
  }
  .doc-card .title { font-weight:600; color:#334155; }
  .doc-card .empty { color:#94a3b8; }
  .badge-soft {
    border-radius: 999px;
    padding: 6px 10px;
    font-weight: 600;
    letter-spacing: .2px;
  }
</style>

<div class="card shadow-sm">
  <!-- Header -->
  <div class="card-header page-header d-flex flex-wrap justify-content-between align-items-center">
    <div class="d-flex align-items-center">
      <span class="mr-2" style="display:inline-flex;width:34px;height:34px;border-radius:50%;background:rgba(255,255,255,.15);align-items:center;justify-content:center;">
        <i class="fas fa-file-alt"></i>
      </span>
      <div>
        <h5 class="mb-0 font-weight-bold">Detail Pengajuan</h5>
        <small class="opacity-75">No. Pendaftaran: <strong>{{ $row->no_pendaftaran }}</strong></small>
      </div>
    </div>

    <div class="mt-2 mt-sm-0">
      <a href="{{ url('direktur/approval/pendaftaran') }}" class="btn btn-sm btn-pill btn-white-outline mr-2">
        <i class="fas fa-arrow-left mr-1"></i> Kembali
      </a>

      @if($row->status !== \App\Models\Pengajuan::ST_APPROVED)
      <a href="#aksi-direktur" class="btn btn-sm btn-pill btn-light text-primary">
        <i class="fas fa-bolt mr-1"></i> Aksi Cepat
      </a>
      @endif
    </div>
  </div>

  <div class="card-body">
    {{-- ===== Data Pemohon ===== --}}
    <h6 class="section-title"><i class="fas fa-user mr-2"></i>Data Pemohon</h6>
    <div class="info-box">
      <dl class="row mb-0">
        <dt class="col-sm-3">Nama Pemohon</dt><dd class="col-sm-9">{{ $row->pemohon_nama }}</dd>
        <dt class="col-sm-3">No. Telepon</dt><dd class="col-sm-9">{{ $row->nomor_telepon ?? '-' }}</dd>
        <dt class="col-sm-3">Email</dt><dd class="col-sm-9">{{ $row->email ?? '-' }}</dd>
        <dt class="col-sm-3">Pekerjaan</dt><dd class="col-sm-9">{{ $row->pekerjaan ?? '-' }}</dd>
      </dl>
    </div>

    {{-- ===== Pemasangan ===== --}}
    <h6 class="section-title"><i class="fas fa-map-marker-alt mr-2"></i>Pemasangan</h6>
    <div class="info-box">
      <dl class="row mb-0">
        <dt class="col-sm-3">Alamat</dt><dd class="col-sm-9">{{ $row->alamat_pemasangan }}</dd>
        <dt class="col-sm-3">Peruntukan</dt><dd class="col-sm-9">{{ $row->peruntukan ?? '-' }}</dd>
        <dt class="col-sm-3">Jumlah Kran</dt><dd class="col-sm-9">{{ $row->jumlah_kran ?? 0 }}</dd>
        <dt class="col-sm-3">Penghuni Tetap</dt><dd class="col-sm-9">{{ $row->penghuni_tetap ?? 0 }}</dd>
        <dt class="col-sm-3">Penghuni Tidak Tetap</dt><dd class="col-sm-9">{{ $row->penghuni_tidak_tetap ?? 0 }}</dd>
        <dt class="col-sm-3">Pelanggan Terdekat</dt><dd class="col-sm-9">{{ $row->pelanggan_terdekat ?? '-' }}</dd>
      </dl>
    </div>

    {{-- ===== Dokumen ===== --}}
    <h6 class="section-title"><i class="fas fa-folder-open mr-2"></i>Dokumen</h6>
    <div class="row">
      <div class="col-md-3 col-6 mb-3">
        <div class="doc-card">
          <div class="title">KTP</div>
          @if($row->ktp_url)
            <a href="{{ url($row->ktp_url) }}" target="_blank" class="btn btn-sm btn-outline-primary btn-pill mt-2">
              <i class="fas fa-eye mr-1"></i> Lihat
            </a>
          @else
            <div class="empty mt-2">Tidak ada</div>
          @endif
        </div>
      </div>
      <div class="col-md-3 col-6 mb-3">
        <div class="doc-card">
          <div class="title">KK</div>
          @if($row->kk_url)
            <a href="{{ url($row->kk_url) }}" target="_blank" class="btn btn-sm btn-outline-primary btn-pill mt-2">
              <i class="fas fa-eye mr-1"></i> Lihat
            </a>
          @else
            <div class="empty mt-2">Tidak ada</div>
          @endif
        </div>
      </div>
      <div class="col-md-3 col-6 mb-3">
        <div class="doc-card">
          <div class="title">Foto Rumah</div>
          @if($row->foto_rumah_url)
            <a href="{{ url($row->foto_rumah_url) }}" target="_blank" class="btn btn-sm btn-outline-primary btn-pill mt-2">
              <i class="fas fa-eye mr-1"></i> Lihat
            </a>
          @else
            <div class="empty mt-2">Tidak ada</div>
          @endif
        </div>
      </div>
      <div class="col-md-3 col-6 mb-3">
        <div class="doc-card">
          <div class="title">Denah</div>
          @if($row->denah_url)
            <a href="{{ url($row->denah_url) }}" target="_blank" class="btn btn-sm btn-outline-primary btn-pill mt-2">
              <i class="fas fa-eye mr-1"></i> Lihat
            </a>
          @else
            <div class="empty mt-2">Tidak ada</div>
          @endif
        </div>
      </div>
    </div>

    {{-- ===== Status ===== --}}
    <h6 class="section-title"><i class="fas fa-clipboard-check mr-2"></i>Status</h6>
    <div class="info-box">
      <dl class="row mb-0">
        <dt class="col-sm-3">Status Pengajuan</dt>
        <dd class="col-sm-9">
          <span class="badge badge-soft {{ $row->status_badge_class }}">{{ $row->status }}</span>
        </dd>

        <dt class="col-sm-3">Catatan Admin</dt>
        <dd class="col-sm-9">{{ $row->catatan_admin ?: '-' }}</dd>

        <dt class="col-sm-3">Catatan Direktur</dt>
        <dd class="col-sm-9">{{ $row->catatan_direktur ?: '-' }}</dd>
      </dl>
    </div>

    {{-- ===== Aksi Direktur ===== --}}
    {{-- ===== Aksi Direktur (selaras & tinggi sama) ===== --}}
<style>
  .aksi-wrap{display:flex;gap:16px;flex-wrap:wrap}
  .aksi-card{
    flex:1 1 320px;
    background:#f8fafc;border:1px solid #eef2f7;border-radius:12px;
    padding:16px;box-shadow:0 2px 6px rgba(21,101,192,.06);
    display:flex;flex-direction:column;min-height:220px;
  }
  .aksi-card textarea{min-height:110px;resize:vertical}
  .aksi-footer{margin-top:auto;display:flex;gap:8px}
</style>

@if($row->status !== \App\Models\Pengajuan::ST_APPROVED)
<a id="aksi-direktur"></a>
<div class="aksi-wrap">
  {{-- Panel SETUJUI --}}
  <form action="{{ url('direktur/approval/pendaftaran/'.$row->id.'/approve') }}" method="POST" class="aksi-card">
    @csrf
    <label class="font-weight-semibold mb-2">Catatan Direktur (opsional)</label>
    <textarea name="catatan_direktur" class="form-control mb-2" placeholder="Boleh dikosongkan">{{ old('catatan_direktur', $row->catatan_direktur) }}</textarea>
    <div class="aksi-footer">
      <button class="btn btn-success btn-pill">
        <i class="fas fa-check mr-1"></i> Setujui
      </button>
    </div>
  </form>

  {{-- Panel TOLAK --}}
  <form action="{{ url('direktur/approval/pendaftaran/'.$row->id.'/reject') }}" method="POST" onsubmit="return confirm('Yakin menolak pengajuan ini?');" class="aksi-card">
    @csrf
    <label class="font-weight-semibold mb-2">Alasan Penolakan <span class="text-danger">*</span></label>
    <textarea name="catatan_direktur" class="form-control mb-2" required placeholder="Tuliskan alasan penolakan"></textarea>
    <div class="aksi-footer">
      <button class="btn btn-danger btn-pill">
        <i class="fas fa-times mr-1"></i> Tolak
      </button>
    </div>
  </form>
</div>
@endif


  </div>
</div>

</x-direktur>
