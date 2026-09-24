<?php
class User {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function findByEmail(string $email): ?array {
        $stmt = $this->db->prepare("SELECT u.*, r.role_key FROM users u JOIN roles r ON u.role_id = r.role_id WHERE u.email = :email LIMIT 1");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch();
        return $user ?: null;
    }

    public function findById(int $id): ?array {
        $stmt = $this->db->prepare("SELECT u.*, r.role_key FROM users u JOIN roles r ON u.role_id = r.role_id WHERE u.user_id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        $user = $stmt->fetch();
        return $user ?: null;
    }

    public function create(array $data): int {
        $roleKey = $data['role'] ?? 'parent';
        $roleStmt = $this->db->prepare("SELECT role_id FROM roles WHERE role_key = :key LIMIT 1");
        $roleStmt->execute(['key' => $roleKey]);
        $role = $roleStmt->fetch();
        if (!$role) return 0;

        $stmt = $this->db->prepare("INSERT INTO users (full_name, email, password_hash, role_id, phone, is_active) VALUES (:full_name, :email, :password_hash, :role_id, :phone, 1)");
        $stmt->execute([
            'full_name'     => $data['full_name'],
            'email'         => $data['email'],
            'password_hash' => password_hash($data['password'], PASSWORD_DEFAULT),
            'role_id'       => $role['role_id'],
            'phone'         => $data['phone'] ?? ''
        ]);
        return (int)$this->db->lastInsertId();
    }

    public function countAll(): int {
        return (int)$this->db->query("SELECT COUNT(*) FROM users")->fetchColumn();
    }

    public function countByRole(string $roleKey): int {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM users u JOIN roles r ON u.role_id = r.role_id WHERE r.role_key = :key");
        $stmt->execute(['key' => $roleKey]);
        return (int)$stmt->fetchColumn();
    }
}
