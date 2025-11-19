<x-perencanaan>
  <div class="content-header d-flex align-items-center justify-content-between mb-3">
    <div>
      <h1 class="m-0 text-primary fw-bold">
        <i class="fas fa-file-invoice-dollar"></i> Susun / Edit RAB
      </h1>
      <small class="text-muted">
        SPKO: <span class="text-monospace">{{ $row->nomor_spko }}</span>
      </small>
    </div>
    <a href="{{ url('perencanaan/rab') }}" class="btn btn-secondary">
      <i class="fas fa-arrow-left"></i> Kembali
    </a>
  </div>

  <section class="content">
    @if ($errors->any())
      <div class="alert alert-danger">
        <strong>Periksa lagi:</strong>
        <ul class="mb-0">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
    @if(session('error'))   <div class="alert alert-danger">{{ session('error') }}</div>   @endif

    <form action="{{ url('perencanaan/rab/'.$row->id) }}" method="POST">
      @csrf
      @method('PUT')

      @include('perencanaan.rab._form')
    </form>
  </section>
</x-perencanaan>
