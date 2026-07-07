<?php

namespace App\Controllers;

use App\Models\WorkspaceModel;

class Workspace extends BaseController
{
    protected \App\Models\WorkspaceModel $workspaceModel;

    public function __construct()
    {
        $this->workspaceModel = new WorkspaceModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Pilih Gudang - Inventaris',
            'workspaces' => $this->workspaceModel->findAll()
        ];

        return view('workspaces/index', $data);
    }

    public function select(int|string $id)
    {
        $workspace = $this->workspaceModel->find($id);
        if ($workspace) {
            session()->set('active_workspace_id', $workspace['id']);
            session()->set('active_workspace_name', $workspace['nama_workspace']);
            session()->set('sidebar_active', 'workspace');
            return redirect()->to('/dashboard');
        }
        return redirect()->back()->with('error', 'Gudang tidak ditemukan.');
    }

    public function store()
    {
        if (!$this->validate([
            'nama_workspace' => 'required|min_length[3]'
        ])) {
            return redirect()->back()->withInput()->with('validation', \Config\Services::validation());
        }

        $this->workspaceModel->save([
            'nama_workspace' => $this->request->getPost('nama_workspace'),
            'deskripsi' => $this->request->getPost('deskripsi')
        ]);

        return redirect()->back()->with('success', 'Gudang baru berhasil dibuat.');
    }

    public function update(int|string $id)
    {
        if (!$this->validate([
            'nama_workspace' => 'required|min_length[3]'
        ])) {
            return redirect()->back()->with('error', 'Nama gudang tidak valid (minimal 3 karakter).');
        }

        $this->workspaceModel->update($id, [
            'nama_workspace' => $this->request->getPost('nama_workspace'),
            'deskripsi' => $this->request->getPost('deskripsi')
        ]);

        if (session()->get('active_workspace_id') == $id) {
            session()->set('active_workspace_name', $this->request->getPost('nama_workspace'));
        }

        return redirect()->back()->with('success', 'Gudang berhasil diubah.');
    }

    public function delete(int|string $id)
    {
        $this->workspaceModel->delete($id);

        if (session()->get('active_workspace_id') == $id) {
            $otherWorkspace = $this->workspaceModel->first();
            
            if ($otherWorkspace) {
                session()->set('active_workspace_id', $otherWorkspace['id']);
                session()->set('active_workspace_name', $otherWorkspace['nama_workspace']);
            } else {
                session()->remove('active_workspace_id');
                session()->remove('active_workspace_name');
            }
        }

        return redirect()->to('/workspaces')->with('success', 'Gudang berhasil dihapus beserta seluruh barang di dalamnya.');
    }
}
