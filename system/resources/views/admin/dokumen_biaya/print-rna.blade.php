{{-- resources/views/admin/dokumen_biaya/print-rna.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>RNA - {{ $row->rna_nomor ?? $row->nomor_rab }}</title>
  <style>
    * { box-sizing: border-box; }
    body {
      font-family: Arial, sans-serif;
      font-size: 11px;
      margin: 20px 40px;
      line-height: 1.4;
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
    .mb-3 { margin-bottom: 12px; }

    table { width: 100%; border-collapse: collapse; }
    table.bordered th, table.bordered td {
      border: 1px solid #000;
      padding: 4px 6px;
    }
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
  <table class="no-border">
    <tr>
      <td style="width:15%; vertical-align:top;">
        {{-- Kalau ada logo:
        <img src="{{ asset('path/logo.png') }}" style="max-width:60px;">
        --}}
      </td>
      <td class="text-center">
        <div class="fw-bold" style="font-size:14px;">
          PERUMDA AIR MINUM TIRTA PAWAN
        </div>
        <div style="font-size:11px;">
          KABUPATEN KETAPANG
        </div>
        <div style="font-size:10px;">
          Alamat: .............................................................................
        </div>
      </td>
      <td style="width:15%;"></td>
    </tr>
  </table>

  <hr>

  {{-- JUDUL DOKUMEN --}}
  <div class="text-center fw-bold" style="margin-top:6px; font-size:13px;">
    RENING NON AIR (RNA)
  </div>
  <div class="text-center" style="font-size:11px; margin-top:2px;">
    Nomor: {{ $row->rna_nomor ?? '-' }}
  </div>

  {{-- IDENTITAS PELANGGAN --}}
  @php
    $pelangganNama   = $row->nama_pelanggan ?? ($row->spko->pengajuan->pemohon_nama ?? '-');
    $pelangganAlamat = $row->alamat ?? ($row->spko->pengajuan->alamat_pemasangan ?? '-');
    $noDaftar        = $row->spko->pengajuan->no_pendaftaran ?? '-';
    $noSpko          = $row->spko->nomor_spko ?? '-';
    $noRab           = $row->nomor_rab ?? '-';
  @endphp

  <table class="no-border" style="margin-top:18px; font-size:11px;">
    <tr>
      <td style="width:24%;">Nama Pelanggan</td>
      <td style="width:3%;">:</td>
      <td>{{ $pelangganNama }}</td>
    </tr>
    <tr>
      <td>Alamat</td>
      <td>:</td>
      <td>{{ $pelangganAlamat }}</td>
    </tr>
    <tr>
      <td>No. Pendaftaran</td>
      <td>:</td>
      <td>{{ $noDaftar }}</td>
    </tr>
    <tr>
      <td>No. SPKO</td>
      <td>:</td>
      <td>{{ $noSpko }}</td>
    </tr>
    <tr>
      <td>No. RAB</td>
      <td>:</td>
      <td>{{ $noRab }}</td>
    </tr>
    <tr>
      <td>Tanggal RNA</td>
      <td>:</td>
      <td>{{ optional($row->rna_tanggal)->format('d-m-Y') ?? '-' }}</td>
    </tr>
  </table>

  {{-- RINCIAN BIAYA --}}
  <div class="mt-3 fw-bold">Rincian Biaya</div>
  <table class="bordered" style="margin-top:4px; font-size:10px;">
    <thead>
      <tr>
        <th style="width:5%;">No</th>
        <th>Uraian Pekerjaan / Material</th>
        <th style="width:12%;">Kategori</th>
        <th style="width:10%;">Satuan</th>
        <th style="width:10%;">Volume</th>
        <th style="width:15%;">Harga Satuan (Rp)</th>
        <th style="width:15%;">Jumlah (Rp)</th>
      </tr>
    </thead>
    <tbody>
      @php $no = 1; @endphp
      @foreach($row->details as $d)
        <tr>
          <td class="text-center">{{ $no++ }}</td>
          <td>{{ $d->uraian }}</td>
          <td class="text-center">
            {{ $d->kategori === 'pipa_dinas' ? 'Pipa Dinas' : 'Pipa Persil' }}
          </td>
          <td class="text-center">{{ $d->satuan }}</td>
          <td class="text-right">{{ number_format($d->volume, 2, ',', '.') }}</td>
          <td class="text-right">{{ number_format($d->harga_satuan, 0, ',', '.') }}</td>
          <td class="text-right">{{ number_format($d->jumlah, 0, ',', '.') }}</td>
        </tr>
      @endforeach

      <tr>
        <td colspan="6" class="text-right fw-bold">Subtotal Pipa Dinas</td>
        <td class="text-right fw-bold">
          Rp {{ number_format($row->subtotal_pipa_dinas ?? 0, 0, ',', '.') }}
        </td>
      </tr>
      <tr>
        <td colspan="6" class="text-right fw-bold">Subtotal Pipa Persil</td>
        <td class="text-right fw-bold">
          Rp {{ number_format($row->subtotal_pipa_persil ?? 0, 0, ',', '.') }}
        </td>
      </tr>
      <tr>
        <td colspan="6" class="text-right">Biaya Pendaftaran</td>
        <td class="text-right">
          Rp {{ number_format($row->biaya_pendaftaran ?? 0, 0, ',', '.') }}
        </td>
      </tr>
      <tr>
        <td colspan="6" class="text-right">Biaya Admin</td>
        <td class="text-right">
          Rp {{ number_format($row->biaya_admin ?? 0, 0, ',', '.') }}
        </td>
      </tr>
      <tr>
        <td colspan="6" class="text-right fw-bold">TOTAL</td>
        <td class="text-right fw-bold">
          Rp {{ number_format($row->total ?? 0, 0, ',', '.') }}
        </td>
      </tr>
      <tr>
        <td colspan="7" class="fw-bold">
          Terbilang: {{ ucfirst($row->total_terbilang) }}
        </td>
      </tr>
    </tbody>
  </table>

  {{-- CATATAN --}}
  <div class="mt-3" style="font-size:10px;">
    Catatan:
    <br>
    {{ $row->billing_note ?? '-' }}
  </div>

  {{-- TANDA TANGAN --}}
  <table class="no-border" style="margin-top:40px; font-size:11px;">
    <tr>
      <td class="text-center" style="width:50%;">
        Mengetahui,<br>
        Bagian Perencanaan<br><br><br><br>
        (__________________________)
      </td>
      <td class="text-center" style="width:50%;">
        Ketapang, {{ now()->format('d-m-Y') }}<br>
        PERUMDA AIR MINUM TIRTA PAWAN<br>
        Kabupaten Ketapang<br><br><br>
        (__________________________)<br>
        Direktur
      </td>
    </tr>
  </table>

</body>
</html>
