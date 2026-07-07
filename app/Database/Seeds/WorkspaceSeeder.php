<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class WorkspaceSeeder extends Seeder
{
    public function run()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('workspaces');
        
        if ($builder->countAllResults() == 0) {
            $data = [
                'nama_workspace' => 'Gudang Utama (Default)',
                'deskripsi' => 'Gudang bawaan sistem tempat semua barang awal Anda berada.',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ];
            $builder->insert($data);
            $workspaceId = $db->insertID();
            
            $db->table('items')->update(['workspace_id' => $workspaceId], "workspace_id IS NULL");
        }
    }
}
