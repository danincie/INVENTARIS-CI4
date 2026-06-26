<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddWorkspaceIdToItems extends Migration
{
    public function up()
    {
        $this->forge->addColumn('items', [
            'workspace_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'id'
            ],
        ]);
        
        $this->db->query("ALTER TABLE items ADD CONSTRAINT items_workspace_id_foreign FOREIGN KEY (workspace_id) REFERENCES workspaces(id) ON DELETE CASCADE ON UPDATE CASCADE");
    }

    public function down()
    {
        $this->db->query("ALTER TABLE items DROP FOREIGN KEY items_workspace_id_foreign");
        $this->forge->dropColumn('items', 'workspace_id');
    }
}
