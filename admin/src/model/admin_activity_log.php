<?php
require_once __DIR__ . '/model.php';


class AdminActivityLogModel extends Model
{
    public function __construct(mysqli $db)
    {
        parent::__construct($db, 'admin_activity_logs', AdminActivityLogEntry::class, '', false);
    }

    public function list_filtered(?string $search = null, ?string $action_type = null, ?int $limit = 20, ?int $offset = null): array
    {
        $params = [];
        $types = '';
        $query = "SELECT * FROM $this->table_name WHERE 1=1";

        if ($search) {
            $query .= " AND (admin_user_name LIKE ? OR action LIKE ?)";
            $params[] = "%$search%";
            $params[] = "%$search%";
            $types .= 'ss';
        }

        if ($action_type) {
            $query .= " AND action LIKE ?";
            $params[] = "%$action_type%";
            $types .= 's';
        }

        $query .= " ORDER BY created_at DESC";
        return $this->select($query, $params, $types, $limit, $offset);
    }

    public function count_filtered(?string $search = null, ?string $action_type = null): int
    {
        $params = [];
        $types = '';
        $query = "SELECT COUNT(id) as total FROM $this->table_name WHERE 1=1";

        if ($search) {
            $query .= " AND (admin_user_name LIKE ? OR action LIKE ?)";
            $params[] = "%$search%";
            $params[] = "%$search%";
            $types .= 'ss';
        }

        if ($action_type) {
            $query .= " AND action LIKE ?";
            $params[] = "%$action_type%";
            $types .= 's';
        }

        return $this->count($query, $params, $types);
    }
}

class AdminActivityLogEntry
{
    public int $id;
    public ?int $admin_user_id;
    public string $admin_user_name;
    public string $action;
    public ?string $target_type;
    public ?int $target_id;
    public ?string $details;
    public ?string $ip_address;
    public DateTime $created_at;

    public function __construct(array $row)
    {
        $this->id = $row['id'];
        $this->admin_user_id = $row['admin_user_id'];
        $this->admin_user_name = $row['admin_user_name'];
        $this->action = $row['action'];
        $this->target_type = $row['target_type'];
        $this->target_id = $row['target_id'];
        $this->details = $row['details'];
        $this->ip_address = $row['ip_address'];
        $this->created_at = new DateTime($row['created_at']);
    }
}
