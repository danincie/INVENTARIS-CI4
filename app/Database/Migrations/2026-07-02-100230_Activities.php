<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Activities extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'workspace_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'item_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'action' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
            ],
            'description' => [
                'type'       => 'TEXT',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('workspace_id');
        $this->forge->addKey('item_id');
        
        $this->forge->addForeignKey('workspace_id', 'workspaces', 'id', 'CASCADE', 'CASCADE');
        // We don't strictly cascade on item_id because we want to keep the history even if item is deleted.
        // We can just set it to null or leave it. Actually, if we set null, we need the foreign key to SET NULL.
        // Let's just make it a normal column without a strict foreign key constraint for item_id, so the log persists if the item is deleted.

        $this->forge->createTable('activities');
    }

    public function down()
    {
        $this->forge->dropTable('activities');
    }
}
