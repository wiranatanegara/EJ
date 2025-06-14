<?php
session_start();

$varA = $_SESSION['varA'] ?? [];
$varB = $_SESSION['varB'] ?? [];
$hasil = $_SESSION['hasil'] ?? [];
$varF = [];

// Fungsi pembanding waktu ±2 detik
function isTimeClose($time1, $time2, $range = 2) {
    $t1 = DateTime::createFromFormat('H:i:s', $time1);
    $t2 = DateTime::createFromFormat('H:i:s', $time2);
    if (!$t1 || !$t2) return false;
    $diff = abs($t1->getTimestamp() - $t2->getTimestamp());
    return $diff <= $range;
}

// Buat daftar rekening & waktu dari Tabel 1 (varA)
$rekeningA = array_column($varA, 'rekening');
$waktuA    = array_column($varA, 'waktu');

// Filter varB
foreach ($varB as $b) {
    $rekeningMatch = in_array($b['rekening'], $rekeningA);

    $waktuMatch = false;
    foreach ($waktuA as $wA) {
        if (isTimeClose($b['waktu'], $wA, 2)) {
            $waktuMatch = true;
            break;
        }
    }

    // Tambahkan ke varF hanya jika rekening & waktu tidak match
    if (!$rekeningMatch && !$waktuMatch) {
        $varF[] = $b;
    }
}

$_SESSION['varF'] = $varF;
header("Location: index.php");
exit;
