<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Tambah Barang</h1>
    <a href="<?= base_url('items') ?>" class="btn btn-sm btn-secondary shadow-sm">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>

<div class="card shadow mb-4">
    <div class="card-body">
        <?php if(isset($validation)): ?>
            <div class="alert alert-danger">
                <?= $validation->listErrors() ?>
            </div>
        <?php endif; ?>

        <form action="<?= base_url('items/store') ?>" method="post">
            <?= csrf_field() ?>
            <div class="mb-3">
                <label for="nama_barang" class="form-label fw-bold">Nama Barang</label>
                <input type="text" class="form-control" id="nama_barang" name="nama_barang" value="<?= old('nama_barang') ?>" required>
            </div>
            <div class="mb-3">
                <label for="category_id" class="form-label fw-bold">Kategori</label>
                <select class="form-select" id="category_id" name="category_id" required>
                    <option value="" disabled <?= set_select('category_id', '', true) ?>></option>
                    <?php if(!empty($categories)): ?>
                        <?php foreach($categories as $category): ?>
                            <option value="<?= $category['id'] ?>" <?= set_select('category_id', $category['id']) ?>><?= esc($category['nama_kategori']) ?></option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="jumlah" class="form-label fw-bold">Jumlah</label>
                    <input type="number" class="form-control" id="jumlah" name="jumlah" value="<?= set_value('jumlah') ?>" required min="0">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="harga" class="form-label fw-bold">Harga Satuan</label>
                    <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="number" step="1" class="form-control" id="harga" name="harga" value="<?= set_value('harga') ?>" required min="0">
                    </div>
                </div>
            </div>
            <div class="mb-3">
                <label for="deskripsi" class="form-label fw-bold">Deskripsi</label>
                <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3"><?= set_value('deskripsi') ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Simpan</button>
        </form>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('#category_id').select2({
            theme: 'bootstrap-5',
            tags: true,
            placeholder: '',
            allowClear: true
        });
    });
</script>
<?= $this->endSection() ?>
