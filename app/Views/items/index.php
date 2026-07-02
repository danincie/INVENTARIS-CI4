<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="card rounded-3 mb-4">
    <div class="card-header bg-white py-3 px-4 border-bottom d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 fw-bold text-primary">Daftar Inventaris Barang</h6>
        <form action="<?= base_url('items') ?>" method="get" class="d-flex" id="searchForm" style="width: 250px;">
            <div class="input-group input-group-sm">
                <span class="input-group-text bg-white text-muted border-end-0" style="border-color: #dee2e6;"><i class="bi bi-search"></i></span>
                <input type="text" name="search" id="liveSearchInput" class="form-control border-start-0" placeholder="Cari barang..." value="<?= esc($search ?? '') ?>" autocomplete="off" style="box-shadow: none; border-color: #dee2e6;">
            </div>
        </form>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table align-middle mb-0" style="font-size: 0.95rem;">
                <thead style="background-color: #e4e9f0;">
                    <tr>
                        <th class="px-4 py-3 text-uppercase text-secondary border-bottom-0" style="font-size: 0.8rem; letter-spacing: 0.5px; background-color: #e4e9f0;">No</th>
                        <th class="py-3 text-uppercase text-secondary border-bottom-0" style="font-size: 0.8rem; letter-spacing: 0.5px; background-color: #e4e9f0;">Nama Barang</th>
                        <th class="py-3 text-uppercase text-secondary border-bottom-0" style="font-size: 0.8rem; letter-spacing: 0.5px; background-color: #e4e9f0;">Kategori</th>
                        <th class="py-3 text-uppercase text-secondary border-bottom-0" style="font-size: 0.8rem; letter-spacing: 0.5px; background-color: #e4e9f0;">Jumlah</th>
                        <th class="py-3 text-uppercase text-secondary border-bottom-0" style="font-size: 0.8rem; letter-spacing: 0.5px; background-color: #e4e9f0;">Harga Satuan</th>
                        <th class="py-3 text-uppercase text-secondary border-bottom-0" style="font-size: 0.8rem; letter-spacing: 0.5px; background-color: #e4e9f0;">Harga Total</th>
                        <th class="px-4 py-3 text-uppercase text-secondary border-bottom-0 text-center" style="font-size: 0.8rem; letter-spacing: 0.5px; background-color: #e4e9f0;">Aksi</th>
                    </tr>
                </thead>
                <tbody id="itemsTableBody">
                    <?php 
                    $totalAset = 0;
                    if(empty($items)): ?>
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">Belum ada data barang di gudang ini.</td>
                        </tr>
                    <?php else: ?>
                        <?php $no = 1; foreach($items as $item): 
                            $hargaTotal = $item['harga'] * $item['jumlah'];
                            $totalAset += $hargaTotal;
                        ?>
                        <tr>
                            <td class="px-4 py-3 text-muted"><?= $no++ ?></td>
                            <td class="py-3 fw-medium text-dark"><?= esc($item['nama_barang']) ?></td>
                            <td class="py-3"><span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25"><?= esc($item['kategori']) ?></span></td>
                            <td class="py-3">
                                <?php if ($item['jumlah'] < 5): ?>
                                    <span class="text-danger fw-bold"><?= esc($item['jumlah']) ?> <i class="bi bi-exclamation-triangle ms-1"></i></span>
                                <?php else: ?>
                                    <span class="fw-medium text-dark"><?= esc($item['jumlah']) ?></span>
                                <?php endif; ?>
                            </td>
                            <td class="py-3 text-success fw-bold">Rp <?= number_format($item['harga'], 0, ',', '.') ?></td>
                            <td class="py-3 text-primary fw-bold">Rp <?= number_format($hargaTotal, 0, ',', '.') ?></td>
                            <td class="px-4 py-3 text-center">
                                <a href="<?= base_url('items/edit/'.$item['id']) ?>" class="btn btn-sm btn-outline-primary border-opacity-50 shadow-sm me-1" title="Edit">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <form action="<?= base_url('items/delete/'.$item['id']) ?>" method="post" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus barang ini?');">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button type="submit" class="btn btn-sm btn-outline-danger border-opacity-50 shadow-sm" title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
                <tfoot style="background-color: #f8fafc;">
                    <tr>
                        <td colspan="7" class="px-4 py-3 border-top align-middle">
                            <span class="fw-bold text-secondary me-2" style="font-size: 0.8rem; letter-spacing: 0.5px; text-transform: uppercase;">
                                <i class="bi bi-wallet2 me-1"></i> Total Nilai Aset:
                            </span>
                            <span class="fw-bold text-primary" style="font-size: 0.95rem;">
                                Rp <?= isset($totalAset) ? number_format($totalAset, 0, ',', '.') : '0' ?>
                            </span>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('liveSearchInput');
    const tableBody = document.getElementById('itemsTableBody');
    const rows = tableBody.getElementsByTagName('tr');
    
    // Buat baris "tidak ditemukan" secara dinamis
    const noResultsRow = document.createElement('tr');
    noResultsRow.id = 'noResultsRow';
    noResultsRow.style.display = 'none';
    noResultsRow.innerHTML = '<td colspan="7" class="text-center py-5 text-muted"><i class="bi bi-search me-2 fs-5"></i>Tidak ada barang yang cocok dengan pencarian.</td>';
    tableBody.appendChild(noResultsRow);

    searchInput.addEventListener('input', function() {
        const filter = searchInput.value.toLowerCase();
        let hasVisibleRows = false;
        
        for (let i = 0; i < rows.length; i++) {
            // Lewati baris "tidak ditemukan" atau baris data kosong default
            if (rows[i].id === 'noResultsRow' || rows[i].querySelector('td[colspan="7"]')) {
                continue;
            }
            
            const nameCol = rows[i].getElementsByTagName('td')[1]; // Nama Barang
            const catCol = rows[i].getElementsByTagName('td')[2]; // Kategori
            
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
        
        // Tampilkan pesan jika tidak ada baris yang cocok dan filter tidak kosong, serta data aslinya memang tidak kosong
        const emptyStateRow = document.querySelector('#itemsTableBody > tr > td[colspan="7"]');
        if (!hasVisibleRows && filter !== '' && (!emptyStateRow || emptyStateRow.parentElement.id === 'noResultsRow')) {
            noResultsRow.style.display = '';
        } else {
            noResultsRow.style.display = 'none';
        }
    });
    
    // Mencegah form disubmit saat menekan enter agar filter tetap client-side (SPA feel)
    document.getElementById('searchForm').addEventListener('submit', function(e) {
        e.preventDefault();
    });
});
</script>
<?= $this->endSection() ?>
