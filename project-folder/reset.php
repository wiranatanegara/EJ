<?php
session_start();

// Hapus semua data tabel dari session
unset($_SESSION['varA']);
unset($_SESSION['varB']);
unset($_SESSION['varC']);
unset($_SESSION['varD']);
unset($_SESSION['varE']);
unset($_SESSION['varF']); // <-- Ini tambahan penting

// Redirect kembali ke index
header("Location: index.php");
exit;
