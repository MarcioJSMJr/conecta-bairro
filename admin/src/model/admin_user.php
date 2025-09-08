<?php
require_once __DIR__ . '/model.php';
require_once __DIR__ . '/exceptions.php';

class AdminUser
{
    public int $id;
    public string $name;
    public string $username;
    public string $password;
    public string $auth_level;
    public DateTime $created_at;
    public DateTime $updated_at;
    public ?DateTime $deleted_at;

    public function __construct(array $row)
    {
        $this->id = $row['id'];
        $this->name = $row['name'];
        $this->username = $row['username'];
        $this->password = $row['password'];
        $this->auth_level = $row['auth_level'];
        $this->created_at = new DateTime($row['created_at']);
        $this->updated_at = new DateTime($row['updated_at']);
        $this->deleted_at = isset($row['deleted_at']) ? new DateTime($row['deleted_at']) : null;
    }
}

class AdminUserModel extends Model
{
    public function __construct(mysqli $db)
    {
        parent::__construct($db, 'admin_users', AdminUser::class, AdminUserExceptionsEnum::class);
    }

    public function create(string $name, string $username, string $password, string $auth_level): int
    {
        $fields = [
            'name' => $name,
            'username' => $username,
            'password' => $this->encrypt_password($password),
            'auth_level' => $auth_level
        ];
        return $this->insert($fields);
    }

    public function modify(int $id, ?string $name, ?string $username, ?string $password, ?string $auth_level): void
    {
        $fields = [
            'name' => $name,
            'username' => $username,
            'password' => $this->encrypt_password($password),
            'auth_level' => $auth_level
        ];
        $this->update($id, $fields);
    }

    public function retrieve_by_username(string $username): ?AdminUser
    {
        $result = $this->select("SELECT * FROM $this->table_name WHERE username=? AND deleted_at IS NULL", [$username], 's');
        return !empty($result) ? $result[0] : null;
    }

    public function username_exists(string $username): bool
    {
        return $this->exists("SELECT id FROM $this->table_name WHERE username = ? AND deleted_at IS NULL", [$username], 's');
    }

    public function has_super_admin(): bool
    {
        return $this->exists("SELECT id FROM $this->table_name WHERE auth_level = 'super' AND deleted_at IS NULL");
    }

    public function list_filtered(?string $search = null, ?string $role = null, ?int $limit = 15, ?int $offset = null): array
    {
        $params = [];
        $types = '';
        $query = "SELECT * FROM $this->table_name WHERE deleted_at IS NULL";

        if ($search) {
            $query .= " AND (name LIKE ? OR username LIKE ?)";
            $params[] = "%$search%";
            $params[] = "%$search%";
            $types .= 'ss';
        }
        if ($role) {
            $query .= " AND auth_level = ?";
            $params[] = $role;
            $types .= 's';
        }

        $query .= " ORDER BY name ASC";
        return $this->select($query, $params, $types, $limit, $offset);
    }

    public function count_filtered(?string $search = null, ?string $role = null): int
    {
        $params = [];
        $types = '';
        $query = "SELECT COUNT(id) as total FROM $this->table_name WHERE deleted_at IS NULL";

        if ($search) {
            $query .= " AND (name LIKE ? OR username LIKE ?)";
            $params[] = "%$search%";
            $params[] = "%$search%";
            $types .= 'ss';
        }
        if ($role) {
            $query .= " AND auth_level = ?";
            $params[] = $role;
            $types .= 's';
        }

        return $this->count($query, $params, $types);
    }
}
