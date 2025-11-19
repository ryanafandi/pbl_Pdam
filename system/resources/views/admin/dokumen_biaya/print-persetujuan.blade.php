{{-- resources/views/admin/dokumen_biaya/print-persetujuan.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Bukti Persetujuan - {{ $row->persetujuan_nomor ?? $row->nomor_rab }}</title>
  <style>
    * { box-sizing: border-box; }
    body {
      font-family: Arial, sans-serif;
      font-size: 11px;
      margin: 20px 40px;
      line-height: 1.5;
    }

    .text-center { text-align: center; }
    .text-right  { text-align: right; }
    .text-left   { text-align: left; }
    .fw-bold     { font-weight: bold; }
    .mt-1 { margin-top: 4px; }
    .mt-2 { margin-top: 8px; }
    .mt-3 { margin-top: 12px; }
    .mt-4 { margin-top: 16px; }
    .mb-1 { margin-bottom: 4px; }
    .mb-2 { margin-bottom: 8px; }

    table { width: 100%; border-collapse: collapse; }
    table.no-border td { border: none !important; padding: 2px 0; }

    hr {
      border: 0;
      border-top: 1px solid #000;
      margin: 6px 0 10px;
    }

    @media print {
      @page {
        size: A4;
        margin: 15mm 20mm;
      }
      body { margin: 0; }
      .no-print { display: none; }
    }
  </style>
</head>
<body>

  {{-- Tombol print saat dilihat di browser --}}
  <div class="no-print" style="text-align:right; margin-bottom:10px;">
    <button onclick="window.print()">🖨 Cetak</button>
  </div>

  {{-- HEADER KOP SURAT --}}
  <div class="text-center">
    <div class="fw-bold" style="font-size:14px;">
      PERUMDA AIR MINUM TIRTA PAWAN
    </div>
    <div style="font-size:11px;">
      KABUPATEN KETAPANG
    </div>
    <div style="font-size:10px;">
      Alamat: .............................................................................
    </div>
  </div>

  <hr>

  {{-- JUDUL --}}
  <div class="text-center fw-bold" style="margin-top:6px; font-size:13px; text-transform:uppercase;">
    BUKTI PERSETUJUAN BIAYA PENYAMBUNGAN INSTANSI
  </div>

  <div class="text-center" style="font-size:11px; margin-top:2px;">
    Nomor: {{ $row->persetujuan_nomor ?? '................................' }}
  </div>

  @php
    $nama    = $row->nama_pelanggan ?? ($row->spko->pengajuan->pemohon_nama ?? '................................');
    $alamat  = $row->alamat ?? ($row->spko->pengajuan->alamat_pemasangan ?? '................................');
    $total   = $row->total ?? 0;
    $noSpko  = $row->spko->nomor_spko ?? '........................';
    $noRab   = $row->nomor_rab ?? '........................';
    $noDaftar = $row->spko->pengajuan->no_pendaftaran ?? '........................';

    // Jatuh tempo: kalau ada, pakai; kalau tidak, coba pakai jatuh_tempo atau +7 hari
    $jatuhTempo = $row->jatuh_tempo
      ? $row->jatuh_tempo
      : ($row->persetujuan_tanggal ? $row->persetujuan_tanggal->copy()->addDays(7) : null);

    $tglSurat = $row->persetujuan_tanggal ?? now();
  @endphp

  {{-- ISI SURAT --}}
  <div class="mt-4" style="font-size:11px; text-align:justify;">
    Berdasarkan hasil penelitian kami atas persil Saudara, sesuai dengan
    SPKO Nomor: {{ $noSpko }}, RAB Nomor: {{ $noRab }}, pembiayaan pemasangan
    sambungan baru adalah sebesar
    <strong>Rp {{ number_format($total, 0, ',', '.') }}</strong>,
    terbilang:
    <u>( {{ strtoupper($row->total_terbilang) }} )</u>.
  </div>

  <div class="mt-2" style="font-size:11px; text-align:justify;">
    Apabila Saudara menyetujui besarnya biaya tersebut, sebagai bukti diharapkan
    membubuhkan tanda tangan di bawah ini dan menyerahkan kembali kepada bagian
    hubungan langganan PERUMDA AIR MINUM TIRTA PAWAN Kabupaten Ketapang dalam
    jangka waktu tujuh (7) hari,
    @if($jatuhTempo)
      selambat-lambatnya tanggal
      <strong>{{ $jatuhTempo->translatedFormat('d F Y') }}</strong>.
    @else
      selambat-lambatnya dalam waktu tujuh (7) hari sejak tanggal surat ini.
    @endif
  </div>

  {{-- DATA PELANGGAN --}}
  <div class="mt-3" style="font-size:11px;">
    <table class="no-border">
      <tr>
        <td style="width:24%;">Nama Pelanggan</td>
        <td style="width:3%;">:</td>
        <td>{{ $nama }}</td>
      </tr>
      <tr>
        <td>Alamat</td>
        <td>:</td>
        <td>{{ $alamat }}</td>
      </tr>
      <tr>
        <td>No. Pendaftaran</td>
        <td>:</td>
        <td>{{ $noDaftar }}</td>
      </tr>
    </table>
  </div>

  {{-- TANDA TANGAN --}}
  <div class="mt-4">
    <table class="no-border" style="width:100%; font-size:11px;">
      <tr>
        <td class="text-center" style="width:50%; vertical-align:top;">
          Menyetujui,<br>
          Pelanggan<br><br><br><br>
          ( {{ $nama }} )
        </td>
        <td class="text-center" style="width:50%; vertical-align:top;">
          KETAPANG, {{ $tglSurat->translatedFormat('d F Y') }}<br>
          PERUMDA AIR MINUM TIRTA PAWAN<br>
          KABUPATEN KETAPANG<br><br><br>
          (____________________________)<br>
          DIREKTUR
        </td>
      </tr>
    </table>
  </div>

</body>
</html>
