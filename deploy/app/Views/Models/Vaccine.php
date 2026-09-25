<?php
class Vaccine {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAll(): array {
        return $this->db->query("SELECT * FROM vaccines ORDER BY name ASC")->fetchAll();
    }

    public function getAvailable(): array {
        return $this->db->query("SELECT DISTINCT v.* FROM vaccines v JOIN hospital_vaccine_inventory hvi ON v.vaccine_id = hvi.vaccine_id WHERE v.is_active = 1 AND hvi.is_available = 1 AND hvi.available_stock > 0 ORDER BY v.name ASC")->fetchAll();
    }

    public function findById(int $id): ?array {
        $stmt = $this->db->prepare("SELECT * FROM vaccines WHERE vaccine_id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        $v = $stmt->fetch();
        return $v ?: null;
    }

    public function toggleActive(int $id, bool $active): bool {
        $stmt = $this->db->prepare("UPDATE vaccines SET is_active = :active WHERE vaccine_id = :id");
        return $stmt->execute(['active' => $active ? 1 : 0, 'id' => $id]);
    }

    public function countAll(): int {
        return (int)$this->db->query("SELECT COUNT(*) FROM vaccines")->fetchColumn();
    }

    public function countActive(): int {
        return (int)$this->db->query("SELECT COUNT(*) FROM vaccines WHERE is_active = 1")->fetchColumn();
    }

    public function countInactive(): int {
        return (int)$this->db->query("SELECT COUNT(*) FROM vaccines WHERE is_active = 0")->fetchColumn();
    }
}
