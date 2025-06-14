<?php if (!empty($_SESSION['varA'])): ?>
<div class="table-responsive">
    <table class="table table-bordered table-striped table-sm align-middle">
        <thead class="table-dark">
            <tr class="text-center">
                <th scope="col">No.</th>
                <th scope="col">No. Rekening/Kartu</th>
                <th scope="col">Tanggal</th>
                <th scope="col">Waktu</th>
                <th scope="col">Error</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($_SESSION['varA'] as $index => $data): ?>
                <tr>
                    <td class="text-center"><?= $index + 1 ?></td>
                    <td class="text-monospace"><?= htmlspecialchars($data['rekening']) ?></td>
                    <td class="text-center"><?= htmlspecialchars($data['tanggal']) ?></td>
                    <td class="text-center"><?= htmlspecialchars($data['waktu']) ?></td>
                    <td class="text-danger">
                        <?php if (!empty($data['error'])): ?>
                            <span class="badge bg-danger">ERROR</span> 
                            <?= htmlspecialchars($data['error']) ?>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Tampilkan jumlah error -->
<div class="alert alert-info mt-3">
    Total Transaksi Error: <strong><?= $_SESSION['errorCount'] ?></strong>
</div>
<?php else: ?>
    <div class="text-muted">Tidak ada data untuk ditampilkan.</div>
<?php endif; ?>