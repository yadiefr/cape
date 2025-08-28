<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="format-detection" content="telephone=no">
    <meta name="print-options" content="no-header,no-footer,background-graphics">
    <title>Rekap Absensi - <?php echo e($kelas->nama_kelas); ?></title>
    <style>
        /* Force background colors and graphics to print */
        * {
            -webkit-print-color-adjust: exact !important;
            color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 20px;
            line-height: 1.4;
            -webkit-print-color-adjust: exact;
            color-adjust: exact;
            print-color-adjust: exact;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
        }
        
        .header h1 {
            margin: 0;
            font-size: 18px;
            font-weight: bold;
        }
        
        .header h2 {
            margin: 5px 0;
            font-size: 16px;
            font-weight: normal;
        }
        
        .info-section {
            margin-bottom: 20px;
        }
        
        .info-table {
            width: 100%;
            margin-bottom: 20px;
        }
        
        .info-table td {
            padding: 3px 10px;
            vertical-align: top;
        }
        
        .info-table td:first-child {
            width: 150px;
            font-weight: bold;
        }
        
        .attendance-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        
        .attendance-table th,
        .attendance-table td {
            border: 1px solid #333;
            padding: 5px;
            text-align: center;
            font-size: 10px;
            -webkit-print-color-adjust: exact;
            color-adjust: exact;
            print-color-adjust: exact;
        }
        
        .attendance-table th {
            background-color: #f0f0f0 !important;
            font-weight: bold;
            -webkit-print-color-adjust: exact !important;
            color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        
        .attendance-table .student-info {
            text-align: left;
            padding-left: 8px;
        }
        
        .status-h { 
            background-color: #d4edda !important; 
            color: #155724 !important; 
            font-weight: bold;
            -webkit-print-color-adjust: exact !important;
            color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        .status-i { 
            background-color: #d1ecf1 !important; 
            color: #0c5460 !important; 
            font-weight: bold;
            -webkit-print-color-adjust: exact !important;
            color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        .status-s { 
            background-color: #fff3cd !important; 
            color: #856404 !important; 
            font-weight: bold;
            -webkit-print-color-adjust: exact !important;
            color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        .status-a { 
            background-color: #f8d7da !important; 
            color: #721c24 !important; 
            font-weight: bold;
            -webkit-print-color-adjust: exact !important;
            color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        
        .summary-section {
            margin-top: 30px;
        }
        
        .summary-table {
            width: 50%;
            border-collapse: collapse;
        }
        
        .summary-table th,
        .summary-table td {
            border: 1px solid #333;
            padding: 8px;
            text-align: center;
            -webkit-print-color-adjust: exact;
            color-adjust: exact;
            print-color-adjust: exact;
        }
        
        .summary-table th {
            background-color: #f0f0f0 !important;
            font-weight: bold;
            -webkit-print-color-adjust: exact !important;
            color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        
        .footer {
            margin-top: 40px;
            display: flex;
            justify-content: space-between;
        }
        
        .signature {
            text-align: center;
            width: 200px;
        }
        
        .signature-line {
            border-top: 1px solid #333;
            margin-top: 60px;
            padding-top: 5px;
        }
        
        @media print {
            body {
                margin: 0;
                padding: 15px;
                -webkit-print-color-adjust: exact;
                color-adjust: exact;
                print-color-adjust: exact;
            }
            
            .no-print {
                display: none !important;
            }
            
            /* Force remove browser's default header and footer */
            @page {
                margin: 0.5in;
                size: A4;
                /* Remove headers and footers */
                @top-left { content: ""; }
                @top-center { content: ""; }
                @top-right { content: ""; }
                @bottom-left { content: ""; }
                @bottom-center { content: ""; }
                @bottom-right { content: ""; }
            }
            
            /* Ensure backgrounds and colors print */
            * {
                -webkit-print-color-adjust: exact !important;
                color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            
            /* Ensure no extra margins that might cause issues */
            html, body {
                height: auto;
                overflow: visible;
            }
        }
        
        .print-button {
            position: fixed;
            top: 20px;
            right: 20px;
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
        }
        
        .print-button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <button class="print-button no-print" onclick="printDocument()">Cetak</button>
    
    <div class="header">
        <h1><?php echo e(strtoupper(setting('nama_sekolah', 'SMK PGRI CIKAMPEK'))); ?></h1>
        <h2>REKAP ABSENSI SISWA</h2>
    </div>
    
    <div class="info-section">
        <table class="info-table">
            <tr>
                <td>Mata Pelajaran</td>
                <td>: <?php echo e($mapel->nama); ?></td>
            </tr>
            <tr>
                <td>Kelas</td>
                <td>: <?php echo e($kelas->nama_kelas); ?></td>
            </tr>
            <tr>
                <td>Guru</td>
                <td>: <?php echo e($guru->nama_lengkap); ?></td>
            </tr>
            <tr>
                <td>Periode</td>
                <td>: <?php echo e(\Carbon\Carbon::parse($tanggalAwal)->format('d/m/Y')); ?> s/d <?php echo e(\Carbon\Carbon::parse($tanggalAkhir)->format('d/m/Y')); ?></td>
            </tr>
            <tr>
                <td>Jumlah Hari Absensi</td>
                <td>: <?php echo e(count($periode)); ?> hari</td>
            </tr>
            <tr>
                <td>Tanggal Cetak</td>
                <td>: <?php echo e(\Carbon\Carbon::now()->format('d/m/Y H:i')); ?></td>
            </tr>
        </table>
    </div>
    
    <table class="attendance-table">
        <thead>
            <tr>
                <th rowspan="2" style="width: 30px;">No</th>
                <th rowspan="2" style="width: 80px;">NIS</th>
                <th rowspan="2" style="width: 200px;">Nama Siswa</th>
                <th colspan="<?php echo e(count($periode)); ?>">Tanggal</th>
                <th colspan="4">Jumlah</th>
            </tr>
            <tr>
                <?php $__currentLoopData = $periode; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tgl): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <th style="width: 25px;"><?php echo e(\Carbon\Carbon::parse($tgl)->format('d')); ?></th>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <th style="width: 30px;">H</th>
                <th style="width: 30px;">I</th>
                <th style="width: 30px;">S</th>
                <th style="width: 30px;">A</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $siswa; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td><?php echo e($i + 1); ?></td>
                <td><?php echo e($s->nis); ?></td>
                <td class="student-info"><?php echo e($s->nama_lengkap); ?></td>
                
                <?php $__currentLoopData = $periode; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tgl): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <td>
                    <?php if(isset($dataAbsensi[$s->id][$tgl])): ?>
                        <?php if($dataAbsensi[$s->id][$tgl] == 'hadir'): ?>
                            <span class="status-h">H</span>
                        <?php elseif($dataAbsensi[$s->id][$tgl] == 'izin'): ?>
                            <span class="status-i">I</span>
                        <?php elseif($dataAbsensi[$s->id][$tgl] == 'sakit'): ?>
                            <span class="status-s">S</span>
                        <?php elseif($dataAbsensi[$s->id][$tgl] == 'alpha'): ?>
                            <span class="status-a">A</span>
                        <?php endif; ?>
                    <?php else: ?>
                        -
                    <?php endif; ?>
                </td>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                
                <td class="status-h"><?php echo e(isset($rekapData[$s->id]['hadir']) ? $rekapData[$s->id]['hadir'] : 0); ?></td>
                <td class="status-i"><?php echo e(isset($rekapData[$s->id]['izin']) ? $rekapData[$s->id]['izin'] : 0); ?></td>
                <td class="status-s"><?php echo e(isset($rekapData[$s->id]['sakit']) ? $rekapData[$s->id]['sakit'] : 0); ?></td>
                <td class="status-a"><?php echo e(isset($rekapData[$s->id]['alpha']) ? $rekapData[$s->id]['alpha'] : 0); ?></td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
        
    <div class="footer">
        <div class="signature">
            <p>Mengetahui,<br>Kepala Sekolah</p>
            <div class="signature-line">
                <strong></strong>
            </div>
        </div>
        <div class="signature">
            <p><?php echo e(setting('alamat_kota', 'Cikampek')); ?>, <?php echo e(\Carbon\Carbon::now()->format('d F Y')); ?><br>Guru Mata Pelajaran</p>
            <div class="signature-line">
                <strong><?php echo e($guru->nama_lengkap); ?></strong>
            </div>
        </div>
    </div>
    
    <script>
        // Function to open print dialog with optimized settings
        function printDocument() {
            // Hide any remaining browser UI elements
            const style = document.createElement('style');
            style.textContent = `
                @media print {
                    @page { 
                        margin: 0.5in; 
                        size: A4;
                    }
                    body { 
                        -webkit-print-color-adjust: exact; 
                        print-color-adjust: exact;
                    }
                }
            `;
            document.head.appendChild(style);
            
            // Open print dialog
            window.print();
        }
        
        // Auto configure print settings when page loads
        window.onload = function() {
            // Add print-specific styles
            const printStyle = document.createElement('style');
            printStyle.textContent = `
                @media print {
                    @page {
                        margin: 0.5in;
                        size: A4 portrait;
                    }
                    html, body {
                        -webkit-print-color-adjust: exact !important;
                        print-color-adjust: exact !important;
                        color-adjust: exact !important;
                    }
                }
            `;
            document.head.appendChild(printStyle);
        }
        
        // Close window after printing (optional)
        window.onafterprint = function() {
            // window.close();
        }
    </script>
</body>
</html>
<?php /**PATH C:\wamp64\www\website-smk3\resources\views\guru\absensi\cetak-rekap.blade.php ENDPATH**/ ?>