<?php
/**
 * @var array $workspaces
 */
?>
<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<style>
    .description-clamp {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        word-wrap: break-word;
        overflow-wrap: anywhere;
    }
</style>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-dark fw-bold">Pilih Gudang</h1>
    <button class="btn btn-sm btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#addWorkspaceModal">
        <i class="bi bi-plus-lg"></i> Buat Gudang Baru
    </button>
</div>

<div class="row">
    <?php foreach ($workspaces as $w) : ?>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card h-100 py-2 <?= (session()->get('active_workspace_id') == $w['id']) ? 'border-primary border-2 shadow' : '' ?>">
                <div class="card-body d-flex flex-column">
                    <div class="text-xs fw-bold text-primary text-uppercase mb-1" style="font-size: 0.8rem;">
                        <?= (session()->get('active_workspace_id') == $w['id']) ? '<i class="bi bi-check-circle-fill"></i> Gudang Aktif' : 'Gudang' ?>
                    </div>
                    <div class="h5 mb-0 fw-bold text-dark"><?= esc($w['nama_workspace']) ?></div>
                    
                    <div class="flex-grow-1">
                        <p class="text-muted small mt-2 mb-1 description-clamp" id="desc-<?= $w['id'] ?>">
                            <?= esc($w['deskripsi'] ?? 'Tidak ada deskripsi.') ?>
                        </p>
                        <?php if (!empty($w['deskripsi']) && strlen($w['deskripsi']) > 60): ?>
                            <a href="javascript:void(0);" onclick="document.getElementById('desc-<?= $w['id'] ?>').classList.toggle('description-clamp'); this.innerText = this.innerText === 'Baca selengkapnya' ? 'Tutup' : 'Baca selengkapnya';" class="text-decoration-none d-block mb-3 fw-medium" style="font-size: 0.75rem;">Baca selengkapnya</a>
                        <?php else: ?>
                            <div class="mb-3"></div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="mt-auto">
                        <div class="d-flex gap-2 mb-2">
                            <?php if (session()->get('active_workspace_id') != $w['id']): ?>
                                <a href="<?= base_url('workspaces/select/' . $w['id']) ?>" class="btn btn-outline-primary btn-sm flex-grow-1 fw-bold">Masuk Gudang</a>
                            <?php else: ?>
                                <button class="btn btn-primary btn-sm flex-grow-1 fw-bold shadow-sm" disabled>Sedang Dibuka</button>
                            <?php endif; ?>
                        </div>

                        <div class="d-flex gap-2">
                            <button class="btn btn-outline-primary btn-sm flex-grow-1" data-bs-toggle="modal" data-bs-target="#editWorkspaceModal<?= $w['id'] ?>"><i class="bi bi-pencil"></i> Edit</button>
                            <button class="btn btn-outline-danger btn-sm flex-grow-1" onclick="if(confirm('Yakin ingin menghapus Gudang ini? SEMUA BARANG di dalam gudang ini akan ikut TERHAPUS!')) window.location.href='<?= base_url('workspaces/delete/' . $w['id']) ?>'"><i class="bi bi-trash"></i> Hapus</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<!-- Modal Tambah Gudang -->
<div class="modal fade" id="addWorkspaceModal" tabindex="-1" aria-labelledby="addWorkspaceModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addWorkspaceModalLabel">Buat Gudang Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= base_url('workspaces/store') ?>" method="post">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nama_workspace" class="form-label fw-bold">Nama Gudang</label>
                        <input type="text" class="form-control" id="nama_workspace" name="nama_workspace" required>
                    </div>
                    <div class="mb-3">
                        <label for="deskripsi" class="form-label fw-bold">Deskripsi (Opsional)</label>
                        <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Gudang</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modals Edit Gudang untuk setiap Gudang -->
<?php foreach ($workspaces as $w) : ?>
<div class="modal fade" id="editWorkspaceModal<?= $w['id'] ?>" tabindex="-1" aria-labelledby="editWorkspaceModalLabel<?= $w['id'] ?>" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editWorkspaceModalLabel<?= $w['id'] ?>">Edit Gudang</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= base_url('workspaces/update/' . $w['id']) ?>" method="post">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nama Gudang</label>
                        <input type="text" class="form-control" name="nama_workspace" value="<?= esc($w['nama_workspace']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Deskripsi</label>
                        <textarea class="form-control" name="deskripsi" rows="3"><?= esc($w['deskripsi']) ?></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endforeach; ?>

<?= $this->endSection() ?>
