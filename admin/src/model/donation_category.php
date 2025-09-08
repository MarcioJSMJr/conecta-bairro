<?php
require_once __DIR__ . '/model.php';
require_once __DIR__ . '/exceptions.php';

class DonationCategory
{
    public int $id;
    public string $name;
    public string $slug;
    public DateTime $created_at;
    public DateTime $updated_at;
    public ?DateTime $deleted_at;

    public function __construct(array $row)
    {
        $this->id = $row['id'];
        $this->name = $row['name'];
        $this->slug = $row['slug'];
        $this->created_at = new DateTime($row['created_at']);
        $this->updated_at = new DateTime($row['updated_at']);
        $this->deleted_at = isset($row['deleted_at']) ? new DateTime($row['deleted_at']) : null;
    }
}

class DonationCategoryModel extends Model
{
    public function __construct(mysqli $db)
    {
        parent::__construct($db, 'donation_categories', DonationCategory::class, DonationCategoryExceptionsEnum::class);
    }

    public function create(string $name, string $slug): int
    {
        $fields = [
            'name' => $name,
            'slug' => $slug
        ];
        return $this->insert($fields);
    }

    public function modify(int $id, ?string $name, ?string $slug): void
    {
        $fields = [
            'name' => $name,
            'slug' => $slug,
        ];
        $this->update($id, $fields);
    }

    public function list_filtered(?string $search = null, ?int $limit = 15, ?int $offset = null): array
    {
        $params = [];
        $types = '';
        $query = "SELECT * FROM $this->table_name WHERE deleted_at IS NULL";

        if ($search) {
            $query .= " AND name LIKE ?";
            $params[] = "%$search%";
            $types .= 's';
        }

        $query .= " ORDER BY name ASC";
        return $this->select($query, $params, $types, $limit, $offset);
    }

    public function count_filtered(?string $search = null): int
    {
        $params = [];
        $types = '';
        $query = "SELECT COUNT(id) as total FROM $this->table_name WHERE deleted_at IS NULL";

        if ($search) {
            $query .= " AND name LIKE ?";
            $params[] = "%$search%";
            $types .= 's';
        }

        return $this->count($query, $params, $types);
    }

    public function retrieve_by_slug(string $slug): ?DonationCategory
    {
        $result = $this->select("SELECT * FROM $this->table_name WHERE slug=? AND deleted_at IS NULL", [$slug], 's');
        return !empty($result) ? $result[0] : null;
    }

    public function list_all(): array
    {
        return $this->select("SELECT * FROM $this->table_name WHERE deleted_at IS NULL ORDER BY name ASC");
    }
}
