<?php
if (!isset($_SESSION)) session_start();

// Set timezone sesuai lokasi ATM (Asia/Makassar)
date_default_timezone_set('Asia/Makassar');

$varA = $_SESSION['varA'] ?? [];
$varB = $_SESSION['varB'] ?? [];
$hasil = [];

foreach ($varA as $a) {
    // Syarat baru: hanya proses jika ada error pada Tabel 1
    if (empty($a['error'])) continue;
    
    // Lewati jika rekening Tabel 1 tidak diawali 08
    if (!preg_match('/^08\d{8,}/', $a['rekening'])) continue;
    
    // Pastikan ada tanggal dan waktu
    if (empty($a['tanggal']) || empty($a['waktu'])) continue;
    
    // Normalisasi tanggal Tabel 1 (dd/mm/yy -> dd-mm-yy)
    $tanggalA = str_replace('/', '-', $a['tanggal']);
    
    // Parse tanggal Tabel 1 dengan format dd-mm-yy
    $datetimeA = DateTime::createFromFormat('d-m-y H:i:s', $tanggalA . ' ' . $a['waktu']);
    
    if (!$datetimeA) {
        // Coba format alternatif jika gagal
        $datetimeA = DateTime::createFromFormat('d-m-y H:i:s', $tanggalA . ' ' . $a['waktu']);
    }
    
    if (!$datetimeA) continue;
    $timestampA = $datetimeA->getTimestamp();

    foreach ($varB as $b) {
        // Pastikan ada tanggal dan waktu
        if (empty($b['tanggal']) || empty($b['waktu'])) continue;
        
        // Normalisasi waktu Tabel 2
        $waktuB = $b['waktu'];
        if (substr_count($waktuB, ':') === 2) {
            $waktuParts = explode(':', $waktuB);
            $waktuB = sprintf('%02d:%02d:%02d', $waktuParts[0], $waktuParts[1], $waktuParts[2]);
        }
        
        // Parse tanggal Tabel 2 dengan format dd-mm-YYYY
        $datetimeB = DateTime::createFromFormat('d-m-Y H:i:s', $b['tanggal'] . ' ' . $waktuB);
        
        if (!$datetimeB) continue;
        $timestampB = $datetimeB->getTimestamp();

        $selisih = abs($timestampA - $timestampB);

        if ($selisih <= 2) {
            $hasil[] = [
                'rek1' => $a['rekening'],
                'rek2' => $b['rekening'],
                'tanggal1' => $a['tanggal'],
                'waktu1' => $a['waktu'],
                'tanggal2' => $b['tanggal'],
                'waktu2' => $b['waktu'],
                'selisih' => $selisih,
                'timestamp1' => $timestampA,
                'timestamp2' => $timestampB,
                'highlight' => true,
                'error' => $a['error'] // Simpan pesan error untuk ditampilkan
            ];
        }
    }
}

// Urutkan hasil berdasarkan timestamp
usort($hasil, function($a, $b) {
    return $a['timestamp1'] <=> $b['timestamp1'];
});

if (empty($hasil)) {
    echo "<p class='text-muted fst-italic'>Tidak ada pasangan rekening dengan waktu cocok (±2 detik) & rekening diawali 08.</p>";
} else {
    echo "<div class='table-responsive'>";
    echo "<table class='table table-bordered table-sm table-hover'>";
    echo "<thead class='table-light'><tr>
            <th>No Telp</th>
            <th>Rekening</th>
            <th>Tanggal Tabel 1</th>
            <th>Waktu Tabel 1</th>
            <th>Pesan Error</th>
            <th>Tanggal Tabel 2</th>
            <th>Waktu Tabel 2</th>
            <th>Selisih (detik)</th>
          </tr></thead><tbody>";

    foreach ($hasil as $row) {
        $rowClass = $row['highlight'] ? 'table-warning fw-bold' : '';
        echo "<tr class='$rowClass'>";
        echo "<td>{$row['rek1']}</td>";
        echo "<td>{$row['rek2']}</td>";
        echo "<td>{$row['tanggal1']}</td>";
        echo "<td>{$row['waktu1']}</td>";
        echo "<td class='text-danger'><small>{$row['error']}</small></td>";
        echo "<td>{$row['tanggal2']}</td>";
        echo "<td>{$row['waktu2']}</td>";
        echo "<td class='text-center'>{$row['selisih']}</td>";
        echo "</tr>";
    }

    echo "</tbody></table></div>";
}
?>