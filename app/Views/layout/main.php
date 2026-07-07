<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Inventaris Barang' ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
    
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    
    <style>
        .select2-container--bootstrap-5.select2-container--focus .select2-selection,
        .select2-container--bootstrap-5.select2-container--open .select2-selection,
        .select2-container--bootstrap-5 .select2-search .select2-search__field:focus {
            box-shadow: none !important;
            border-color: #dee2e6 !important;
            outline: none !important;
        }

        body {
            font-family: system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            background-color: #ffffff; 
            color: #334155;
        }
        .sidebar {
            width: 260px;
            background-color: #e4e9f0; 
            min-height: 100vh;
            color: #334155;
            border-right: 0; 
            transition: all 0.3s ease;
        }
        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 1.25rem 1.5rem 0.5rem 1.5rem; 
            font-size: 1.15rem; 
            font-weight: 700;
            color: #0f172a;
            text-decoration: none;
        }
        .sidebar-brand i {
            color: #0f172a;
        }
        .sidebar .nav {
            margin-top: 0;
        }
        .sidebar .nav-link {
            color: #475569;
            padding: 0.6rem 1rem;
            margin: 0.25rem 0.5rem;
            border-radius: 0.5rem;
            font-weight: 500;
            font-size: 0.95rem;
            transition: all 0.2s ease;
        }
        .sidebar .nav-link:hover {
            color: #0f172a;
            background-color: #f1f5f9;
        }
        .sidebar .nav-link.active {
            color: #1d4ed8; 
            background-color: #eff6ff; 
            font-weight: 600;
        }
        .sidebar .nav-link i {
            margin-right: 0.5rem;
            font-size: 1.1rem;
        }
        .main-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            overflow-x: hidden;
            background-color: #e4e9f0; 
        }
        .topbar {
            height: 70px;
            background-color: #e4e9f0;
            border-bottom: 0; 
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 1.5rem; 
        }
        .page-content {
            padding: 2rem;
            flex: 1;
        }
        .card {
            border: 1px solid #e2e8f0;
            border-radius: 0.85rem;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05), 0 2px 4px -2px rgba(0,0,0,0.03) !important;
        }
        .table tbody tr {
            transition: background-color 0.2s ease;
        }
        .table tbody tr:hover {
            background-color: #f8fafc !important;
        }
        .card-header {
            background-color: #f8f9fa;
            border-bottom: 1px solid #dee2e6;
            font-weight: 600;
        }
        .toggle-icon {
            transition: transform 0.2s ease;
        }
        .section-toggle[aria-expanded="false"] .toggle-icon {
            transform: rotate(180deg);
        }
    </style>
