<?php session_start(); ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Sistem Pemrosesan Data ATM</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .card {
            max-height: 350px;
            overflow-y: auto;
        }
        textarea {
            font-family: monospace;
        }
    </style>
</head>
<body class="bg-light">

<div class="container py-4">
    <h1 class="mb-4 text-center">Sistem Pemrosesan Data ATM</h1>

    <div class="row g-4 mb-5">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <i class="bi bi-pencil-square me-1"></i>Form Input EJ
                </div>
                <div class="card-body">
                    <form method="post" action="proses1.php">
                        <textarea class="form-control mb-2" name="form1" rows="8" placeholder="Masukkan log transaksi ATM..."></textarea>
                        <button type="submit" class="btn btn-primary w-100">Proses Form 1</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white">
                    <i class="bi bi-pencil-square me-1"></i>Form Input Spoll
                </div>
                <div class="card-body">
                    <form method="post" action="proses2.php">
                        <textarea class="form-control mb-2" name="form2" rows="8" placeholder="Masukkan data mutasi tabungan..."></textarea>
                        <button type="submit" class="btn btn-success w-100">Proses Form 2</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


<<!-- Tombol Hapus Semua Tabel -->
<div class="d-flex justify-content-end my-3">
    <form method="post" action="reset.php" onsubmit="return confirm('Yakin ingin menghapus semua data di tabel?');">
        <button type="submit" class="btn btn-outline-danger btn-sm">
            <i class="bi bi-trash"></i> Hapus Semua Tabel
        </button>
    </form>
</div>

<!-- Grid 2 Kolom -->
<div class="row g-4">
    <!-- Tabel 1 -->
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
                <span><i class="bi bi-table me-1"></i>Tabel 1 – Data dari Form 1</span>
                <form method="post" action="proses1.php" class="m-0">
                    
                </form>
            </div>
            <div class="card-body p-2">
                <?php include 'components/tabel1.php'; ?>
            </div>
        </div>
    </div>

    <!-- Tabel 2 -->
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
                <span><i class="bi bi-table me-1"></i>Tabel 2 – Data dari Form 2</span>
                <form method="post" action="proses2.php" class="m-0">
                    
                </form>
            </div>
            <div class="card-body p-2">
                <?php include 'components/tabel2.php'; ?>
            </div>
        </div>
    </div>

    <!-- Tabel 3 -->
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                <span><i class="bi bi-clock-history me-1"></i>Transaksi Cardless Berpotensi Gagal</span>
                <form method="post" action="proses3.php" class="m-0">
                    <button type="submit" class="btn btn-sm btn-light">
                        <i class="bi bi-gear"></i> Proses
                    </button>
                </form>
            </div>
            <div class="card-body p-2">
                <?php include 'components/tabel3.php'; ?>
            </div>
        </div>
    </div>


    
    <!-- Tabel 4 -->
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                <span><i class="bi bi-filter-circle me-1"></i>Tabel 4 – Filtered Hasil</span>
                <form method="post" action="proses4.php" class="m-0">
                    <button type="submit" class="btn btn-sm btn-light">
                        <i class="bi bi-gear"></i> Proses
                    </button>
                </form>
            </div>
            <div class="card-body p-2">
                <?php include 'components/tabel4.php'; ?>
            </div>
        </div>
    </div>
</div>

<div class="mt-4">
    <h5>Diagram Garis – Intensitas Error per Transaksi</h5>
    <canvas id="errorChart" height="100"></canvas>
</div>

<?php if (!empty($_SESSION['varA'])): ?>
<script>
// Ambil context canvas
const ctx = document.getElementById('errorChart').getContext('2d');

// Label transaksi
const labels = <?= json_encode(array_map(
    fn($v, $i) => 'Transaksi ' . ($i + 1),
    $_SESSION['varA'],
    array_keys($_SESSION['varA'])
)) ?>;

// Jumlah error per transaksi
const errorCounts = <?= json_encode(array_map(function($v) {
    // Hitung jumlah kata error berdasarkan pemisah ;
    $errorString = trim($v['error']);
    if (empty($errorString)) return 0;
    return count(array_filter(explode(';', $errorString)));
}, $_SESSION['varA'])) ?>;

// Plugin latar belakang berdasarkan kelompok tanggal (contoh: grup 3 transaksi)
const backgroundPlugin = {
    id: 'backgroundPlugin',
    beforeDraw: (chart) => {
        const {ctx, chartArea: {top, bottom}, scales: {x}} = chart;

        // Contoh grup berdasarkan indeks transaksi
        const dateGroups = [
            {start: 0, end: 2, color: 'rgba(200,200,255,0.2)'},
            {start: 3, end: 5, color: 'rgba(255,200,200,0.2)'},
            {start: 6, end: 8, color: 'rgba(200,255,200,0.2)'},
        ];

        dateGroups.forEach(group => {
            const startPixel = x.getPixelForValue(group.start);
            const endPixel = x.getPixelForValue(group.end + 1);
            ctx.fillStyle = group.color;
            ctx.fillRect(startPixel, top, endPixel - startPixel, bottom - top);
        });
    }
};

// Buat chart
new Chart(ctx, {
    type: 'line',
    data: {
        labels: labels,
        datasets: [{
            label: 'Jumlah Error',
            data: errorCounts,
            borderColor: 'rgba(255, 99, 132, 1)',
            backgroundColor: 'rgba(255, 99, 132, 0.2)',
            tension: 0.3,
            pointRadius: 3
        }]
    },
    options: {
        scales: {
            y: {
                title: {
                    display: true,
                    text: 'Jumlah Error'
                },
                beginAtZero: true,
                ticks: {
                    precision: 0
                }
            },
            x: {
                title: {
                    display: true,
                    text: 'Transaksi'
                }
            }
        }
    },
    plugins: [backgroundPlugin]
});
</script>
<?php endif; ?>



<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="js/scripts.js"></script>
</body>
</html>
