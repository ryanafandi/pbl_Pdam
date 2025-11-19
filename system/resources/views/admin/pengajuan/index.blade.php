{{-- resources/views/admin/pengajuan/index.blade.php --}}
<x-admin-dashboard>
  <div class="content-header">
    <div class="container-fluid">
      <div class="d-flex align-items-center justify-content-between mb-2">
        <div>
          <h1 class="m-0 text-primary fw-bold">
            <i class="fas fa-file-alt"></i> Daftar Pengajuan Pendaftaran
          </h1>
          <small class="text-muted">Kelola pendaftaran pelanggan baru.</small>
        </div>
      </div>
    </div>
  </div>

  <section class="content">
    <div class="container-fluid">

      {{-- Flash message --}}
      @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
      @endif
      @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
      @endif

      <div class="card shadow-sm">
        <div class="card-body">

          {{-- Filter & Pencarian --}}
          <form class="row g-2 mb-3" method="GET" action="{{ url('admin/pengajuan') }}">
            <div class="col-md-4">
              <input type="text"
                     name="s"
                     class="form-control"
                     placeholder="Cari no pendaftaran / nama / alamat"
                     value="{{ request('s') }}">
            </div>

            <div class="col-md-3">
              <select name="status" class="form-control">
                <option value="">— status persetujuan —</option>
                @foreach(\App\Models\Pengajuan::approvalStatuses() as $st)
                  <option value="{{ $st }}" {{ request('status') === $st ? 'selected' : '' }}>
                    @php
                      $tmp = new \App\Models\Pengajuan(['status' => $st]);
                    @endphp
                    {{ $tmp->status_label }}
                  </option>
                @endforeach
              </select>
            </div>

            <div class="col-md-3">
              <select name="progress_status" class="form-control">
                <option value="">— status progres —</option>
                @foreach(\App\Models\Pengajuan::progressStatuses() as $pg)
                  <option value="{{ $pg }}" {{ request('progress_status') === $pg ? 'selected' : '' }}>
                    @php
                      $tmp = new \App\Models\Pengajuan(['progress_status' => $pg]);
                    @endphp
                    {{ $tmp->progress_label }}
                  </option>
                @endforeach
              </select>
            </div>

            <div class="col-md-2 d-flex align-items-center">
              <div class="form-check mr-2">
                <input type="checkbox"
                       name="siap_spko"
                       id="siap_spko"
                       class="form-check-input"
                       value="1"
                       {{ request()->boolean('siap_spko') ? 'checked' : '' }}>
                <label class="form-check-label" for="siap_spko">
                  Siap SPKO
                </label>
              </div>
              <button class="btn btn-primary btn-sm ml-auto" type="submit">
                <i class="fas fa-search"></i> Cari
              </button>
            </div>
          </form>

          {{-- Tabel data --}}
          <div class="table-responsive">
            <table class="table table-bordered table-hover mb-0">
              <thead class="thead-light">
                <tr>
                  <th style="width: 60px;">#</th>
                  <th>No. Pendaftaran</th>
                  <th>Nama Pemohon</th>
                  <th>Alamat</th>
                  <th>Status</th>
                  <th>Progres</th>
                  <th>Tanggal</th>
                  <th style="width: 260px;">Aksi</th>
                </tr>
              </thead>
              <tbody>
                @forelse($data as $index => $row)
                  <tr>
                    <td>{{ $data->firstItem() + $index }}</td>
                    <td class="text-monospace">{{ $row->no_pendaftaran }}</td>
                    <td>{{ $row->pemohon_nama }}</td>
                    <td>{{ $row->alamat_pemasangan }}</td>
                    <td>
                      <span class="badge {{ $row->status_badge_class }}">
                        {{ $row->status_label }}
                      </span>
                    </td>
                    <td>
                      <span class="badge {{ $row->progress_badge_class }}">
                        {{ $row->progress_label }}
                      </span>
                    </td>
                    <td>{{ $row->created_at?->format('d/m/Y H:i') }}</td>
                    <td>
                      {{-- Kelompok tombol aksi supaya rapi --}}
                      <div class="d-flex flex-wrap" style="gap: .25rem;">
                        {{-- Lihat --}}
                        <a href="{{ url('admin/pengajuan/'.$row->id) }}"
                           class="btn btn-sm btn-outline-secondary">
                          <i class="fas fa-eye"></i> Lihat
                        </a>

                        {{-- Edit --}}
                        <a href="{{ url('admin/pengajuan/'.$row->id.'/edit') }}"
                           class="btn btn-sm btn-info">
                          <i class="fas fa-edit"></i> Edit
                        </a>

                        {{-- Buat SPKO (jika sudah disetujui & belum punya SPKO) --}}
                        @if($row->boleh_buat_spko)
                          <a href="{{ url('admin/spko/create?pengajuan_id='.$row->id) }}"
                             class="btn btn-sm btn-primary">
                            <i class="fas fa-file"></i> Buat SPKO
                          </a>
                        @endif

                        {{-- Kirim ke Direktur (hanya SUBMITTED) --}}
                        @if($row->status === \App\Models\Pengajuan::ST_SUBMITTED)
                          <form action="{{ url('admin/pengajuan/'.$row->id.'/send-to-director') }}"
                                method="POST"
                                class="d-inline">
                            @csrf
                            <button type="submit"
                                    class="btn btn-sm btn-success"
                                    onclick="return confirm('Kirim pengajuan ini ke Direktur?');">
                              <i class="fas fa-paper-plane"></i> Kirim ke Direktur
                            </button>
                          </form>

                          {{-- Tolak oleh Admin --}}
                          <form action="{{ url('admin/pengajuan/'.$row->id.'/reject') }}"
                                method="POST"
                                class="d-inline">
                            @csrf
                            <button type="submit"
                                    class="btn btn-sm btn-danger"
                                    onclick="return confirm('Yakin ingin MENOLAK pengajuan ini?');">
                              <i class="fas fa-times"></i> Tolak
                            </button>
                          </form>
                        @endif

                        {{-- Hapus (selalu ada, hati-hati pakai konfirmasi) --}}
                        <form action="{{ url('admin/pengajuan/'.$row->id) }}"
                              method="POST"
                              class="d-inline">
                          @csrf
                          @method('DELETE')
                          <button type="submit"
                                  class="btn btn-sm btn-outline-danger"
                                  onclick="return confirm('Hapus pengajuan ini secara permanen?');">
                            <i class="fas fa-trash-alt"></i> Hapus
                          </button>
                        </form>
                      </div>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="8" class="text-center text-muted">
                      Tidak ada data.
                    </td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>

          <div class="mt-3">
            {{ $data->links() }}
          </div>
        </div>
      </div>
    </div>
  </section>
</x-admin-dashboard>
