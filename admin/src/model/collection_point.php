<?php
require_once __DIR__ . '/model.php';
require_once __DIR__ . '/exceptions.php';

class CollectionPoint
{
    public int $id;
    public string $name;
    public string $street;
    public ?string $number;
    public string $neighborhood;
    public string $city;
    public string $state;
    public string $accepted_materials;
    public string $category;
    public ?string $Maps_link;
    public DateTime $created_at;
    public DateTime $updated_at;
    public ?DateTime $deleted_at;

    public function __construct(array $row)
    {
        $this->id = $row['id'];
        $this->name = $row['name'];
        $this->street = $row['street'];
        $this->number = $row['number'];
        $this->neighborhood = $row['neighborhood'];
        $this->city = $row['city'];
        $this->state = $row['state'];
        $this->accepted_materials = $row['accepted_materials'];
        $this->category = $row['category'];
        $this->Maps_link = $row['Maps_link'];
        $this->created_at = new DateTime($row['created_at']);
        $this->updated_at = new DateTime($row['updated_at']);
        $this->deleted_at = isset($row['deleted_at']) ? new DateTime($row['deleted_at']) : null;
    }

    public function getFullAddress(): string
    {
        $addressParts = [$this->street];
        if ($this->number) $addressParts[] = $this->number;
        if ($this->neighborhood) $addressParts[] = $this->neighborhood;
        if ($this->city) $addressParts[] = $this->city;
        if ($this->state) $addressParts[] = $this->state;
        return implode(', ', $addressParts);
    }
}

class CollectionPointModel extends Model
{
    public function __construct(mysqli $db)
    {
        parent::__construct($db, 'collection_points', CollectionPoint::class, CollectionPointExceptionsEnum::class, true);
    }

    private function generateMapsLink(string $name, string $street, ?string $number, string $neighborhood, string $city, string $state): string
    {

        $addressParts = [$name, $street, $number, $neighborhood, $city, $state];
        $addressString = implode(', ', array_filter($addressParts));
        return 'https://www.google.com/maps/search/?api=1&query=' . urlencode($addressString);
    }

    public function create(string $name, string $street, ?string $number, string $neighborhood, string $city, string $state, string $accepted_materials, string $category): int
    {
        $maps_link = $this->generateMapsLink($name, $street, $number, $neighborhood, $city, $state);

        $fields = [
            'name' => $name,
            'street' => $street,
            'number' => $number,
            'neighborhood' => $neighborhood,
            'city' => $city,
            'state' => $state,
            'accepted_materials' => $accepted_materials,
            'category' => $category,
            'Maps_link' => $maps_link,
        ];
        return $this->insert($fields);
    }

    public function modify(int $id, ?string $name, ?string $street, ?string $number, ?string $neighborhood, ?string $city, ?string $state, ?string $accepted_materials, ?string $category): void
    {
        $maps_link = $this->generateMapsLink($name, $street, $number, $neighborhood, $city, $state);

        $fields = [
            'name' => $name,
            'street' => $street,
            'number' => $number,
            'neighborhood' => $neighborhood,
            'city' => $city,
            'state' => $state,
            'accepted_materials' => $accepted_materials,
            'category' => $category,
            'Maps_link' => $maps_link,
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
}
