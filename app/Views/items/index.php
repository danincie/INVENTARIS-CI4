<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="card rounded-3 mb-4">
    <div class="card-header bg-white py-3 px-4 border-bottom d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 fw-bold text-primary">Daftar Inventaris Barang</h6>
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
                        <th class="px-4 py-3 text-uppercase text-secondary border-bottom-0 text-center" style="font-size: 0.8rem; letter-spacing: 0.5px; background-color: #e4e9f0;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($items)): ?>
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">Belum ada data barang di gudang ini.</td>
                        </tr>
                    <?php else: ?>
                        <?php $no = 1; foreach($items as $item): ?>
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
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
