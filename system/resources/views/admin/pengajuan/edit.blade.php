{{-- resources/views/admin/pengajuan/edit.blade.php --}}
<x-admin-dashboard>
  <div class="content-header">
    <div class="container-fluid">
      <div class="d-flex align-items-center justify-content-between mb-2">
        <div>
          <h1 class="m-0 text-primary fw-bold">
            <i class="fas fa-edit"></i> Edit Pengajuan
          </h1>
          <small class="text-muted">
            No. Pendaftaran: <span class="text-monospace">{{ $row->no_pendaftaran }}</span>
          </small>
        </div>
        <a href="{{ url('admin/pengajuan/'.$row->id) }}" class="btn btn-secondary btn-sm">
          <i class="fas fa-arrow-left"></i> Kembali ke Detail
        </a>
      </div>
    </div>
  </div>

  <section class="content">
    <div class="container-fluid">

      @if($errors->any())
        <div class="alert alert-danger">
          <ul class="mb-0">
            @foreach($errors->all() as $err)
              <li>{{ $err }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
      @endif
      @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
      @endif

      <div class="row">
        <div class="col-md-8">
          <div class="card shadow-sm">
            <div class="card-header">
              <h3 class="card-title">Catatan & Progres</h3>
            </div>
            <form action="{{ url('admin/pengajuan/'.$row->id) }}" method="POST">
              @csrf
              @method('PUT')
              <div class="card-body">
                <div class="form-group">
                  <label for="catatan_admin">Catatan Admin</label>
                  <textarea name="catatan_admin"
                            id="catatan_admin"
                            rows="5"
                            class="form-control"
                            placeholder="Catatan internal admin (opsional)">{{ old('catatan_admin', $row->catatan_admin) }}</textarea>
                </div>

                <div class="form-group">
                  <label for="progress_status">Status Progres Pemasangan</label>
                  <select name="progress_status" id="progress_status" class="form-control">
                    <option value="">— tidak mengubah progres —</option>
                    @foreach(\App\Models\Pengajuan::progressStatuses() as $pg)
                      @php
                        $tmp = new \App\Models\Pengajuan(['progress_status' => $pg]);
                      @endphp
                      <option value="{{ $pg }}"
                        {{ old('progress_status', $row->progress_status) === $pg ? 'selected' : '' }}>
                        {{ $tmp->progress_label }}
                      </option>
                    @endforeach
                  </select>
                  <small class="form-text text-muted">
                    Progres saat ini:
                    <span class="badge {{ $row->progress_badge_class }}">
                      {{ $row->progress_label }}
                    </span>
                    @if($row->progress_updated_at)
                      (diubah {{ $row->progress_updated_at->format('d/m/Y H:i') }})
                    @endif
                  </small>
                </div>
              </div>
              <div class="card-footer text-right">
                <button type="submit" class="btn btn-primary">
                  <i class="fas fa-save"></i> Simpan Perubahan
                </button>
              </div>
            </form>
          </div>
        </div>

        {{-- Info singkat di samping --}}
        <div class="col-md-4">
          <div class="card shadow-sm">
            <div class="card-header">
              <h3 class="card-title">Ringkasan Pengajuan</h3>
            </div>
            <div class="card-body">
              <p><strong>Nama Pemohon:</strong><br>{{ $row->pemohon_nama }}</p>
              <p><strong>Alamat Pemasangan:</strong><br>{{ $row->alamat_pemasangan }}</p>
              <p><strong>Status Persetujuan:</strong><br>
                <span class="badge {{ $row->status_badge_class }}">
                  {{ $row->status_label }}
                </span>
              </p>
              <p><strong>Tanggal Pengajuan:</strong><br>
                {{ $row->created_at?->format('d/m/Y H:i') }}
              </p>
            </div>
          </div>
        </div>
      </div>

    </div>
  </section>
</x-admin-dashboard>
