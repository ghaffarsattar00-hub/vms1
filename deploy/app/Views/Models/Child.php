<?php
class Child {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function findByParentId(int $parentId): array {
        $stmt = $this->db->prepare("SELECT * FROM children WHERE parent_user_id = :pid ORDER BY first_name ASC");
        $stmt->execute(['pid' => $parentId]);
        return $stmt->fetchAll();
    }

    public function findById(int $id): ?array {
        $stmt = $this->db->prepare("SELECT * FROM children WHERE child_id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        $child = $stmt->fetch();
        return $child ?: null;
    }

    public function create(array $data): int {
        $stmt = $this->db->prepare("INSERT INTO children (parent_user_id, first_name, last_name, date_of_birth, gender, blood_group, birth_registration_number, is_active) VALUES (:parent_user_id, :first_name, :last_name, :date_of_birth, :gender, :blood_group, :brn, 1)");
        $stmt->execute([
            'parent_user_id'          => $data['parent_user_id'],
            'first_name'              => $data['first_name'],
            'last_name'               => $data['last_name'],
            'date_of_birth'           => $data['date_of_birth'],
            'gender'                  => $data['gender'] ?? 'Unknown',
            'blood_group'             => $data['blood_group'] ?? 'Unknown',
            'brn'                     => $data['birth_registration_number'] ?? 'BRN-' . uniqid()
        ]);
        return (int)$this->db->lastInsertId();
    }

    public function delete(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM children WHERE child_id = :id");
        return $stmt->execute(['id' => $id]);
    }

    public function countAll(): int {
        return (int)$this->db->query("SELECT COUNT(*) FROM children")->fetchColumn();
    }
}
