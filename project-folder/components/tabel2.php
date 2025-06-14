<?php
$varB = $_SESSION['varB'] ?? [];
?>

<?php if (count($varB)): ?>
    <div class="table-responsive">
        <table class="table table-bordered table-sm table-striped">
            <thead class="table-light">
                <tr>
                    <th>No</th>
                    <th>Rekening</th>
                    <th>Tanggal</th>
                    <th>Waktu</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($varB as $i => $data): ?>
                    <tr>
                        <td><?= $i + 1 ?></td>
                        <td><?= htmlspecialchars($data['rekening']) ?></td>
                        <td><?= htmlspecialchars($data['tanggal']) ?></td>
                        <td><?= htmlspecialchars($data['waktu']) ?></td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>
<?php else: ?>
    <p>Tidak ada data ditemukan dari Form 2.</p>
<?php endif ?>