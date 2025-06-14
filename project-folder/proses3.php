<?php
session_start();

$varA = $_SESSION['varA'] ?? [];
$varB = $_SESSION['varB'] ?? [];
$varC = [];

foreach ($varB as $b) {
    foreach ($varA as $a) {
        if ($b['rekening'] == $a['rekening']) {
            $varC[] = $b;
            break;
        }
    }
}

$_SESSION['varC'] = $varC;

header("Location: proses4.php");
exit;
