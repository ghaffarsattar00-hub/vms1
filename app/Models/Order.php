<?php
class Order {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function create(array $data): int {
        $etaMinutes = rand(20, 30);
        $stmt = $this->db->prepare("
            INSERT INTO vaccine_orders (hospital_id, inventory_id, vaccine_name, quantity_ordered, ordered_by, notes, estimated_delivery)
            VALUES (:hid, :iid, :vname, :qty, :uid, :notes, DATE_ADD(NOW(), INTERVAL :eta MINUTE))
        ");
        $stmt->execute([
            'hid'   => $data['hospital_id'],
            'iid'   => $data['inventory_id'],
            'vname' => $data['vaccine_name'],
            'qty'   => $data['quantity_ordered'],
            'uid'   => $data['ordered_by'],
            'notes' => $data['notes'] ?? null,
            'eta'   => $etaMinutes
        ]);
        return (int)$this->db->lastInsertId();
    }

    public function findByHospitalId(int $hospitalId): array {
        $stmt = $this->db->prepare("
            SELECT vo.*, u.full_name as ordered_by_name,
                   TIMESTAMPDIFF(MINUTE, NOW(), vo.estimated_delivery) as minutes_left
            FROM vaccine_orders vo
            JOIN users u ON vo.ordered_by = u.user_id
            WHERE vo.hospital_id = :hid
            ORDER BY vo.created_at DESC
        ");
        $stmt->execute(['hid' => $hospitalId]);
        return $stmt->fetchAll();
    }

    public function findById(int $id): ?array {
        $stmt = $this->db->prepare("
            SELECT vo.*, u.full_name as ordered_by_name, h.name as hospital_name,
                   TIMESTAMPDIFF(MINUTE, NOW(), vo.estimated_delivery) as minutes_left
            FROM vaccine_orders vo
            JOIN users u ON vo.ordered_by = u.user_id
            JOIN hospitals h ON vo.hospital_id = h.hospital_id
            WHERE vo.order_id = :id LIMIT 1
        ");
        $stmt->execute(['id' => $id]);
        $r = $stmt->fetch();
        return $r ?: null;
    }

    public function getAll(): array {
        return $this->db->query("
            SELECT vo.*, u.full_name as ordered_by_name, h.name as hospital_name,
                   TIMESTAMPDIFF(MINUTE, NOW(), vo.estimated_delivery) as minutes_left
            FROM vaccine_orders vo
            JOIN users u ON vo.ordered_by = u.user_id
            JOIN hospitals h ON vo.hospital_id = h.hospital_id
            ORDER BY vo.created_at DESC
        ")->fetchAll();
    }

    public function countByStatus(string $status): int {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM vaccine_orders WHERE status = :status");
        $stmt->execute(['status' => $status]);
        return (int)$stmt->fetchColumn();
    }

    public function countByHospitalAndStatus(int $hospitalId, string $status): int {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM vaccine_orders WHERE hospital_id = :hid AND status = :status");
        $stmt->execute(['hid' => $hospitalId, 'status' => $status]);
        return (int)$stmt->fetchColumn();
    }

    public function updateStatus(int $orderId, string $status): bool {
        $extra = '';
        $params = ['status' => $status, 'id' => $orderId];
        if ($status === 'delivered') {
            $extra = ', actual_delivery = NOW()';
        }
        $stmt = $this->db->prepare("UPDATE vaccine_orders SET status = :status{$extra} WHERE order_id = :id");
        $result = $stmt->execute($params);

        if ($result && $status === 'delivered') {
            $order = $this->findById($orderId);
            if ($order) {
                $updateStock = $this->db->prepare("
                    UPDATE hospital_vaccine_inventory 
                    SET available_stock = available_stock + :qty,
                        last_restocked_at = NOW()
                    WHERE inventory_id = :iid
                ");
                $updateStock->execute(['qty' => $order['quantity_ordered'], 'iid' => $order['inventory_id']]);
            }
        }

        return $result;
    }

    public function getPendingCount(): int {
        return $this->countByStatus('pending');
    }
}
