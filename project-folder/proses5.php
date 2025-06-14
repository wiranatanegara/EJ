<?php
session_start();

$varC = $_SESSION['varC'] ?? [];
$varE = $_SESSION['varE'] ?? [];

$varC_clean = array_filter($varC, function ($c) use ($varE) {
    foreach ($varE as $e) {
        if ($e['rekening'] == $c['rekening'] && $e['waktu'] == $c['waktu']) {
            return false;
        }
    }
    return true;
});

$_SESSION['varC_clean'] = $varC_clean;

header("Location: index.php");
exit;
