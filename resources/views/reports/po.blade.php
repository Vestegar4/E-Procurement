<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat Perintah Kerja - Purchase Order</title>
    <style>
        /* Gaya font standar untuk dokumen resmi/yuridis */
        body { 
            font-family: 'Times New Roman', Times, serif; 
            margin: 15px; 
            color: #333;
            line-height: 1.4;
        }
        
        /* Desain Kop Surat */
        .kop-surat { 
            text-align: center; 
            border-bottom: 3px double #000; 
            padding-bottom: 10px; 
            margin-bottom: 25px; 
        }
        .kop-surat h2 { margin: 0; font-size: 20px; text-transform: uppercase; letter-spacing: 1px; }
        .kop-surat h1 { margin: 5px 0; font-size: 24px; text-transform: uppercase; color: #111; }
        .kop-surat p { margin: 0; font-size: 12px; font-style: italic; color: #555; }
        
        /* Judul Dokumen */
        .judul-dokumen {
            text-align: center;
            margin-bottom: 30px;
        }
        .judul-dokumen h3 { 
            margin: 0; 
            font-size: 16px; 
            text-decoration: underline; 
            text-transform: uppercase;
            font-weight: bold;
        }
        .judul-dokumen p { margin: 5px 0 0 0; font-size: 13px; }

        /* Paragraf Pembuka dan Penutup */
        .text-justified {
            text-align: justify;
            font-size: 14px;
            margin-bottom: 15px;
        }

        /* Desain Tabel Informasi */
        .section-title {
            font-size: 14px;
            font-weight: bold;
            text-transform: uppercase;
            background-color: #f2f2f2;
            padding: 5px;
            margin-top: 20px;
            margin-bottom: 10px;
            border-left: 4px solid #333;
        }
        
        .table-data { 
            width: 100%; 
            margin-bottom: 20px; 
            border-collapse: collapse;
            font-size: 14px;
        }
        .table-data td { 
            padding: 6px 4px; 
            vertical-align: top; 
        }

        /* Aturan Cetak Halaman & Tanda Tangan */
        .container-signature {
            margin-top: 50px;
            width: 100%;
            font-size: 14px;
        }
        .signature-box {
            width: 45%;
            float: left;
            text-align: center;
        }
        .signature-box.right {
            float: right;
        }
        .space-ttd {
            height: 70px;
        }
    </style>
</head>
<body>

    <div class="kop-surat">
        <h2>PANITIA PENGADAAN BARANG DAN JASA</h2>
        <h1>PORTAL E-PROCUREMENT SYSTEM</h1>
        <p>Gedung Pusat Administrasi, Lantai 3, Jl. Protokol No. 12, Jakarta, Telp: (021) 555-1234</p>
    </div>

    <div class="judul-dokumen">
        <h3>{{ $title }}</h3>
        <p>Nomor: {{ $po->po_number }}</p>
    </div>

    <p class="text-justified">
        Berdasarkan hasil evaluasi dokumen penawaran, teknis, dan harga pada sistem pelelangan elektronik, dengan ini Panitia Pengadaan menetapkan kesepakatan kerja dan memberikan perintah pengadaan kepada pihak penyedia barang/jasa berikut:
    </p>

    <div class="section-title">I. Identitas Penyedia Barang / Jasa (Vendor)</div>
    <table class="table-data">
        <tr>
            <td width="30%"><strong>Nama Perusahaan</strong></td>
            <td width="3%">:</td>
            <td>{{ $po->vendor->company_name ?? 'Nama Vendor Tidak Ditemukan' }}</td>
        </tr>
        <tr>
            <td><strong>Nama Pemilik / Direktur</strong></td>
            <td>:</td>
            <td>{{ $po->vendor->owner_name ?? '-' }}</td>
        </tr>
        <tr>
            <td><strong>Alamat Perusahaan</strong></td>
            <td>:</td>
            <td>{{ $po->vendor->address ?? '-' }}</td>
        </tr>
        <tr>
            <td><strong>Kontak / Email</strong></td>
            <td>:</td>
            <td>{{ $po->vendor->user->email ?? '-' }}</td>
        </tr>
    </table>

    <div class="section-title">II. Informasi Paket Pekerjaan</div>
    <table class="table-data">
        <tr>
            <td width="30%"><strong>ID Kode Tender</strong></td>
            <td width="3%">:</td>
            <td>{{ $po->tender->id }}</td>
        </tr>
        <tr>
            <td><strong>Nama Paket Pekerjaan</strong></td>
            <td>:</td>
            <td>{{ $po->tender->title }}</td>
        </tr>
        <tr>
            <td><strong>Nilai Pagu Anggaran</strong></td>
            <td>:</td>
            <td>Rp {{ number_format($po->total_amount, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td><strong>Status Dokumen</strong></td>
            <td>:</td>
            <td><strong>{{ strtoupper($po->status) }}</strong></td>
        </tr>
    </table>

    <div class="section-title">III. Syarat dan Ketentuan</div>
    <p class="text-justified" style="font-size: 12px; color: #555;">
        1. Penyedia barang/jasa wajib menyelesaikan pekerjaan atau mengirimkan barang sesuai spesifikasi teknis yang tertera pada dokumen tender.<br>
        2. Surat Perintah Kerja (PO) ini diterbitkan secara otomatis oleh sistem e-procurement setelah penetapan pemenang yang sah dan berkekuatan hukum tetap.<br>
        3. Segala bentuk keterlambatan pemenuhan komitmen akan dikenakan sanksi atau denda sesuai aturan pengadaan yang berlaku.
    </p>

    <div class="container-signature">
        <div class="signature-box">
            <p>Menerima Perintah Kerja,</p>
            <p><strong>Perwakilan Vendor</strong></p>
            <div class="space-ttd"></div>
            <p><u>( ________________________ )</u></p>
            <p>Direktur / Penanggung Jawab</p>
        </div>
        
        <div class="signature-box right">
            <p>Jakarta, {{ $date }}</p>
            <p><strong>Panitia Pengadaan (Admin)</strong></p>
            <div class="space-ttd"></div>
            <p><u>( Sistem E-Procurement )</u></p>
            <p>NIP. 19950821 202605 1 002</p>
        </div>
    </div>

</body>
</html>