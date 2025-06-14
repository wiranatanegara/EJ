<?php
session_start();

$input = $_POST['form2'] ?? '';
$lines = explode("\n", $input);
$varB = [];
$currentDate = '';

// Fungsi tambahan untuk mendeteksi baris "PEMINDAHBUKUAN ATM"
function containsPemindahbukuanATM($line) {
    return stripos($line, 'PEMINDAHBUKUAN ATM') !== false;
}

foreach ($lines as $line) {
    // Deteksi baris tanggal (format: TANGGAL : dd-mm-yyyy)
    if (preg_match('/TANGGAL\s*:\s*(\d{1,2}-\d{1,2}-\d{4})/i', $line, $dateMatches)) {
        $currentDate = $dateMatches[1];
        continue;
    }

    // Lewati jika mengandung PEMINDAHBUKUAN ATM
    if (containsPemindahbukuanATM($line)) {
        continue;
    }

    // Cari pola rekening dan waktu
    if (preg_match('/\b(\d{10,})\b.*\b(\d{1,2}:\d{2}:\d{2})\b/', $line, $matches)) {
        $rekening = $matches[1];
        $waktu = $matches[2];

        // Jika mengandung 'TARIK TUNAI ATM CARDLESS', ubah 2 digit akhir rekening jadi 'CL'
        if (stripos($line, 'TARIK TUNAI ATM CARDLESS') !== false) {
    $rekening .= 'CL';
}

        // Simpan dengan tanggal jika tersedia
        $varB[] = [
            'rekening' => $rekening, 
            'waktu' => $waktu,
            'tanggal' => $currentDate
        ];
    }
}

$_SESSION['varB'] = $varB;
header("Location: index.php");
exit;