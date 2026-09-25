<?php
class VaccineDose {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAll(): array {
        return $this->db->query("
            SELECT vd.*, v.name as vaccine_name
            FROM vaccine_doses vd
            JOIN vaccines v ON vd.vaccine_id = v.vaccine_id
            ORDER BY v.name ASC, vd.dose_number ASC
        ")->fetchAll();
    }

    public function findByVaccineId(int $vaccineId): array {
        $stmt = $this->db->prepare("SELECT * FROM vaccine_doses WHERE vaccine_id = :vid ORDER BY dose_number ASC");
        $stmt->execute(['vid' => $vaccineId]);
        return $stmt->fetchAll();
    }

    public function findById(int $id): ?array {
        $stmt = $this->db->prepare("SELECT vd.*, v.name as vaccine_name FROM vaccine_doses vd JOIN vaccines v ON vd.vaccine_id = v.vaccine_id WHERE vd.dose_id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        $d = $stmt->fetch();
        return $d ?: null;
    }

    public function getAvailableForBooking(int $hospitalId): array {
        $stmt = $this->db->prepare("
            SELECT vd.*, v.name as vaccine_name, v.targeted_disease, hvi.available_stock
            FROM vaccine_doses vd
            JOIN vaccines v ON vd.vaccine_id = v.vaccine_id
            JOIN hospital_vaccine_inventory hvi ON v.vaccine_id = hvi.vaccine_id
            WHERE v.is_active = 1 AND hvi.is_available = 1 AND hvi.available_stock > 0 AND hvi.hospital_id = :hid
            ORDER BY v.name ASC, vd.dose_number ASC
        ");
        $stmt->execute(['hid' => $hospitalId]);
        return $stmt->fetchAll();
    }
}
