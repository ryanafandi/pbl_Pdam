<x-admin-dashboard>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card shadow-sm">
                        <div class="card-header bg-primary text-white">
                            <h3 class="card-title">
                                <i class="fas fa-file-alt mr-2"></i>Kelola Data Pengajuan
                            </h3>
                        </div>

                        <div class="card-body">
                            <table id="example1" class="table table-bordered table-striped">
                                <thead class="bg-light text-center">
                                    <tr>
                                        <th>No</th>
                                        <th>Nama</th>
                                        <th>NIK</th>
                                        <th>Email</th>
                                        <th>No Handphone</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>

                                <tbody class="text-center">
                                    @foreach ($pengajuan as $key => $item)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $item->nama }}</td>
                                            <td>{{ $item->nik }}</td>
                                            <td>{{ $item->email }}</td>
                                            <td>{{ $item->no_handphone }}</td>
                                            <!-- STATUS -->
                                            <td>
                                                <form action="{{ route('pengajuan.updateStatus', $item->nik) }}"
                                                    method="POST">
                                                    @csrf
                                                    <select name="status"
                                                        class="form-control form-control-sm text-center"
                                                        onchange="this.form.submit()">
                                                        <option value="Baru"
                                                            {{ $item->status == 'Baru' ? 'selected' : '' }}>Baru
                                                        </option>
                                                        <option value="Diproses"
                                                            {{ $item->status == 'Diproses' ? 'selected' : '' }}>Diproses
                                                        </option>
                                                        <option value="Selesai"
                                                            {{ $item->status == 'Selesai' ? 'selected' : '' }}>Selesai
                                                        </option>
                                                        <option value="Ditolak"
                                                            {{ $item->status == 'Ditolak' ? 'selected' : '' }}>Ditolak
                                                        </option>
                                                    </select>
                                                </form>
                                            </td>
                                            <td>
                                                <div class="d-flex justify-content-center gap-4">
                                                    <a href="{{ url('admin/pengajuan/' . $item->nik) }}"
                                                        class="btn btn-info btn-sm">
                                                        <i class="fas fa-eye"></i> Lihat
                                                    </a>
                                                    <form action="{{ url('admin/pengajuan/' . $item->nik) }}"
                                                        method="POST" class="form-inline"
                                                        onsubmit="return confirm('Yakin ingin menghapus data?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="btn btn-danger btn-sm">
                                                            <i class="fas fa-trash"></i> Hapus
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>

                                        </tr>

                                        <!-- Data 2 -->
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        $(function() {
            $("#example1").DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                "pageLength": 5,
                "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
        });
    </script>
</x-admin-dashboard>
