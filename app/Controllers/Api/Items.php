<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use App\Models\ItemModel;

class Items extends ResourceController
{
    protected $modelName = 'App\Models\ItemModel';
    protected $format    = 'json';

    public function index()
    {
        // Join dengan tabel categories agar response API menampilkan nama_kategori
        $data = $this->model->select('items.*, categories.nama_kategori')
                            ->join('categories', 'categories.id = items.category_id', 'left')
                            ->findAll();
        return $this->respond($data);
    }

    public function show($id = null)
    {
        $data = $this->model->select('items.*, categories.nama_kategori')
                            ->join('categories', 'categories.id = items.category_id', 'left')
                            ->find($id);
        if ($data) {
            return $this->respond($data);
        }
        return $this->failNotFound('Barang tidak ditemukan.');
    }

    public function create()
    {
        // Tambahan validasi untuk workspace_id karena diperlukan oleh database
        $rules = $this->model->getValidationRules();
        $rules['workspace_id'] = 'required|is_natural_no_zero';
        
        if (!$this->validate($rules)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $data = [
            'workspace_id'=> $this->request->getVar('workspace_id'),
            'nama_barang' => $this->request->getVar('nama_barang'),
            'category_id' => $this->request->getVar('category_id'),
            'jumlah'      => $this->request->getVar('jumlah'),
            'harga'       => $this->request->getVar('harga'),
            'deskripsi'   => $this->request->getVar('deskripsi'),
        ];

        $this->model->insert($data);
        $data['id'] = $this->model->getInsertID();

        return $this->respondCreated($data, 'Barang berhasil ditambahkan.');
    }

    public function update($id = null)
    {
        $rules = $this->model->getValidationRules();

        // ngambil inputan dari method PUT
        $input = $this->request->getRawInput();
        
        if (!$this->validate($rules)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $data = [
            'nama_barang' => $input['nama_barang'] ?? $this->request->getVar('nama_barang'),
            'category_id' => $input['category_id'] ?? $this->request->getVar('category_id'),
            'jumlah'      => $input['jumlah'] ?? $this->request->getVar('jumlah'),
            'harga'       => $input['harga'] ?? $this->request->getVar('harga'),
            'deskripsi'   => $input['deskripsi'] ?? $this->request->getVar('deskripsi'),
        ];
        
        if (isset($input['workspace_id']) || $this->request->getVar('workspace_id')) {
            $data['workspace_id'] = $input['workspace_id'] ?? $this->request->getVar('workspace_id');
        }

        if ($this->model->find($id)) {
            $this->model->update($id, $data);
            return $this->respond($data, 200, 'Barang berhasil diupdate.');
        }

        return $this->failNotFound('Barang tidak ditemukan.');
    }

    public function delete($id = null)
    {
        $data = $this->model->find($id);
        if ($data) {
            $this->model->delete($id);
            return $this->respondDeleted($data, 'Barang berhasil dihapus.');
        }

        return $this->failNotFound('Barang tidak ditemukan.');
    }
}
