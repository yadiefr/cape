<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bukti Pendaftaran - <?php echo e($pendaftaran->nomor_pendaftaran); ?></title>
    <style>
        @page {
            size: A4;
            margin: 15mm;
        }
        body {
            font-family: Arial, sans-serif;
            line-height: 1.5;
            font-size: 12pt;
            color: #333;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 3px solid #000;
            padding-bottom: 10px;
        }
        .header h1 {
            font-size: 18pt;
            font-weight: bold;
            margin: 5px 0;
        }
        .header p {
            margin: 5px 0;
            font-size: 10pt;
        }
        .title {
            text-align: center;
            font-weight: bold;
            font-size: 14pt;
            margin: 20px 0;
            text-decoration: underline;
        }
        .reg-number {
            text-align: center;
            margin: 20px 0;
            padding: 10px;
            border: 1px dashed #333;
            font-size: 14pt;
            font-weight: bold;
        }
        .info-box {
            margin-bottom: 20px;
        }
        .info-box h2 {
            font-size: 12pt;
            margin: 0 0 10px 0;
            border-bottom: 1px solid #333;
            padding-bottom: 5px;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
        }
        .info-table td {
            padding: 5px;
            vertical-align: top;
        }
        .info-table td:first-child {
            width: 180px;
        }
        .info-table td:nth-child(2) {
            width: 20px;
            text-align: center;
        }
        .footer {
            margin-top: 30px;
        }
        .signature {
            float: right;
            width: 40%;
            text-align: center;
        }
        .signature p {
            margin: 50px 0 0;
        }
        .clearfix::after {
            content: "";
            clear: both;
            display: table;
        }
        .status {
            text-align: center;
            margin: 20px 0;
            padding: 5px;
            font-weight: bold;
        }
        .status.pending {
            border: 2px solid #ffc107;
            color: #856404;
            background-color: #fff3cd;
        }
        .status.accepted {
            border: 2px solid #28a745;
            color: #155724;
            background-color: #d4edda;
        }
        .status.rejected {
            border: 2px solid #dc3545;
            color: #721c24;
            background-color: #f8d7da;
        }
        .status.waiting {
            border: 2px solid #17a2b8;
            color: #0c5460;
            background-color: #d1ecf1;
        }
        @media print {
            body {
                font-size: 12pt;
            }
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>SMK PGRI CIKAMPEK</h1>
            <p>Jalan Pendidikan No. 123, Cikampek, Karawang, Jawa Barat</p>
            <p>Telp: (0264) 123456 | Email: info@smkpgricikampek.sch.id</p>
        </div>
        
        <div class="title">BUKTI PENDAFTARAN PESERTA DIDIK BARU</div>
        <div class="subtitle" style="text-align: center; margin-top: -15px;">Tahun Ajaran <?php echo e($pendaftaran->tahun_ajaran); ?></div>
        
        <div class="reg-number"><?php echo e($pendaftaran->nomor_pendaftaran); ?></div>
        
        <div class="status <?php echo e($pendaftaran->status == 'menunggu' ? 'pending' : 
            ($pendaftaran->status == 'diterima' ? 'accepted' : 
            ($pendaftaran->status == 'ditolak' ? 'rejected' : 'waiting'))); ?>">
            Status: <?php echo e($pendaftaran->status == 'menunggu' ? 'MENUNGGU VERIFIKASI' : 
                ($pendaftaran->status == 'diterima' ? 'DITERIMA' : 
                ($pendaftaran->status == 'ditolak' ? 'TIDAK DITERIMA' : 'CADANGAN'))); ?>

        </div>
        
        <div class="info-box">
            <h2>A. INFORMASI PENDAFTARAN</h2>
            <table class="info-table">
                <tr>
                    <td>Tanggal Pendaftaran</td>
                    <td>:</td>
                    <td><?php echo e($pendaftaran->tanggal_pendaftaran->format('d F Y')); ?></td>
                </tr>
                <tr>
                    <td>Pilihan Jurusan 1</td>
                    <td>:</td>
                    <td><?php echo e($pendaftaran->jurusanPertama->nama); ?></td>
                </tr>
                
            </table>
        </div>
        
        <div class="info-box">
            <h2>B. DATA PRIBADI</h2>
            <table class="info-table">
                <tr>
                    <td>Nama Lengkap</td>
                    <td>:</td>
                    <td><?php echo e($pendaftaran->nama_lengkap); ?></td>
                </tr>
                <tr>
                    <td>NISN</td>
                    <td>:</td>
                    <td><?php echo e($pendaftaran->nisn); ?></td>
                </tr>
                <tr>
                    <td>Jenis Kelamin</td>
                    <td>:</td>
                    <td><?php echo e($pendaftaran->jenis_kelamin); ?></td>
                </tr>
                <tr>
                    <td>Tempat, Tanggal Lahir</td>
                    <td>:</td>
                    <td><?php echo e($pendaftaran->tempat_lahir); ?>, <?php echo e($pendaftaran->tanggal_lahir->format('d F Y')); ?></td>
                </tr>
                <tr>
                    <td>Agama</td>
                    <td>:</td>
                    <td><?php echo e($pendaftaran->agama); ?></td>
                </tr>
                <tr>
                    <td>Alamat</td>
                    <td>:</td>
                    <td><?php echo e($pendaftaran->alamat); ?></td>
                </tr>
                <tr>
                    <td>Telepon</td>
                    <td>:</td>
                    <td><?php echo e($pendaftaran->telepon); ?></td>
                </tr>
                <tr>
                    <td>Asal Sekolah</td>
                    <td>:</td>
                    <td><?php echo e($pendaftaran->asal_sekolah); ?></td>
                </tr>
            </table>
        </div>
        
        <div class="info-box">
            <h2>C. DATA ORANG TUA</h2>
            <table class="info-table">
                <tr>
                    <td>Nama Ayah</td>
                    <td>:</td>
                    <td><?php echo e($pendaftaran->nama_ayah); ?></td>
                </tr>
                <tr>
                    <td>Nama Ibu</td>
                    <td>:</td>
                    <td><?php echo e($pendaftaran->nama_ibu); ?></td>
                </tr>
                <tr>
                    <td>Pekerjaan Ayah</td>
                    <td>:</td>
                    <td><?php echo e($pendaftaran->pekerjaan_ayah ?? '-'); ?></td>
                </tr>
                <tr>
                    <td>Pekerjaan Ibu</td>
                    <td>:</td>
                    <td><?php echo e($pendaftaran->pekerjaan_ibu ?? '-'); ?></td>
                </tr>
                <tr>
                    <td>Telepon Orang Tua</td>
                    <td>:</td>
                    <td><?php echo e($pendaftaran->telepon_orangtua ?? '-'); ?></td>
                </tr>
                <?php if($pendaftaran->alamat_orangtua): ?>
                <tr>
                    <td>Alamat Orang Tua</td>
                    <td>:</td>
                    <td><?php echo e($pendaftaran->alamat_orangtua); ?></td>
                </tr>
                <?php endif; ?>
            </table>
        </div>
        
        <?php if($pendaftaran->nilai_matematika || $pendaftaran->nilai_indonesia || $pendaftaran->nilai_inggris): ?>
        <div class="info-box">
            <h2>D. NILAI UJIAN</h2>
            <table class="info-table">
                <?php if($pendaftaran->nilai_matematika): ?>
                <tr>
                    <td>Matematika</td>
                    <td>:</td>
                    <td><?php echo e($pendaftaran->nilai_matematika); ?></td>
                </tr>
                <?php endif; ?>
                <?php if($pendaftaran->nilai_indonesia): ?>
                <tr>
                    <td>Bahasa Indonesia</td>
                    <td>:</td>
                    <td><?php echo e($pendaftaran->nilai_indonesia); ?></td>
                </tr>
                <?php endif; ?>
                <?php if($pendaftaran->nilai_inggris): ?>
                <tr>
                    <td>Bahasa Inggris</td>
                    <td>:</td>
                    <td><?php echo e($pendaftaran->nilai_inggris); ?></td>
                </tr>
                <?php endif; ?>
            </table>
        </div>
        <?php endif; ?>
        
        <?php if($pendaftaran->keterangan): ?>
        <div class="info-box">
            <h2>E. CATATAN</h2>
            <p><?php echo e($pendaftaran->keterangan); ?></p>
        </div>
        <?php endif; ?>
        
        <div class="footer clearfix">
            <div class="signature">
                <p>Cikampek, <?php echo e(date('d F Y')); ?></p>
                <p style="margin-top: 70px; font-weight: bold; text-decoration: underline;"><?php echo e($pendaftaran->nama_lengkap); ?></p>
                <p style="margin-top: 0;">Calon Siswa</p>
            </div>
        </div>
        
        <div style="margin-top: 50px; border-top: 1px dashed #000; padding-top: 10px; font-size: 10pt; text-align: center;">
            <p><strong>Catatan:</strong> Bukti pendaftaran ini WAJIB dibawa pada saat verifikasi berkas dan daftar ulang.</p>
            <p>Silahkan cek status pendaftaran secara berkala melalui website kami di www.smkpgricikampek.sch.id/ppdb</p>
        </div>
        
        <div class="no-print" style="margin-top: 20px; text-align: center;">
            <button onclick="window.print()" style="padding: 10px 20px; background-color: #4e73df; color: white; border: none; border-radius: 4px; cursor: pointer;">
                Cetak Bukti Pendaftaran
            </button>
        </div>
    </div>
</body>
</html>
<?php /**PATH C:\wamp64\www\website-smk3\resources\views\ppdb\print.blade.php ENDPATH**/ ?>