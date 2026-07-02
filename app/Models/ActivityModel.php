<?php

namespace App\Models;

use CodeIgniter\Model;

class ActivityModel extends Model
{
    protected $table            = 'activities';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['workspace_id', 'item_id', 'action', 'description', 'created_at'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = ''; // No updated_at needed for append-only log

    public function logActivity(int|string $workspaceId, int|string|null $itemId, string $action, string $description)
    {
        return $this->insert([
            'workspace_id' => $workspaceId,
            'item_id'      => $itemId,
            'action'       => $action,
            'description'  => $description
        ]);
    }
}
