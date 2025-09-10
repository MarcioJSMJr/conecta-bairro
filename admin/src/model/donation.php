<?php
require_once __DIR__ . '/model.php';
require_once __DIR__ . '/exceptions.php';

class Donation
{
    public int $id;
    public int $user_id;
    public int $category_id;
    public string $title;
    public string $description;
    public string $slug;
    public ?string $image_url;
    public string $condition;
    public string $status;
    public string $neighborhood;
    public ?string $category_name;
    public ?string $user_phone_number;
    public DateTime $created_at;
    public DateTime $updated_at;
    public ?DateTime $deleted_at;

    public function __construct(array $row)
    {
        $this->id = $row['id'];
        $this->user_id = $row['user_id'];
        $this->category_id = $row['category_id'];
        $this->title = $row['title'];
        $this->description = $row['description'];
        $this->slug = $row['slug'];
        $this->image_url = $row['image_url'];
        $this->condition = $row['condition'];
        $this->status = $row['status'];
        $this->neighborhood = $row['neighborhood'];
        $this->category_name = $row['category_name'] ?? null;
        $this->user_phone_number = $row['user_phone_number'] ?? null;
        $this->created_at = new DateTime($row['created_at']);
        $this->updated_at = new DateTime($row['updated_at']);
        $this->deleted_at = isset($row['deleted_at']) ? new DateTime($row['deleted_at']) : null;
    }
}

class DonationModel extends Model
{
    public function __construct(mysqli $db)
    {
        parent::__construct($db, 'donations', Donation::class, DonationExceptionsEnum::class);
    }

    public function create(
        int $user_id,
        int $category_id,
        string $title,
        string $description,
        string $slug,
        string $condition,
        string $status,
        string $neighborhood,
        array $image
    ): int {

        $physical_upload_path = ADMIN_ROOT . '/media/donations/';

        $public_path_for_db = 'media/donations/';

        $new_filename = $this->upload_file($physical_upload_path, $image);
        if ($new_filename === null) {
            throw new ModelException(ModelExceptionsEnum::FILE_UPLOAD_FAILED);
        }

        $fields = [
            'user_id' => $user_id,
            'category_id' => $category_id,
            'title' => $title,
            'description' => $description,
            'slug' => $slug,
            'condition' => $condition,
            'status' => $status,
            'neighborhood' => $neighborhood,
            'image_url' => $public_path_for_db . $new_filename,
        ];

        return $this->insert($fields);
    }

    public function modify(
        int $id,
        ?int $category_id,
        ?string $title,
        ?string $description,
        ?string $slug,
        ?string $condition,
        ?string $status,
        ?string $neighborhood,
        ?array $image
    ): void {

        $image_path_for_db = null;
        if ($image !== null && $image['error'] === UPLOAD_ERR_OK) {
            $instance = $this->retrieve($id);

            $physical_upload_path = ADMIN_ROOT . '/media/donations/';
            $public_path_for_db = 'media/donations/';

            $new_filename = $this->upload_file($physical_upload_path, $image);
            if ($new_filename === null) {
                throw new ModelException(ModelExceptionsEnum::FILE_UPLOAD_FAILED);
            }

            if ($instance->image_url) {
                $old_image_physical_path = ADMIN_ROOT . '/media/' . $instance->image_url;
                if (file_exists($old_image_physical_path)) {
                    unlink($old_image_physical_path);
                }
            }

            $image_path_for_db = $public_path_for_db . $new_filename;
        }

        $fields = [
            'category_id' => $category_id,
            'title' => $title,
            'description' => $description,
            'slug' => $slug,
            'condition' => $condition,
            'status' => $status,
            'neighborhood' => $neighborhood,
            'image_url' => $image_path_for_db,
        ];

        $this->update($id, $fields);
    }

    public function list(?int $limit = 15, ?int $offset = null): array
    {
        $query = "SELECT d.*, c.name AS category_name 
                  FROM $this->table_name d
                  LEFT JOIN donation_categories c ON d.category_id = c.id";
        if ($this->uses_soft_delete) {
            $query .= " WHERE d.deleted_at IS NULL";
        }
        $query .= " ORDER BY d.id DESC";
        return $this->select($query, [], '', $limit, $offset);
    }

