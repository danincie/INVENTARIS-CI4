<?php

namespace App\Controllers;

use App\Models\ItemModel;

class Items extends BaseController
{
    protected \App\Models\ItemModel $itemModel;

    public function __construct()
    {
        $this->itemModel = new ItemModel();
    }

    public function index()
    {
        $workspaceId = session()->get('active_workspace_id');
        if (!$workspaceId) return redirect()->to('/workspaces')->with('error', 'Silakan buat Gudang pertama Anda terlebih dahulu.');
        
        $search = $this->request->getGet('search');
        
        $query = $this->itemModel
            ->select('items.*, categories.nama_kategori as kategori')
            ->join('categories', 'categories.id = items.category_id', 'left')
            ->where('items.workspace_id', $workspaceId);
            
        if (!empty($search)) {
            $query->groupStart()
                  ->like('items.nama_barang', $search)
                  ->orLike('categories.nama_kategori', $search)
                  ->groupEnd();
        }
            
        $items = $query->findAll();

        $data = [
            'title' => 'Data Barang - Inventaris',
            'items' => $items,
            'search' => $search
        ];
        return view('items/index', $data);
    }

    public function create()
    {
        if (!session()->get('active_workspace_id')) return redirect()->to('/workspaces')->with('error', 'Silakan buat Gudang terlebih dahulu.');
        
        $categoryModel = new \App\Models\CategoryModel();
        $data = [
            'title' => 'Tambah Barang - Inventaris',
            'categories' => $categoryModel->where('workspace_id', session()->get('active_workspace_id'))->findAll()
        ];
        return view('items/create', $data);
    }

    public function store()
    {
        $workspaceId = session()->get('active_workspace_id');
        if (!$workspaceId) return redirect()->to('/workspaces')->with('error', 'Silakan buat Gudang terlebih dahulu.');
        
        $postData = $this->request->getPost();
        $categoryIdInput = $postData['category_id'] ?? '';
        
        if (!empty($categoryIdInput) && !is_numeric($categoryIdInput)) {
            $categoryModel = new \App\Models\CategoryModel();
            $existing = $categoryModel->where('workspace_id', $workspaceId)
                                      ->where('nama_kategori', $categoryIdInput)
                                      ->first();
            if ($existing) {
                $postData['category_id'] = $existing['id'];
            } else {
                $categoryModel->insert([
                    'workspace_id' => $workspaceId,
                    'nama_kategori' => $categoryIdInput
                ]);
                $postData['category_id'] = $categoryModel->getInsertID();
            }
        }

        if (!$this->validateData($postData, $this->itemModel->getValidationRules())) {
            $categoryModel = new \App\Models\CategoryModel();
            return view('items/create', [
                'validation' => $this->validator,
                'title' => 'Tambah Barang - Inventaris',
                'categories' => $categoryModel->where('workspace_id', $workspaceId)->findAll()
            ]);
        }

        $this->itemModel->save([
            'workspace_id' => $workspaceId,
            'nama_barang'  => $postData['nama_barang'],
            'category_id'  => $postData['category_id'],
            'jumlah'       => $postData['jumlah'],
            'harga'        => $postData['harga'],
            'deskripsi'    => $postData['deskripsi'],
        ]);
        
        $itemId = $this->itemModel->getInsertID();
        $activityModel = new \App\Models\ActivityModel();
        $activityModel->logActivity($workspaceId, $itemId, 'Tambah', "Menambahkan barang baru: {$postData['nama_barang']} (Stok: {$postData['jumlah']})");

        session()->setFlashdata('success', 'Barang berhasil ditambahkan.');
        return redirect()->to('/items');
    }

