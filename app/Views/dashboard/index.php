<?php
/**
 * @var int|string $total_items
 * @var int|float $total_value
 * @var int $total_categories
 * @var int $low_stock_count
 * @var array $recent_items
 * @var array $categories_list
 * @var string $current_kategori
 * @var string $current_sort
 */
?>
<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="row g-3 mb-4">
    <div class="col-md-6 col-lg-3">
        <div class="card h-100 shadow-sm" style="background-color: #eff3f7;">
            <div class="card-body p-3 px-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="pe-1">
                        <p class="text-muted mb-1 fw-medium" style="font-size: 0.85rem;">Total Barang</p>
                        <h4 class="fw-bold mb-0 text-dark text-nowrap" style="font-size: 1.45rem;"><?= number_format($total_items, 0, ',', '.') ?> <span class="fs-6 fw-normal text-muted">Item</span></h4>
                    </div>
                    <div class="bg-primary bg-opacity-10 rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 48px; height: 48px;">
                        <i class="bi bi-box-seam fs-4 text-primary"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6 col-lg-3">
        <div class="card h-100 shadow-sm" style="background-color: #e4e9f0;">
            <div class="card-body p-3 px-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="pe-1">
                        <p class="text-muted mb-1 fw-medium" style="font-size: 0.85rem;">Kategori Barang</p>
                        <h4 class="fw-bold mb-0 text-dark text-nowrap" style="font-size: 1.45rem;"><?= $total_categories ?> <span class="fs-6 fw-normal text-muted">Jenis</span></h4>
                    </div>
                    <div class="bg-info bg-opacity-10 rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 48px; height: 48px;">
                        <i class="bi bi-tags fs-4 text-info"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-lg-3">
        <div class="card h-100 shadow-sm" style="background-color: #eff3f7;">
            <div class="card-body p-3 px-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="pe-1">
                        <p class="text-muted mb-1 fw-medium" style="font-size: 0.85rem;">Total Nilai Aset</p>
                        <h4 class="fw-bold mb-0 text-dark text-nowrap" style="font-size: 1.45rem;">Rp <?= number_format($total_value, 0, ',', '.') ?></h4>
                    </div>
                    <div class="bg-success bg-opacity-10 rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 48px; height: 48px;">
                        <i class="bi bi-cash-coin fs-4 text-success"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-lg-3">
        <div class="card h-100 shadow-sm" style="background-color: #e4e9f0;">
            <div class="card-body p-3 px-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="pe-1">
                        <p class="text-muted mb-1 fw-medium" style="font-size: 0.85rem;">Stok Tipis (< 5)</p>
                        <h4 class="fw-bold mb-0 text-dark text-nowrap" style="font-size: 1.45rem;"><?= $low_stock_count ?> <span class="fs-6 fw-normal text-muted">Barang</span></h4>
                    </div>
                    <div class="bg-danger bg-opacity-10 rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 48px; height: 48px;">
                        <i class="bi bi-exclamation-triangle fs-4 text-danger"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card rounded-3">
            <div class="card-header bg-white py-3 px-0 border-bottom">
                <form action="<?= base_url('dashboard') ?>" method="get" class="d-flex w-100 align-items-center m-0" id="dashboardSearchForm">
                    <div style="width: 45%;" class="ps-4 d-flex align-items-center gap-3">
                        <h6 class="m-0 fw-bold text-primary text-nowrap">Daftar Barang</h6>
                        <div class="input-group input-group-sm" style="max-width: 220px;">
                            <span class="input-group-text bg-white text-muted border-end-0" style="border-color: #dee2e6;"><i class="bi bi-search"></i></span>
                            <input type="text" id="dashboardLiveSearchInput" class="form-control border-start-0" placeholder="Cari di tabel ini..." autocomplete="off" style="box-shadow: none; border-color: #dee2e6;">
                        </div>
                    </div>
                    
                    <div style="width: 30%;" class="d-flex align-items-center gap-2 px-2">
                        <label for="category_id" class="text-muted mb-0 text-nowrap fw-medium" style="font-size: 0.8rem;">Kategori :</label>
                        <select name="category_id" id="category_id" class="form-select form-select-sm border-0 bg-light w-100 fw-medium text-dark" onchange="this.form.submit()" style="cursor: pointer;">
                            <option value="">Semua Kategori</option>
                            <?php if (!empty($categories_list)): ?>
                                <?php foreach($categories_list as $cat): ?>
                                <option value="<?= $cat['id'] ?>" <?= $current_kategori == $cat['id'] ? 'selected' : '' ?>><?= esc($cat['nama_kategori']) ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>

                    <div style="width: 25%;" class="d-flex align-items-center gap-2 pe-4 ps-2">
                        <label for="sort" class="text-muted mb-0 text-nowrap fw-medium" style="font-size: 0.8rem;">Urutkan :</label>
                        <select name="sort" id="sort" class="form-select form-select-sm border-0 bg-light w-100 fw-medium text-dark" onchange="this.form.submit()" style="cursor: pointer;">
                            <option value="terbaru" <?= $current_sort == 'terbaru' ? 'selected' : '' ?>>Terbaru</option>
                            <option value="terlama" <?= $current_sort == 'terlama' ? 'selected' : '' ?>>Terlama</option>
                            <option value="stok_terbanyak" <?= $current_sort == 'stok_terbanyak' ? 'selected' : '' ?>>Terbanyak</option>
                            <option value="stok_terdikit" <?= $current_sort == 'stok_terdikit' ? 'selected' : '' ?>>Terdikit</option>
                            <option value="harga_termahal" <?= $current_sort == 'harga_termahal' ? 'selected' : '' ?>>Termahal</option>
                            <option value="harga_termurah" <?= $current_sort == 'harga_termurah' ? 'selected' : '' ?>>Termurah</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table align-middle mb-0" style="font-size: 0.95rem; table-layout: fixed; width: 100%;">
                        <thead style="background-color: #e4e9f0;">
                            <tr>
                                <th class="ps-4 py-3 text-uppercase text-secondary border-bottom-0" style="font-size: 0.8rem; letter-spacing: 0.5px; width: 30%; background-color: #e4e9f0;">Nama Barang</th>
                                <th class="py-3 text-uppercase text-secondary border-bottom-0" style="font-size: 0.8rem; letter-spacing: 0.5px; width: 15%; background-color: #e4e9f0;">Kategori</th>
                                <th class="py-3 text-uppercase text-secondary border-bottom-0" style="font-size: 0.8rem; letter-spacing: 0.5px; width: 20%; background-color: #e4e9f0;">Harga Satuan</th>
                                <th class="py-3 text-uppercase text-secondary border-bottom-0" style="font-size: 0.8rem; letter-spacing: 0.5px; width: 15%; background-color: #e4e9f0;">Jumlah (Stok)</th>
                                <th class="pe-4 py-3 text-uppercase text-secondary border-bottom-0" style="font-size: 0.8rem; letter-spacing: 0.5px; width: 20%; background-color: #e4e9f0;">Harga Total</th>
                            </tr>
                        </thead>
                        <tbody id="dashboardItemsTableBody">
                            <?php if (empty($recent_items)): ?>
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted">Belum ada barang di gudang ini.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach($recent_items as $item): ?>
                                <tr>
                                    <td class="ps-4 py-3 fw-medium text-dark text-truncate"><?= esc($item['nama_barang']) ?></td>
                                    <td class="py-3 text-truncate"><span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25"><?= esc($item['kategori']) ?></span></td>
                                    <td class="py-3 text-success fw-bold text-truncate">Rp <?= number_format($item['harga'], 0, ',', '.') ?></td>
                                    <td class="py-3 text-truncate">
                                        <?php if ($item['jumlah'] < 5): ?>
                                            <span class="text-danger fw-bold"><?= esc($item['jumlah']) ?> <i class="bi bi-exclamation-triangle ms-1"></i></span>
                                        <?php else: ?>
                                            <span class="fw-medium text-dark"><?= esc($item['jumlah']) ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="pe-4 py-3 text-primary fw-bold text-truncate">Rp <?= number_format($item['harga'] * $item['jumlah'], 0, ',', '.') ?></td>
                                </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                        <tfoot style="background-color: #f8fafc;">
                            <tr>
                                <td colspan="5" class="px-4 py-3 border-top align-middle">
                                    <span class="fw-bold text-secondary me-2" style="font-size: 0.8rem; letter-spacing: 0.5px; text-transform: uppercase;">
                                        <i class="bi bi-wallet2 me-1"></i> Total Nilai Aset:
                                    </span>
                                    <span class="fw-bold text-primary" style="font-size: 0.95rem;">
                                        Rp <?= number_format($total_value, 0, ',', '.') ?>
                                    </span>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-white py-3 px-4 border-top text-center">
                <a href="<?= base_url('items') ?>" class="btn btn-sm btn-outline-primary px-4 rounded-pill">Kelola Semua Data Barang</a>
            </div>
        </div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('dashboardLiveSearchInput');
    const tableBody = document.getElementById('dashboardItemsTableBody');
    const rows = tableBody.getElementsByTagName('tr');
    
    const noResultsRow = document.createElement('tr');
    noResultsRow.id = 'dashboardNoResultsRow';
    noResultsRow.style.display = 'none';
    noResultsRow.innerHTML = '<td colspan="5" class="text-center py-5 text-muted"><i class="bi bi-search me-2 fs-5"></i>Tidak ada barang yang cocok dengan pencarian.</td>';
    tableBody.appendChild(noResultsRow);

    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const filter = searchInput.value.toLowerCase();
            let hasVisibleRows = false;
            
            for (let i = 0; i < rows.length; i++) {
                if (rows[i].id === 'dashboardNoResultsRow' || rows[i].querySelector('td[colspan="5"]')) {
                    continue;
                }
                
                const nameCol = rows[i].getElementsByTagName('td')[0];
                const catCol = rows[i].getElementsByTagName('td')[1];
                
                if (nameCol || catCol) {
                    const nameText = nameCol.textContent || nameCol.innerText;
                    const catText = catCol.textContent || catCol.innerText;
                    
                    if (nameText.toLowerCase().indexOf(filter) > -1 || catText.toLowerCase().indexOf(filter) > -1) {
                        rows[i].style.display = '';
                        hasVisibleRows = true;
                    } else {
                        rows[i].style.display = 'none';
                    }
                }
            }
            
            const emptyStateRow = document.querySelector('#dashboardItemsTableBody > tr > td[colspan="5"]');
            if (!hasVisibleRows && filter !== '' && (!emptyStateRow || emptyStateRow.parentElement.id === 'dashboardNoResultsRow')) {
                noResultsRow.style.display = '';
            } else {
                noResultsRow.style.display = 'none';
            }
        });
    }
    
    if(document.getElementById('dashboardSearchForm')) {
        document.getElementById('dashboardSearchForm').addEventListener('submit', function(e) {
            if (document.activeElement === searchInput) {
                e.preventDefault();
            }
        });
    }
});
</script>
<?= $this->endSection() ?>