    public function retrieve_by_slug(string $slug): ?Donation
    {
        $query = "SELECT d.*, c.name AS category_name, su.phone_number AS user_phone_number
              FROM $this->table_name d
              LEFT JOIN donation_categories c ON d.category_id = c.id
              LEFT JOIN site_users su ON d.user_id = su.id
              WHERE d.slug=? AND d.deleted_at IS NULL";

        $result = $this->select($query, [$slug]);
        return !empty($result) ? $result[0] : null;
    }

    public function list_by_category_slug(string $slug, ?int $limit = 9, ?int $offset = null): array
    {
        return $this->select(
            "SELECT t.* FROM $this->table_name t
            JOIN donation_categories c ON t.category_id=c.id
            WHERE c.slug=? AND t.deleted_at IS NULL ORDER BY id DESC",
            [$slug],
            $limit,
            $offset
        );
    }

    public function list_filtered(?string $search = null, ?int $category_id = null, ?int $limit = 15, ?int $offset = null): array
    {
        $params = [];
        $types = '';

        $query = "SELECT d.*, c.name AS category_name 
                  FROM donations d
                  LEFT JOIN donation_categories c ON d.category_id = c.id 
                  WHERE d.deleted_at IS NULL";

        if ($search) {
            $query .= " AND d.title LIKE ?";
            $params[] = "%$search%";
            $types .= 's';
        }
        if ($category_id) {
            $query .= " AND d.category_id = ?";
            $params[] = $category_id;
            $types .= 'i';
        }

        $query .= " ORDER BY d.id DESC";

        return $this->select($query, $params, $types, $limit, $offset);
    }

    public function count_filtered(?string $search = null, ?int $category_id = null): int
    {
        $params = [];
        $types = '';

        $query = "SELECT COUNT(d.id) as total 
                  FROM donations d 
                  WHERE d.deleted_at IS NULL";

        if ($search) {
            $query .= " AND d.title LIKE ?";
            $params[] = "%$search%";
            $types .= 's';
        }
        if ($category_id) {
            $query .= " AND d.category_id = ?";
            $params[] = $category_id;
            $types .= 'i';
        }

        return $this->count($query, $params, $types);
    }

    public function count_today(): int
    {
        $query = "SELECT COUNT(id) as total FROM $this->table_name WHERE DATE(created_at) = CURDATE() AND deleted_at IS NULL";
        return $this->count($query);
    }

    public function get_weekly_activity(): array
    {
        $query = "SELECT DATE(created_at) as date, COUNT(id) as count 
                  FROM $this->table_name 
                  WHERE created_at >= CURDATE() - INTERVAL 6 DAY AND deleted_at IS NULL 
                  GROUP BY DATE(created_at) 
                  ORDER BY date ASC";

        $results = $this->db->query($query)->fetch_all(MYSQLI_ASSOC);

        $labels = [];
        $data = [];
        $db_data = array_column($results, 'count', 'date');

        for ($i = 6; $i >= 0; $i--) {
            $date = new DateTime("-$i days");
            $date_key = $date->format('Y-m-d');
            $labels[] = $date->format('d/m');
            $data[] = $db_data[$date_key] ?? 0;
        }

        return ['labels' => $labels, 'data' => $data];
    }

    public function list_by_user_id(int $user_id, ?int $limit = 99, ?int $offset = null): array
    {
        return $this->select(
            "SELECT d.*, c.name as category_name FROM $this->table_name d
            LEFT JOIN donation_categories c ON d.category_id = c.id
            WHERE d.user_id = ? AND d.deleted_at IS NULL ORDER BY d.id DESC",
            [$user_id],
            'i',
            $limit,
            $offset
        );
    }

    public function slug_exists(string $slug): bool
    {
        return $this->exists("SELECT id FROM $this->table_name WHERE slug=? AND deleted_at IS NULL", [$slug]);
    }
}
