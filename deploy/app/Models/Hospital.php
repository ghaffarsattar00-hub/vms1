<?php
class Hospital {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAll(): array {
        return $this->db->query("SELECT * FROM hospitals ORDER BY name ASC")->fetchAll();
    }

    public function findById(int $id): ?array {
        $stmt = $this->db->prepare("SELECT * FROM hospitals WHERE hospital_id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        $h = $stmt->fetch();
        return $h ?: null;
    }

    public function findByUserId(int $userId): ?array {
        $stmt = $this->db->prepare("SELECT h.* FROM hospitals h JOIN hospital_users hu ON h.hospital_id = hu.hospital_id WHERE hu.user_id = :uid AND hu.is_active = 1 LIMIT 1");
        $stmt->execute(['uid' => $userId]);
        $h = $stmt->fetch();
        return $h ?: null;
    }

    public function create(array $data): int {
        $stmt = $this->db->prepare("INSERT INTO hospitals (name, registration_number, email, phone, address_line1, city, state, postal_code, is_active, created_by) VALUES (:name, :reg, :email, :phone, :addr, :city, :state, :zip, 1, :created_by)");
        $stmt->execute([
            'name'       => $data['name'],
            'reg'        => $data['registration_number'] ?? '',
            'email'      => $data['email'] ?? '',
            'phone'      => $data['phone'] ?? '',
            'addr'       => $data['address_line1'] ?? '',
            'city'       => $data['city'] ?? '',
            'state'      => $data['state'] ?? '',
            'zip'        => $data['postal_code'] ?? '',
            'created_by' => $data['created_by'] ?? 1
        ]);
        return (int)$this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool {
        $stmt = $this->db->prepare("UPDATE hospitals SET name = :name, address_line1 = :addr, city = :city, state = :state, postal_code = :zip, phone = :phone WHERE hospital_id = :id");
        return $stmt->execute([
            'name'  => $data['name'],
            'addr'  => $data['address_line1'] ?? '',
            'city'  => $data['city'] ?? '',
            'state' => $data['state'] ?? '',
            'zip'   => $data['postal_code'] ?? '',
            'phone' => $data['phone'] ?? '',
            'id'    => $id
        ]);
    }

    public function delete(int $id): bool {
        $stmt = $this->db->prepare("UPDATE hospitals SET is_active = 0 WHERE hospital_id = :id");
        return $stmt->execute(['id' => $id]);
    }

    public function countAll(): int {
        return (int)$this->db->query("SELECT COUNT(*) FROM hospitals WHERE is_active = 1")->fetchColumn();
    }
}
