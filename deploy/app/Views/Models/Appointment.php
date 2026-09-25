<?php
class Appointment {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAll(): array {
        $stmt = $this->db->query("
            SELECT a.*, c.first_name, c.last_name, c.date_of_birth, c.gender,
                   h.name as hospital_name, h.city as hospital_city,
                   v.name as vaccine_name, v.targeted_disease,
                   vd.dose_number, vd.description as dose_description,
                   u.full_name as parent_name
            FROM appointments a
            JOIN children c ON a.child_id = c.child_id
            JOIN users u ON c.parent_user_id = u.user_id
            JOIN hospitals h ON a.hospital_id = h.hospital_id
            JOIN vaccine_doses vd ON a.dose_id = vd.dose_id
            JOIN vaccines v ON vd.vaccine_id = v.vaccine_id
            ORDER BY a.scheduled_date DESC, a.created_at DESC
        ");
        return $stmt->fetchAll();
    }

    public function findByHospitalId(int $hospitalId): array {
        $stmt = $this->db->prepare("
            SELECT a.*, c.first_name, c.last_name, c.date_of_birth, c.gender,
                   v.name as vaccine_name, vd.dose_number, vd.description as dose_description,
                   u.full_name as parent_name
            FROM appointments a
            JOIN children c ON a.child_id = c.child_id
            JOIN users u ON c.parent_user_id = u.user_id
            JOIN vaccine_doses vd ON a.dose_id = vd.dose_id
            JOIN vaccines v ON vd.vaccine_id = v.vaccine_id
            WHERE a.hospital_id = :hid
            ORDER BY a.scheduled_date ASC, a.created_at DESC
        ");
        $stmt->execute(['hid' => $hospitalId]);
        return $stmt->fetchAll();
    }

    public function findByParentId(int $parentId): array {
        $stmt = $this->db->prepare("
            SELECT a.*, c.first_name, c.last_name,
                   h.name as hospital_name, h.city as hospital_city,
                   v.name as vaccine_name, vd.dose_number
            FROM appointments a
            JOIN children c ON a.child_id = c.child_id
            JOIN hospitals h ON a.hospital_id = h.hospital_id
            JOIN vaccine_doses vd ON a.dose_id = vd.dose_id
            JOIN vaccines v ON vd.vaccine_id = v.vaccine_id
            WHERE c.parent_user_id = :pid
            ORDER BY a.scheduled_date DESC, a.created_at DESC
        ");
        $stmt->execute(['pid' => $parentId]);
        return $stmt->fetchAll();
    }

    public function findById(int $id): ?array {
        $stmt = $this->db->prepare("SELECT * FROM appointments WHERE appointment_id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        $a = $stmt->fetch();
        return $a ?: null;
    }

    public function create(array $data): int {
        $stmt = $this->db->prepare("INSERT INTO appointments (child_id, hospital_id, dose_id, scheduled_date, status) VALUES (:child_id, :hospital_id, :dose_id, :scheduled_date, 'pending')");
        $stmt->execute([
            'child_id'      => $data['child_id'],
            'hospital_id'   => $data['hospital_id'],
            'dose_id'       => $data['dose_id'],
            'scheduled_date'=> $data['scheduled_date']
        ]);
        return (int)$this->db->lastInsertId();
    }

    public function updateStatus(int $id, string $status): bool {
        $stmt = $this->db->prepare("UPDATE appointments SET status = :status WHERE appointment_id = :id");
        return $stmt->execute(['status' => $status, 'id' => $id]);
    }

    public function countAll(): int {
        return (int)$this->db->query("SELECT COUNT(*) FROM appointments")->fetchColumn();
    }

    public function countByStatus(string $status): int {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM appointments WHERE status = :status");
        $stmt->execute(['status' => $status]);
        return (int)$stmt->fetchColumn();
    }

    public function getMonthlyStats(): array {
        $stmt = $this->db->query("SELECT MONTH(scheduled_date) as m, COUNT(*) as c FROM appointments WHERE YEAR(scheduled_date) = YEAR(CURRENT_DATE()) GROUP BY MONTH(scheduled_date) ORDER BY m ASC");
        $results = $stmt->fetchAll();
        $months = array_fill(1, 12, 0);
        foreach ($results as $row) { $months[(int)$row['m']] = (int)$row['c']; }
        return array_values($months);
    }
}
