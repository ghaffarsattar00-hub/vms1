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

    public function setResetToken(string $email, string $token, string $expires): bool {
        $stmt = $this->db->prepare("UPDATE users SET reset_token = :token, reset_expires = :expires WHERE email = :email AND is_active = 1");
        return $stmt->execute([
            'token'   => $token,
            'expires' => $expires,
            'email'   => $email
        ]);
    }

    public function findByResetToken(string $token): ?array {
        if (strlen($token) !== 64 || !ctype_xdigit($token)) return null;
        $stmt = $this->db->prepare("SELECT * FROM users WHERE reset_token = :token AND is_active = 1 LIMIT 1");
        $stmt->execute(['token' => $token]);
        $user = $stmt->fetch();
        if (!$user) return null;
        if (empty($user['reset_expires']) || strtotime($user['reset_expires']) <= time()) return null;
        return $user;
    }

    public function resetPasswordWithToken(string $token, string $passwordHash): bool {
        $stmt = $this->db->prepare("UPDATE users SET password_hash = :hash, reset_token = NULL, reset_expires = NULL WHERE reset_token = :token");
        return $stmt->execute(['hash' => $passwordHash, 'token' => $token]) && $stmt->rowCount() > 0;
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
