<x-direktur>
<form action="{{ url('direktur/approval/pendaftaran/'.$row->id) }}" method="POST">
  @csrf
  @method('PUT')

  <div class="card">
    <div class="card-header d-flex justify-content-between">
      <h5>Edit Catatan Direktur</h5>
      <a href="{{ url('direktur/approval/pendaftaran/'.$row->id) }}" class="btn btn-sm btn-light">Kembali</a>
    </div>
    <div class="card-body">
      @if($errors->any())
        <div class="alert alert-danger">
          <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
      @endif

      <div class="form-group">
        <label>Catatan Direktur</label>
        <textarea name="catatan_direktur" class="form-control" rows="5">{{ old('catatan_direktur', $row->catatan_direktur) }}</textarea>
      </div>
    </div>
    <div class="card-footer text-right">
      <button class="btn btn-primary">Simpan</button>
    </div>
  </div>
</form>
</x-direktur>
