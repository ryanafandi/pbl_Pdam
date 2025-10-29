@php
    use Illuminate\Support\Str;
@endphp

<x-admin-dashboard>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-2">
                <i class="fas fa-file-alt me-2"></i> Detail Pengajuan {{ $pengajuan->nama }}
            </h5>

            <!-- Status -->
            <div>
                <span
                    class="badge 
                    @if ($pengajuan->status == 'Baru') bg-secondary
                    @elseif($pengajuan->status == 'Diproses') bg-warning text-dark
                    @elseif($pengajuan->status == 'Selesai') bg-success
                    @elseif($pengajuan->status == 'Ditolak') bg-danger
                    @else bg-light text-dark @endif
                    px-3 py-2 rounded-pill shadow-sm"
                    style="font-size: 0.9rem;">
                    {{ strtoupper($pengajuan->status ?? 'BELUM DITENTUKAN') }}
                </span>
            </div>
        </div>

        <div class="card-body">
            <!-- DATA PRIBADI -->
            <div class="mb-4">
                <h6 class="text-primary mb-3">
                    <i class="fas fa-user me-2"></i> Data Pribadi
                </h6>

                <div class="row mb-2">
                    <div class="col-md-6">
                        <strong>NIK:</strong><br>
                        <span class="text-muted">{{ $pengajuan->nik }}</span>
                    </div>
                    <div class="col-md-6">
                        <strong>Nama Lengkap:</strong><br>
                        <span class="text-muted">{{ $pengajuan->nama }}</span>
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-md-6">
                        <strong>Email:</strong><br>
                        <span class="text-muted">{{ $pengajuan->email }}</span>
                    </div>
                    <div class="col-md-6">
                        <strong>No Handphone:</strong><br>
                        <span class="text-muted">{{ $pengajuan->no_handphone }}</span>
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-md-12">
                        <strong>Alamat:</strong><br>
                        <span class="text-muted">{{ $pengajuan->alamat }}</span>
                    </div>
                </div>
            </div>

            <hr>

            <!-- FILE BAGIAN -->
            <div class="mb-3">
                <h6 class="text-primary mb-3">
                    <i class="fas fa-file-upload me-2"></i> Berkas Pengajuan
                </h6>

                <div class="row g-4">
                    <!-- KTP -->
                    <div class="col-md-6">
                        <div class="border rounded p-3 shadow-sm">
                            <strong>KTP:</strong><br>
                            @if ($pengajuan->ktp)
                                @if (Str::endsWith($pengajuan->ktp, '.pdf'))
                                    <iframe src="{{ asset($pengajuan->ktp) }}" width="100%" height="400px"
                                        class="rounded"></iframe>
                                @else
                                    <img src="{{ asset($pengajuan->ktp) }}" class="img-fluid rounded shadow-sm mt-2"
                                        alt="KTP">
                                @endif
                            @else
                                <span class="text-muted">Tidak ada file</span>
                            @endif
                        </div>
                    </div>

                    <!-- KK -->
                    <div class="col-md-6">
                        <div class="border rounded p-3 shadow-sm">
                            <strong>KK:</strong><br>
                            @if ($pengajuan->kk)
                                @if (Str::endsWith($pengajuan->kk, '.pdf'))
                                    <iframe src="{{ asset($pengajuan->kk) }}" width="100%" height="400px"
                                        class="rounded"></iframe>
                                @else
                                    <img src="{{ asset($pengajuan->kk) }}" class="img-fluid rounded shadow-sm mt-2"
                                        alt="KK">
                                @endif
                            @else
                                <span class="text-muted">Tidak ada file</span>
                            @endif
                        </div>
                    </div>

                    <!-- Surat Permohonan -->
                    <div class="col-md-6">
                        <div class="border rounded p-3 shadow-sm">
                            <strong>Surat Permohonan:</strong><br>
                            @if ($pengajuan->surat_permohonan)
                                @if (Str::endsWith($pengajuan->surat_permohonan, '.pdf'))
                                    <iframe src="{{ asset($pengajuan->surat_permohonan) }}" width="100%"
                                        height="400px" class="rounded"></iframe>
                                @else
                                    <img src="{{ asset($pengajuan->surat_permohonan) }}"
                                        class="img-fluid rounded shadow-sm mt-2" alt="Surat Permohonan">
                                @endif
                            @else
                                <span class="text-muted">Tidak ada file</span>
                            @endif
                        </div>
                    </div>

                    <!-- Foto Rumah -->
                    <div class="col-md-6">
                        <div class="border rounded p-3 shadow-sm">
                            <strong>Foto Rumah:</strong><br>
                            @if ($pengajuan->foto_rumah)
                                <img src="{{ url("public/$pengajuan->foto_rumah") }}"
                                    class="img-fluid rounded shadow-sm mt-2" alt="Foto Rumah">
                            @else
                                <span class="text-muted">Tidak ada file</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

</x-admin-dashboard>