    public function edit(int|string $id)
    {
        $workspaceId = session()->get('active_workspace_id');
        if (!$workspaceId) return redirect()->to('/workspaces')->with('error', 'Silakan buat Gudang terlebih dahulu.');
        
        $categoryModel = new \App\Models\CategoryModel();
        $data = [
            'title' => 'Edit Barang - Inventaris',
            'item'  => $this->itemModel->where('workspace_id', $workspaceId)->find($id),
            'categories' => $categoryModel->where('workspace_id', $workspaceId)->findAll()
        ];

        if (empty($data['item'])) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Barang tidak ditemukan.');
        }

        return view('items/edit', $data);
    }

    public function update(int|string $id)
    {
        $workspaceId = session()->get('active_workspace_id');
        if (!$workspaceId) return redirect()->to('/workspaces')->with('error', 'Silakan buat Gudang terlebih dahulu.');
        
        $postData = $this->request->getPost();
        $categoryIdInput = $postData['category_id'] ?? '';
        
        if (!empty($categoryIdInput) && !is_numeric($categoryIdInput)) {
            $categoryModel = new \App\Models\CategoryModel();
            $existing = $categoryModel->where('workspace_id', $workspaceId)
                                      ->where('nama_kategori', $categoryIdInput)
                                      ->first();
            if ($existing) {
                $postData['category_id'] = $existing['id'];
            } else {
                $categoryModel->insert([
                    'workspace_id' => $workspaceId,
                    'nama_kategori' => $categoryIdInput
                ]);
                $postData['category_id'] = $categoryModel->getInsertID();
            }
        }
        
        if (!$this->validateData($postData, $this->itemModel->getValidationRules())) {
            $categoryModel = new \App\Models\CategoryModel();
            return view('items/edit', [
                'validation' => $this->validator,
                'title' => 'Edit Barang - Inventaris',
                'item'  => $this->itemModel->where('workspace_id', $workspaceId)->find($id),
                'categories' => $categoryModel->where('workspace_id', $workspaceId)->findAll()
            ]);
        }
        
        $item = $this->itemModel->where('workspace_id', $workspaceId)->find($id);
        if (!$item) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Barang tidak ditemukan.');
        }

        $changes = [];
        if ($item['jumlah'] != $postData['jumlah']) {
            $changes[] = "stok dari {$item['jumlah']} ke {$postData['jumlah']}";
        }
        if ($item['harga'] != $postData['harga']) {
            $changes[] = "harga dari Rp" . number_format($item['harga'], 0, ',', '.') . " ke Rp" . number_format($postData['harga'], 0, ',', '.');
        }
        if ($item['nama_barang'] != $postData['nama_barang']) {
            $changes[] = "nama dari '{$item['nama_barang']}' ke '{$postData['nama_barang']}'";
        }
        
        $desc = "Memperbarui data barang: {$postData['nama_barang']}";
        if (count($changes) > 0) {
            $desc .= " (" . implode(", ", $changes) . ")";
        }

        $this->itemModel->update($id, [
            'nama_barang' => $postData['nama_barang'],
            'category_id' => $postData['category_id'],
            'jumlah'      => $postData['jumlah'],
            'harga'       => $postData['harga'],
            'deskripsi'   => $postData['deskripsi'],
        ]);
        
        $activityModel = new \App\Models\ActivityModel();
        $activityModel->logActivity($workspaceId, $id, 'Ubah', $desc);

        session()->setFlashdata('success', 'Barang berhasil diupdate.');
        return redirect()->to('/items');
    }

    public function delete(int|string $id)
    {
        $workspaceId = session()->get('active_workspace_id');
        if (!$workspaceId) return redirect()->to('/workspaces')->with('error', 'Silakan buat Gudang terlebih dahulu.');
        
        $item = $this->itemModel->where('workspace_id', $workspaceId)->find($id);
        
        if ($item) {
            $this->itemModel->delete($id);
            
            $activityModel = new \App\Models\ActivityModel();
            $activityModel->logActivity($workspaceId, $id, 'Hapus', "Menghapus barang: {$item['nama_barang']}");
            
            session()->setFlashdata('success', 'Barang berhasil dihapus.');
        }
        return redirect()->to('/items');
    }
}
