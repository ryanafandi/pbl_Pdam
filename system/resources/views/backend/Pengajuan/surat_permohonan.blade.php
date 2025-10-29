<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat Permohonan Pemasangan Baru - PDAM Tirta Pawan</title>
    <style>
        body {
            font-family: "Times New Roman", Times, serif;
            margin: 3cm 2.5cm;
            line-height: 1.6;
            color: #000;
        }
        .kop-surat {
            text-align: center;
            border-bottom: 3px solid #000;
            padding-bottom: 10px;
            margin-bottom: 30px;
        }
        .kop-surat img {
            float: left;
            width: 80px;
            height: 80px;
            margin-right: 15px;
        }
        .kop-surat h2 {
            margin: 0;
            font-size: 20px;
            text-transform: uppercase;
        }
        .kop-surat p {
            margin: 0;
            font-size: 13px;
        }
        .judul {
            text-align: center;
            font-weight: bold;
            text-decoration: underline;
            margin-bottom: 20px;
        }
        .isi {
            text-align: justify;
        }
        .tanda-tangan {
            margin-top: 50px;
            width: 100%;
            display: flex;
            justify-content: flex-end;
        }
        .tanda-tangan div {
            text-align: center;
        }
        @media print {
            .no-print { display: none; }
        }
    </style>
</head>
<body>

    <!-- Kop Surat -->
    <div class="kop-surat">
        <img src="{{ url('public/images.jpg') }}" alt="Logo PDAM">
        <div>
            <h2>PERUSAHAAN UMUM DAERAH AIR MINUM (PERUMDAM)</h2>
            <h2>“TIRTA PAWAN” KABUPATEN KETAPANG</h2>
            <p>Jl. S. Parman No. 45 Ketapang, Kalimantan Barat 78813</p>
            <p>Telp. (0534) 322585 | Email: tirta.pawan@pdam.co.id</p>
        </div>
    </div>

    <!-- Judul Surat -->
    <div class="judul">
        SURAT PERMOHONAN PEMASANGAN SAMBUNGAN BARU
    </div>

    <!-- Isi Surat -->
    <div class="isi">
        <p>Kepada Yth,</p>
        <p>Direktur PDAM Tirta Pawan</p>
        <p>Di Tempat</p>

        <br>

        <p>Dengan hormat,</p>
        <p>Saya yang bertanda tangan di bawah ini:</p>

        <table style="margin-left:20px;">
            <tr>
                <td width="160">Nama Lengkap</td>
                <td width="10">:</td>
                <td>..................................................</td>
            </tr>
            <tr>
                <td>Alamat</td>
                <td>:</td>
                <td>..................................................</td>
            </tr>
            <tr>
                <td>No. Telepon / HP</td>
                <td>:</td>
                <td>..................................................</td>
            </tr>
            <tr>
                <td>No. KTP</td>
                <td>:</td>
                <td>..................................................</td>
            </tr>
        </table>

        <br>

        <p>
            Dengan ini mengajukan permohonan untuk pemasangan sambungan air minum baru di alamat tersebut di atas.
            Saya bersedia memenuhi segala ketentuan dan persyaratan yang berlaku di PDAM Tirta Pawan.
        </p>

        <p>Demikian surat permohonan ini saya buat dengan sebenar-benarnya. Atas perhatian dan kerja samanya, saya ucapkan terima kasih.</p>
    </div>

    <!-- Tanda Tangan -->
    <div class="tanda-tangan">
        <div>
            <p>Ketapang, .............. 20......</p>
            <p>Hormat Saya,</p>
            <br><br><br>
            <p>(...........................................)</p>
        </div>
    </div>

    <!-- Tombol Print -->
    <div class="no-print" style="margin-top:40px; text-align:center;">
        <button onclick="window.print()" style="
            padding:10px 20px;
            font-size:16px;
            background:#007bff;
            color:#fff;
            border:none;
            border-radius:5px;
            cursor:pointer;">
            Cetak Surat
        </button>
    </div>

</body>
</html>
