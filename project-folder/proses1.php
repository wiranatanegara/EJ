<?php
session_start();

$input = $_POST['form1'];
$lines = explode("\n", $input);
$varA = [];

$norek = '';
$kartu = '';
$currentTanggal = '';
$currentWaktu = '';
$currentError = '';

// Daftar kata kunci error
$errorKeywords = [
    'PHYSICAL CASSETTE', 'COMMUNICATION ERROR',
    'COMMUNICATION OFFLINE', 'CDM ERROR', 'FAILED', 'ERROR'
];

// =====================
// PARSING DATA UTAMA + ERROR
// =====================
foreach ($lines as $line) {
    $line = trim($line);
    
    // Tangkap nomor rekening
    if (preg_match('/INFORMATION\s+(\d{10,})/', $line, $match) || preg_match('/REKENING\s*:\s*(\d+)/i', $line, $match)) {
        $norek = $match[1];
    }

    // Tangkap nomor kartu
    if (preg_match('/NO KARTU\s*:\s*(\d+(?:\*{4,}\d{4}))/i', $line, $match) || preg_match('/CARD\((\d+(?:\*{4,}\d{4}))\)/', $line, $match)) {
        $kartu = $match[1];
    }

    // Deteksi tanggal dan waktu
    if (preg_match('/TANGGAL\s+(\d{2}\/\d{2}\/\d{2})\s+JAM\s+(\d{2}:\d{2}:\d{2})/', $line, $match) || 
        preg_match('/(\d{2}\/\d{2}\/\d{2})\s+(\d{2}:\d{2}:\d{2})/', $line, $match)) {
        $currentTanggal = $match[1];
        $currentWaktu = $match[2];
    }

    // Deteksi error menggunakan kata kunci
    foreach ($errorKeywords as $keyword) {
        if (stripos($line, $keyword) !== false) {
            $currentError .= $keyword . '; ';
        }
    }

    // Deteksi error spesifik dari PESAN
    if (preg_match('/PESAN\s*:(.*)/i', $line, $match)) {
        $errorLine = trim($match[1]);
        if (!empty($errorLine)) {
            $currentError .= $errorLine . '; ';
        }
    }

    // Jika sudah lengkap tanggal, waktu dan salah satu dari rekening/kartu, simpan
    if ($currentTanggal && $currentWaktu && ($norek || $kartu)) {
        if ($norek) {
            $varA[] = [
                'rekening' => $norek,
                'tanggal' => $currentTanggal,
                'waktu' => $currentWaktu,
                'error' => trim($currentError, '; ')
            ];
        }

        if ($kartu) {
            $varA[] = [
                'rekening' => $kartu,
                'tanggal' => $currentTanggal,
                'waktu' => $currentWaktu,
                'error' => trim($currentError, '; ')
            ];
        }

        // Reset variabel untuk transaksi berikutnya
        $norek = '';
        $kartu = '';
        $currentTanggal = '';
        $currentWaktu = '';
        $currentError = '';
    }
}

// =====================
// HITUNG JUMLAH ERROR
// =====================
$errorCount = 0;
foreach ($varA as $transaction) {
    if (!empty($transaction['error'])) {
        $errorCount++;
    }
}

$_SESSION['varA'] = $varA;
$_SESSION['errorCount'] = $errorCount;
$_SESSION['varD'] = [];

header("Location: index.php");
exit;
