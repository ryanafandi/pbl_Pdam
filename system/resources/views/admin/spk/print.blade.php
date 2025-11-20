<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak SPK - {{ $row->nomor_spk }}</title>
    <style>
        body { font-family: 'Times New Roman', Times, serif; font-size: 12pt; line-height: 1.5; margin: 0; padding: 0; }
        .container { width: 210mm; margin: auto; padding: 10mm 20mm; background: white; }
        
        /* Kop Surat */
        .header { text-align: center; margin-bottom: 20px; border-bottom: 3px double black; padding-bottom: 10px; }
        .header h2 { margin: 0; font-size: 16pt; font-weight: bold; text-transform: uppercase; }
        .header h3 { margin: 0; font-size: 14pt; font-weight: bold; text-transform: uppercase; }
        .header p { margin: 0; font-size: 11pt; }

        /* Judul */
        .title { text-align: center; margin-bottom: 20px; font-weight: bold; text-decoration: underline; }
        .nomor { text-align: center; margin-top: -15px; margin-bottom: 30px; }

        /* Isi */
        .content { margin-bottom: 20px; text-align: justify; }
        .details-table { width: 100%; margin-left: 20px; margin-bottom: 20px; }
        .details-table td { padding: 3px; vertical-align: top; }
        .details-table td:first-child { width: 180px; }
        .details-table td:nth-child(2) { width: 10px; }

        /* Tanda Tangan */
        .signature-section { margin-top: 40px; display: flex; justify-content: space-between; }
        .signature-box { width: 45%; text-align: center; }
        .signature-box .role { font-weight: bold; margin-bottom: 60px; }
        .signature-box .name { font-weight: bold; text-decoration: underline; }
        .signature-box .nip { font-size: 10pt; }

        /* Laporan Pelaksana (Bagian Bawah) */
        .laporan-section { margin-top: 40px; border-top: 1px dashed black; padding-top: 10px; }
        .laporan-title { font-weight: bold; text-decoration: underline; margin-bottom: 10px; }
        .laporan-table { width: 100%; }
        .laporan-table td { padding: 5px; border-bottom: 1px dotted #ccc; }
        
        @media print {
            @page { size: A4; margin: 10mm; }
            body { -webkit-print-color-adjust: exact; }
            .no-print { display: none; }
        }
    </style>
</head>
<body onload="{{ request()->has('print') ? 'window.print()' : '' }}">

    <div class="no-print" style="position: fixed; top: 10px; right: 10px; background: #eee; padding: 10px; border: 1px solid #999;">
        <button onclick="window.print()" style="padding: 5px 10px; font-weight: bold; cursor: pointer;">🖨️ CETAK SEKARANG</button>
    </div>

    <div class="container">
        <div class="header">
            <h2>PERUMDA AIR MINUM TIRTA PAWAN</h2>
            <h3>KABUPATEN KETAPANG</h3>
            <p>Jalan Brigjend Katamso No. 66 Telp/Fax (0534) 3037192</p>
        </div>

        <div class="title">SURAT PERINTAH KERJA ( SPK )</div>
        <div class="nomor">Nomor : {{ $row->nomor_spk }}</div>

        <div class="content">
            <p>Kepada : Sdr. Kasub. Bag. Distribusi</p>
            <p>Harap segera dilaksanakan : <strong>{{ strtoupper($row->pekerjaan ?? 'PEMASANGAN SAMBUNGAN BARU') }}</strong></p>
        </div>

        <table class="details-table">
            <tr>
                <td>Nama Pelanggan</td>
                <td>:</td>
                <td><strong>{{ $row->nama_pelanggan }}</strong></td>
            </tr>
            <tr>
                <td>Alamat</td>
                <td>:</td>
                <td>{{ $row->alamat }}</td>
            </tr>
            <tr>
                <td>No. Langganan / Kode Wil</td>
                <td>:</td>
                <td>{{ $row->no_pelanggan ?? '-' }}</td>
            </tr>
            <tr>
                <td>Catatan Tambahan</td>
                <td>:</td>
                <td>{{ $row->catatan ?? '-' }}</td>
            </tr>
        </table>

        <div class="content">
            <p>Kemudian setelah selesai pelaksanaan, diminta kepada bagian Distribusi untuk menyampaikan berita acara penyelesaian pelaksanaan tugas kepada Direktur melalui bagian Hubungan Langganan.</p>
        </div>

        <div class="signature-section">
            <div class="signature-box">
                <div class="role">MENGETAHUI,<br>DIREKTUR</div>
                <br>
                <div class="name">L. YUDIHARTO J. SAPTONO, S.T.</div>
            </div>
            <div class="signature-box">
                <div>Ketapang, {{ date('d F Y', strtotime($row->dibuat_at)) }}</div>
                <div class="role">KABAG. TEHNIK</div>
                <br>
                <div class="name">ANDRI FITMAWA, S.T.</div>
                <div class="nip">NIPP. 101 204 05</div>
            </div>
        </div>

        <br><br>

        <div class="laporan-section">
            <div style="font-size: 9pt; margin-bottom: 5px;">File: SPK/EX/SHET.1</div>
            <div class="laporan-title">LAPORAN PELAKSANA</div>
            <table class="laporan-table">
                <tr>
                    <td width="20%">Dilaksanakan Oleh</td>
                    <td width="30%">: .......................................</td>
                    <td width="20%">Tanggal Pelaksana</td>
                    <td width="30%">: .......................................</td>
                </tr>
                <tr>
                    <td>BPP No</td>
                    <td>: .......................................</td>
                    <td>No. Meter</td>
                    <td>: .......................................</td>
                </tr>
                <tr>
                    <td>Merek Meter</td>
                    <td>: .......................................</td>
                    <td>Stand Meter</td>
                    <td>: .......................................</td>
                </tr>
                <tr>
                    <td>Ukuran</td>
                    <td>: .......................................</td>
                    <td>No. HP Pelanggan</td>
                    <td>: {{ $row->rab->spko->pengajuan->nomor_telepon ?? '-' }}</td>
                </tr>
            </table>
        </div>
    </div>

</body>
</html>