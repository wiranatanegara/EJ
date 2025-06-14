<?php if (!empty($_SESSION['varF'])): ?>
<div class="table-responsive">
    <table class="table table-bordered table-striped table-sm align-middle">
        <thead class="table-dark">
            <tr class="text-center">
                <th scope="col">No.</th>
                <th scope="col">No. Rekening</th>
                <th scope="col">Waktu</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($_SESSION['varF'] as $index => $data): ?>
                <tr>
                    <td class="text-center"><?= $index + 1 ?></td>
                    <td class="text-monospace"><?= htmlspecialchars($data['rekening']) ?></td>
                    <td class="text-center"><?= htmlspecialchars($data['waktu']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php else: ?>
    <div class="text-muted">Tidak ada data hasil selisih (varC - varE) untuk ditampilkan.</div>
<?php endif; ?>
