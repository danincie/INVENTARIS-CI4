<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="card rounded-3 mb-4">
    <div class="card-header bg-white py-3 px-4 border-bottom d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 fw-bold text-primary">
            <i class="bi bi-clock-history me-2"></i>Riwayat Aktivitas Gudang
        </h6>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table align-middle mb-0" style="font-size: 0.95rem;">
                <thead style="background-color: #e4e9f0;">
                    <tr>
                        <th class="px-4 py-3 text-uppercase text-secondary border-bottom-0" style="font-size: 0.8rem; letter-spacing: 0.5px; width: 20%; background-color: #e4e9f0;">Waktu</th>
                        <th class="py-3 text-uppercase text-secondary border-bottom-0" style="font-size: 0.8rem; letter-spacing: 0.5px; width: 15%; background-color: #e4e9f0;">Aksi</th>
                        <th class="py-3 text-uppercase text-secondary border-bottom-0" style="font-size: 0.8rem; letter-spacing: 0.5px; background-color: #e4e9f0;">Deskripsi</th>
                        <th class="pe-4 py-3 text-end text-uppercase text-secondary border-bottom-0" style="font-size: 0.8rem; letter-spacing: 0.5px; width: 10%; background-color: #e4e9f0;"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($activities)): ?>
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted">
                                <i class="bi bi-journal-x fs-4 d-block mb-2 text-secondary opacity-50"></i>
                                Belum ada aktivitas yang dicatat di gudang ini.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach($activities as $act): ?>
                        <tr>
                            <td class="px-4 py-3 text-muted">
                                <?php 
                                    $date = new \DateTime($act['created_at']);
                                    echo $date->format('d M Y, H:i'); 
                                ?>
                            </td>
                            <td class="py-3">
                                <?php if($act['action'] == 'Tambah'): ?>
                                    <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2 py-1"><i class="bi bi-plus-circle me-1"></i><?= esc($act['action']) ?></span>
                                <?php elseif($act['action'] == 'Ubah'): ?>
                                    <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-2 py-1"><i class="bi bi-pencil-square me-1"></i><?= esc($act['action']) ?></span>
                                <?php elseif($act['action'] == 'Hapus'): ?>
                                    <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-2 py-1"><i class="bi bi-trash me-1"></i><?= esc($act['action']) ?></span>
                                <?php else: ?>
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 px-2 py-1"><?= esc($act['action']) ?></span>
                                <?php endif; ?>
                            </td>
                            <td class="py-3 fw-medium text-dark">
                                <?= esc($act['description']) ?>
                            </td>
                            <td class="pe-4 py-3 text-end">
                                <form action="<?= base_url('activities/delete/' . $act['id']) ?>" method="post" class="d-inline">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button type="submit" class="btn btn-sm btn-light text-danger rounded-circle border-0 shadow-sm" style="width: 32px; height: 32px;" onclick="return confirm('Apakah Anda yakin ingin menghapus riwayat ini?');" title="Hapus Riwayat">
                                        <i class="bi bi-x-lg"></i>
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
    <?php if (isset($pager) && $pager->getPageCount() > 1): ?>
    <div class="card-footer bg-white py-3 px-4 border-top d-flex justify-content-end">
        <?= $pager->links('activities', 'default_full') ?>
    </div>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>
