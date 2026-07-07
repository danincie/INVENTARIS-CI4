<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterItemsCategory extends Migration
{
    public function up()
    {
        $this->forge->addColumn('items', [
            'category_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
        ]);

        $db = \Config\Database::connect();
        
        $query = $db->query("SELECT DISTINCT workspace_id, kategori FROM items WHERE kategori IS NOT NULL AND kategori != ''");
        $results = $query->getResultArray();
        
        $categoryMap = [];
        foreach ($results as $row) {
            $workspaceId = $row['workspace_id'];
            $kategoriName = $row['kategori'];
            
            $db->table('categories')->insert([
                'workspace_id'  => $workspaceId,
                'nama_kategori' => $kategoriName,
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s'),
            ]);
            
            $catId = $db->insertID();
            if (!isset($categoryMap[$workspaceId])) {
                $categoryMap[$workspaceId] = [];
            }
            $categoryMap[$workspaceId][$kategoriName] = $catId;
        }

        $itemsQuery = $db->table('items')->get();
        foreach ($itemsQuery->getResultArray() as $item) {
            $wId = $item['workspace_id'];
            $kName = $item['kategori'];
            if (!empty($kName) && isset($categoryMap[$wId][$kName])) {
                $db->table('items')->where('id', $item['id'])->update([
                    'category_id' => $categoryMap[$wId][$kName]
                ]);
            }
        }

        $this->forge->dropColumn('items', 'kategori');
        
        $db->query('ALTER TABLE `items` ADD CONSTRAINT `items_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories`(`id`) ON DELETE SET NULL ON UPDATE CASCADE');
    }

    public function down()
    {
        $this->forge->addColumn('items', [
            'kategori' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
            ]
        ]);
        
        $db = \Config\Database::connect();
        $db->query('ALTER TABLE `items` DROP FOREIGN KEY `items_category_id_foreign`');

        $query = $db->query("SELECT items.id, categories.nama_kategori FROM items JOIN categories ON items.category_id = categories.id");
        foreach($query->getResultArray() as $row) {
            $db->table('items')->where('id', $row['id'])->update(['kategori' => $row['nama_kategori']]);
        }
        
        $this->forge->dropColumn('items', 'category_id');
    }
}
