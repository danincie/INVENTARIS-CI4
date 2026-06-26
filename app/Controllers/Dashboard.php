<?php

namespace App\Controllers;

use App\Models\ItemModel;

class Dashboard extends BaseController
{
    public function index()
    {
        if ($this->request->getGet('src') == 'sidebar') {
            session()->set('sidebar_active', 'dashboard');
            return redirect()->to('/dashboard');
        }

        $workspaceId = session()->get('active_workspace_id');
        
        if (!$workspaceId) {
            return redirect()->to('/workspaces')->with('error', 'Silakan buat Gudang pertama Anda terlebih dahulu.');
        }

        $itemModel = new ItemModel();
        $categoryModel = new \App\Models\CategoryModel();
        
        $total_items = 0;
        $total_value = 0;
        
        $allItems = $itemModel->select('items.*, categories.nama_kategori as kategori')
                              ->join('categories', 'categories.id = items.category_id', 'left')
                              ->where('items.workspace_id', $workspaceId)
                              ->findAll();
        
        // Kumpulkan data yang lebih detail
        $lowStockItems = [];
        
        foreach($allItems as $item) {
            $total_items += $item['jumlah']; // Total unit fisik
            $total_value += ($item['harga'] * $item['jumlah']);
            
            if ($item['jumlah'] < 5) {
                $lowStockItems[] = $item;
            }
        }
        
        $categoriesList = $categoryModel->where('workspace_id', $workspaceId)->findAll();
        $total_categories = count($categoriesList);
        
        // Filter & Sortir
        $sort = $this->request->getGet('sort') ?? 'terbaru';
        $filter_kategori = $this->request->getGet('category_id');
        
        $query = $itemModel->select('items.*, categories.nama_kategori as kategori')
                           ->join('categories', 'categories.id = items.category_id', 'left')
                           ->where('items.workspace_id', $workspaceId);
        
        if (!empty($filter_kategori)) {
            $query->where('items.category_id', $filter_kategori);
        }
        
        switch ($sort) {
            case 'stok_terbanyak':
                $query->orderBy('items.jumlah', 'DESC');
                break;
            case 'stok_terdikit':
                $query->orderBy('items.jumlah', 'ASC');
                break;
            case 'harga_termahal':
                $query->orderBy('items.harga', 'DESC');
                break;
            case 'harga_termurah':
                $query->orderBy('items.harga', 'ASC');
                break;
            case 'terlama':
                $query->orderBy('items.id', 'ASC');
                break;
            case 'terbaru':
            default:
                $query->orderBy('items.id', 'DESC');
                break;
        }
        
        // Ambil barang untuk ditampilkan di tabel bawah
        $recentItems = $query->findAll(10);
        
        $data = [
            'title' => 'Dashboard - Inventaris',
            'total_items' => $total_items,
            'total_value' => $total_value,
            'total_categories' => $total_categories,
            'categories_list' => $categoriesList,
            'low_stock_count' => count($lowStockItems),
            'recent_items' => $recentItems,
            'current_sort' => $sort,
            'current_kategori' => $filter_kategori
        ];

        return view('dashboard/index', $data);
    }
}