</head>
<body>
    <div class="d-flex">
        <nav class="sidebar d-none d-md-block">
            <a href="<?= base_url('dashboard') ?>" class="sidebar-brand">
                <i class="bi bi-box-seam" style="font-size: 1.3rem;"></i> 
                <span>Inventaris</span>
            </a>
            
            <ul class="nav flex-column mt-0 mb-2">
                <?php $sidebarWorkspaceModel = new \App\Models\WorkspaceModel(); ?>
                <?php $allWorkspaces = $sidebarWorkspaceModel->findAll(); ?>

                <li class="nav-item">
                    <a class="nav-link <?= (session()->get('sidebar_active') !== 'workspace' && (url_is('/') || url_is('dashboard*') || url_is('items*') || url_is('activities*'))) ? 'active text-primary bg-primary bg-opacity-10 fw-bold rounded' : '' ?>" href="<?= base_url('dashboard?src=sidebar') ?>" style="font-size: 1rem; padding: 0.75rem 1rem; margin-bottom: 0;">
                        <i class="bi bi-grid <?= (session()->get('sidebar_active') !== 'workspace' && (url_is('/') || url_is('dashboard*') || url_is('items*') || url_is('activities*'))) ? 'text-primary' : '' ?> me-2" style="font-size: 1.15rem;"></i> Dashboard
                    </a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link <?= (url_is('workspaces*')) ? 'active text-primary bg-primary bg-opacity-10 fw-bold rounded' : '' ?>" href="<?= base_url('workspaces') ?>" style="font-size: 0.9rem; padding: 0.5rem 1rem; margin-top: 0;">
                        <i class="bi bi-buildings me-2"></i> Kelola Gudang
                    </a>
                </li>
                
                <li class="nav-item mt-3 mb-1">
                    <a class="d-flex align-items-center justify-content-between text-decoration-none text-muted section-toggle" data-bs-toggle="collapse" href="#collapseWorkspaces" role="button" aria-expanded="true" style="padding: 0 1rem; margin: 0 0.5rem;">
                        <span class="fw-bold" style="font-size: 0.7rem; letter-spacing: 0.5px;">DAFTAR GUDANG</span>
                        <i class="bi bi-chevron-up toggle-icon" style="font-size: 0.7rem;"></i>
                    </a>
                </li>
                
                <li class="nav-item">
                    <div class="collapse show" id="collapseWorkspaces">
                        <ul class="nav flex-column mb-2 p-0 m-0">
                            <?php if (count($allWorkspaces) > 0): ?>
                                <?php foreach($allWorkspaces as $w): ?>
                                    <?php $isActive = (session()->get('active_workspace_id') == $w['id']); ?>
                                    <li class="nav-item mb-1">
                                        <a class="nav-link d-flex align-items-center <?= $isActive ? 'text-primary fw-bold bg-primary bg-opacity-10 rounded' : 'text-secondary bg-transparent fw-medium' ?>" href="<?= base_url('workspaces/select/' . $w['id']) ?>" style="font-size: 0.85rem; padding: 0.4rem 1rem; margin: 0 0.5rem;">
                                            <?= esc($w['nama_workspace']) ?>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <li class="nav-item">
                                    <span class="nav-link text-muted bg-transparent" style="font-size: 0.85rem; padding: 0.35rem 1rem; margin: 0.1rem 0.5rem;">Belum ada Gudang</span>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </li>
            </ul>
        </nav>

        <div class="main-content">
            <header class="topbar">
                <?php $globalWorkspaceModel = new \App\Models\WorkspaceModel(); ?>
                <?php if (!url_is('workspaces*')): ?>
                <div class="dropdown">
                    <button class="btn btn-white border-primary border-opacity-25 text-primary fw-bold dropdown-toggle shadow-sm" style="background-color: #fff;" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-journal-text me-2"></i><?= esc(session()->get('active_workspace_name') ?? 'Belum ada Gudang') ?>
                    </button>
                    <ul class="dropdown-menu shadow">
                        <li><h6 class="dropdown-header text-dark fw-bold">Daftar Gudang</h6></li>
                        <?php $allWorkspaces = $globalWorkspaceModel->findAll(); ?>
                        <?php if (count($allWorkspaces) > 0): ?>
                            <?php foreach($allWorkspaces as $w): ?>
                                <li>
                                    <a class="dropdown-item d-flex align-items-center justify-content-between <?= session()->get('active_workspace_id') == $w['id'] ? 'active bg-primary' : '' ?>" href="<?= base_url('workspaces/select/' . $w['id']) ?>">
                                        <span><?= esc($w['nama_workspace']) ?></span>
                                        <?php if (session()->get('active_workspace_id') == $w['id']): ?><i class="bi bi-check2"></i><?php endif; ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <li><span class="dropdown-item text-muted disabled">Gudang Kosong</span></li>
                        <?php endif; ?>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="<?= base_url('workspaces') ?>"><i class="bi bi-buildings me-2"></i>Kelola Gudang</a></li>
                    </ul>
                </div>
                <?php else: ?>
                <div></div>
                <?php endif; ?>
                <div class="dropdown">
                    <button class="btn btn-link dropdown-toggle text-decoration-none text-secondary d-flex align-items-center gap-2" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-person-circle fs-5"></i>
                        <span class="d-none d-lg-inline"><?= session()->get('username') ?></span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow">
                        <li><a class="dropdown-item text-danger" href="<?= base_url('auth/logout') ?>"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
                    </ul>
                </div>
            </header>

            <main class="p-4" style="background-color: #f8f9fc; min-height: calc(100vh - 70px); border-top-left-radius: 1.5rem;">
                
                <?php if (session()->get('active_workspace_id') && (url_is('dashboard*') || url_is('items*') || url_is('activities*'))): ?>
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <ul class="nav nav-pills">
                        <li class="nav-item me-2">
                            <a class="nav-link px-3 py-2 <?= url_is('dashboard*') ? 'active shadow-sm' : 'text-secondary bg-white border border-opacity-50' ?>" href="<?= base_url('dashboard') ?>" style="font-size: 0.9rem;">
                                <i class="bi bi-grid me-1"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item me-2">
                            <a class="nav-link px-3 py-2 <?= url_is('items*') ? 'active shadow-sm' : 'text-secondary bg-white border border-opacity-50' ?>" href="<?= base_url('items') ?>" style="font-size: 0.9rem;">
                                <i class="bi bi-boxes me-1"></i> Data Barang
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link px-3 py-2 <?= url_is('activities*') ? 'active shadow-sm' : 'text-secondary bg-white border border-opacity-50' ?>" href="<?= base_url('activities') ?>" style="font-size: 0.9rem;">
                                <i class="bi bi-clock-history me-1"></i> Riwayat
                            </a>
                        </li>
                    </ul>
                    <?php if (url_is('items') || url_is('items/*')): ?>
                    <a href="<?= base_url('items/create') ?>" class="btn btn-sm btn-primary shadow-sm px-3 py-2" style="font-size: 0.9rem;">
                        <i class="bi bi-plus-lg me-1"></i> Tambah Barang
                    </a>
                    <?php endif; ?>
                </div>
                <?php endif; ?>

                <?= $this->renderSection('content') ?>
            </main>
        </div>
    </div>

    <div class="toast-container position-fixed top-0 end-0 p-4" style="z-index: 1090; margin-top: 20px;">
        <?php if (session()->getFlashdata('success')): ?>
            <div class="toast align-items-center text-bg-success border-0 shadow-lg" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="4000">
                <div class="d-flex">
                    <div class="toast-body d-flex align-items-center fw-medium">
                        <i class="bi bi-check-circle-fill me-2 fs-5"></i>
                        <?= session()->getFlashdata('success') ?>
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="toast align-items-center text-bg-danger border-0 shadow-lg" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="5000">
                <div class="d-flex">
                    <div class="toast-body d-flex align-items-center fw-medium">
                        <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
                        <?= session()->getFlashdata('error') ?>
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var toastElList = [].slice.call(document.querySelectorAll('.toast'));
            var toastList = toastElList.map(function (toastEl) {
                return new bootstrap.Toast(toastEl);
            });
            toastList.forEach(toast => toast.show());
        });
    </script>
</body>
</html>
