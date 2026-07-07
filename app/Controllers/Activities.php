<?php

namespace App\Controllers;

use App\Models\ActivityModel;

class Activities extends BaseController
{
    public function index()
    {
        $workspaceId = session()->get('active_workspace_id');
        if (!$workspaceId) return redirect()->to('/workspaces')->with('error', 'Silakan buat Gudang pertama Anda terlebih dahulu.');
        
        $activityModel = new ActivityModel();
        
        $perPage = 20;
        $activities = $activityModel->where('workspace_id', $workspaceId)
                                    ->orderBy('created_at', 'DESC')
                                    ->paginate($perPage, 'activities');
                                    
        $pager = $activityModel->pager;

        $data = [
            'title'      => 'Riwayat Aktivitas - Inventaris',
            'activities' => $activities,
            'pager'      => $pager
        ];

        return view('activities/index', $data);
    }

    public function delete(int|string $id)
    {
        $workspaceId = session()->get('active_workspace_id');
        if (!$workspaceId) return redirect()->to('/workspaces')->with('error', 'Silakan buat Gudang pertama Anda terlebih dahulu.');
        
        $activityModel = new ActivityModel();
        $activity = $activityModel->where('workspace_id', $workspaceId)->find($id);
        
        if ($activity) {
            $activityModel->delete($id);
            session()->setFlashdata('success', 'Riwayat aktivitas berhasil dihapus.');
        }
        
        return redirect()->to('/activities');
    }
}
