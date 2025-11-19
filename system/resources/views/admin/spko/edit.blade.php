<x-admin-dashboard>
  <div class="content-header">
    <div class="container-fluid d-flex justify-content-between align-items-center mb-2">
      <h1 class="m-0 text-primary fw-bold"><i class="fas fa-edit"></i> Edit SPKO</h1>
      <div>
        <a href="{{ url('admin/spko/'.$row->id) }}" class="btn btn-light"><i class="fas fa-arrow-left"></i> Kembali</a>
      </div>
    </div>
  </div>

  <section class="content">
    <div class="container-fluid">
      @if($errors->any())
        <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
      @endif

      <div class="card shadow-sm">
        <form method="POST" action="{{ url('admin/spko/'.$row->id) }}">
          @csrf @method('PUT')
          <div class="card-body">
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label>Nomor SPKO</label>
                  <input type="text" name="nomor_spko" class="form-control" value="{{ old('nomor_spko',$row->nomor_spko) }}">
                </div>
                <div class="form-group">
                  <label>Tanggal SPKO</label>
                  <input type="date" name="tanggal_spko" class="form-control" value="{{ old('tanggal_spko', optional($row->tanggal_spko)->toDateString()) }}">
                </div>
                <div class="form-group">
                  <label>Tujuan</label>
                  <input type="text" name="tujuan" class="form-control" value="{{ old('tujuan',$row->tujuan) }}">
                </div>
                <div class="form-group">
                  <label>Status</label>
                  <select name="status" class="form-control">
                    @foreach(['DRAFT','SENT_TO_PLANNING','SENT_TO_DIRECTOR','APPROVED','REJECTED','DONE'] as $st)
                      <option value="{{ $st }}" {{ old('status',$row->status)===$st?'selected':'' }}>{{ $st }}</option>
                    @endforeach
                  </select>
                </div>
              </div>

              <div class="col-md-6">
                <div class="form-group">
                  <label>Nama Pemohon</label>
                  <input type="text" name="pemilik_nama" class="form-control" value="{{ old('pemilik_nama',$row->pemilik_nama) }}">
                </div>
                <div class="form-group">
                  <label>Alamat</label>
                  <input type="text" name="alamat" class="form-control" value="{{ old('alamat',$row->alamat) }}">
                </div>
                <div class="form-group">
                  <label>Lokasi / Kelurahan</label>
                  <input type="text" name="lokasi" class="form-control" value="{{ old('lokasi',$row->lokasi) }}">
                </div>
                <div class="form-group">
                  <label>Kepada (Jabatan)</label>
                  <input type="text" name="kepada_jabatan" class="form-control" value="{{ old('kepada_jabatan',$row->kepada_jabatan) }}">
                </div>
              </div>
            </div>

            <hr>

            <div class="form-group">
              <label>Laporan Hasil Opname</label>
              <textarea name="laporan_ringkas" class="form-control" rows="4">{{ old('laporan_ringkas',$row->laporan_ringkas) }}</textarea>
            </div>

            {{-- <div class="form-group">
              <label>Terobos?</label><br>
              @php $ter = old('terobos', is_null($row->terobos) ? '' : (string)$row->terobos); @endphp
              <label class="mr-3"><input type="radio" name="terobos" value="1" {{ $ter==='1'?'checked':'' }}> Ya</label>
              <label class="mr-3"><input type="radio" name="terobos" value="0" {{ $ter==='0'?'checked':'' }}> Tidak</label>
              <label><input type="radio" name="terobos" value=""  {{ $ter===''?'checked':'' }}> Kosongkan</label>
            </div> --}}

            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label>Disurvey oleh (Nama)</label>
                  <input type="text" name="disurvey_oleh_nama" class="form-control" value="{{ old('disurvey_oleh_nama',$row->disurvey_oleh_nama) }}">
                </div>
                <div class="form-group">
                  <label>Disurvey oleh (NIPP/NIK)</label>
                  <input type="text" name="disurvey_oleh_nipp" class="form-control" value="{{ old('disurvey_oleh_nipp',$row->disurvey_oleh_nipp) }}">
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label>Kabag Teknik (Nama)</label>
                  <input type="text" name="kabag_teknik_nama" class="form-control" value="{{ old('kabag_teknik_nama',$row->kabag_teknik_nama) }}">
                </div>
                <div class="form-group">
                  <label>Kabag Teknik (NIPP)</label>
                  <input type="text" name="kabag_teknik_nipp" class="form-control" value="{{ old('kabag_teknik_nipp',$row->kabag_teknik_nipp) }}">
                </div>
              </div>
            </div>

            <div class="form-group">
              <label>Catatan</label>
              <textarea name="catatan" class="form-control" rows="3">{{ old('catatan',$row->catatan) }}</textarea>
            </div>

          </div>
          <div class="card-footer text-right">
            <button class="btn btn-primary"><i class="fas fa-save"></i> Simpan Perubahan</button>
          </div>
        </form>
      </div>
    </div>
  </section>
</x-admin-dashboard>
