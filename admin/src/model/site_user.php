<?php
require_once __DIR__ . '/model.php';
require_once __DIR__ . '/exceptions.php';

class SiteUser
{
    public int $id;
    public string $full_name;
    public string $email;
    public string $password;
    public ?string $phone_number;
    public DateTime $created_at;
    public DateTime $updated_at;
    public ?DateTime $deleted_at;

    public function __construct(array $row)
    {
        $this->id = $row['id'];
        $this->full_name = $row['full_name'];
        $this->email = $row['email'];
        $this->password = $row['password'];
        $this->phone_number = $row['phone_number'];
        $this->created_at = new DateTime($row['created_at']);
        $this->updated_at = new DateTime($row['updated_at']);
        $this->deleted_at = isset($row['deleted_at']) ? new DateTime($row['deleted_at']) : null;
    }
}

class SiteUserModel extends Model
{
    public function __construct(mysqli $db)
    {
        parent::__construct($db, 'site_users', SiteUser::class, SiteUserExceptionsEnum::class);
    }

    public function create(string $full_name, string $email, string $password, ?string $phone_number): int
    {
        $fields = [
            'full_name' => $full_name,
            'email' => $email,
            'password' => $this->encrypt_password($password),
            'phone_number' => $phone_number
        ];
        return $this->insert($fields);
    }

    public function modify(int $id, ?string $full_name, ?string $email, ?string $password, ?string $phone_number): void
    {
        $fields = [
            'full_name' => $full_name,
            'email' => $email,
            'password' => $this->encrypt_password($password),
            'phone_number' => $phone_number
        ];
        $this->update($id, $fields);
    }

    public function list_filtered(?string $search = null, ?int $limit = 15, ?int $offset = null): array
    {
        $params = [];
        $types = '';
        $query = "SELECT * FROM $this->table_name WHERE deleted_at IS NULL";

        if ($search) {
            $query .= " AND (full_name LIKE ? OR email LIKE ?)";
            $params[] = "%$search%";
            $params[] = "%$search%";
            $types .= 'ss';
        }

        $query .= " ORDER BY full_name ASC";
        return $this->select($query, $params, $types, $limit, $offset);
    }

    public function count_filtered(?string $search = null): int
    {
        $params = [];
        $types = '';
        $query = "SELECT COUNT(id) as total FROM $this->table_name WHERE deleted_at IS NULL";

        if ($search) {
            $query .= " AND (full_name LIKE ? OR email LIKE ?)";
            $params[] = "%$search%";
            $params[] = "%$search%";
            $types .= 'ss';
        }

        return $this->count($query, $params, $types);
    }

    public function count_today(): int
    {
        $query = "SELECT COUNT(id) as total FROM $this->table_name WHERE DATE(created_at) = CURDATE() AND deleted_at IS NULL";
        return $this->count($query);
    }

    public function retrieve_by_email(string $email): ?SiteUser
    {
        $result = $this->select("SELECT * FROM $this->table_name WHERE email=? AND deleted_at IS NULL", [$email]);
        return !empty($result) ? $result[0] : null;
    }

    public function email_exists(string $email): bool
    {
        return $this->exists("SELECT id FROM $this->table_name WHERE email=? AND deleted_at IS NULL", [$email]);
    }
}
