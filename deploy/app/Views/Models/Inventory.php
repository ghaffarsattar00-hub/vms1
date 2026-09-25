<?php
class Inventory {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAll(): array {
        return $this->db->query("
            SELECT hvi.*, v.name as vaccine_name, v.targeted_disease, h.name as hospital_name
            FROM hospital_vaccine_inventory hvi
            JOIN vaccines v ON hvi.vaccine_id = v.vaccine_id
            JOIN hospitals h ON hvi.hospital_id = h.hospital_id
            WHERE h.is_active = 1
            ORDER BY h.name ASC, v.name ASC
        ")->fetchAll();
    }

    public function findByHospitalId(int $hospitalId): array {
        $stmt = $this->db->prepare("
            SELECT hvi.*, v.name as vaccine_name, v.targeted_disease
            FROM hospital_vaccine_inventory hvi
            JOIN vaccines v ON hvi.vaccine_id = v.vaccine_id
            WHERE hvi.hospital_id = :hid
            ORDER BY v.name ASC
        ");
        $stmt->execute(['hid' => $hospitalId]);
        return $stmt->fetchAll();
    }

    public function toggleAvailability(int $inventoryId, bool $available): bool {
        $stmt = $this->db->prepare("UPDATE hospital_vaccine_inventory SET is_available = :avail WHERE inventory_id = :id");
        return $stmt->execute(['avail' => $available ? 1 : 0, 'id' => $inventoryId]);
    }

    public function findById(int $id): ?array {
        $stmt = $this->db->prepare("
            SELECT hvi.*, v.name as vaccine_name, v.targeted_disease
            FROM hospital_vaccine_inventory hvi
            JOIN vaccines v ON hvi.vaccine_id = v.vaccine_id
            WHERE hvi.inventory_id = :id LIMIT 1
        ");
        $stmt->execute(['id' => $id]);
        $r = $stmt->fetch();
        return $r ?: null;
    }

    public function countAll(): int {
        return (int)$this->db->query("SELECT COUNT(*) FROM hospital_vaccine_inventory")->fetchColumn();
    }

    public function countAvailable(): int {
        return (int)$this->db->query("SELECT COUNT(*) FROM hospital_vaccine_inventory WHERE is_available = 1")->fetchColumn();
    }

    public function countUnavailable(): int {
        return (int)$this->db->query("SELECT COUNT(*) FROM hospital_vaccine_inventory WHERE is_available = 0")->fetchColumn();
    }
}
