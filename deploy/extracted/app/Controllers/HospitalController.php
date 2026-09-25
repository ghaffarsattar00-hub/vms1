<?php
class HospitalController extends Controller {
    private Hospital $hospitalModel;
    private Appointment $appointmentModel;
    private Inventory $inventoryModel;
    private Order $orderModel;

    public function __construct() {
        Security::requireHospitalId();
        $this->hospitalModel = new Hospital();
        $this->appointmentModel = new Appointment();
        $this->inventoryModel = new Inventory();
        $this->orderModel = new Order();
    }

    private function getHospital(): array {
        $hospitalId = Security::getHospitalId();
        $hospital = $this->hospitalModel->findById($hospitalId);
        if (!$hospital) {
            $_SESSION['error'] = "Hospital account not found.";
            $this->redirect('login');
        }
        return $hospital;
    }

    public function dashboard(): void {
        $hospital = $this->getHospital();
        $hospitalId = $hospital['hospital_id'];

        $appointments = $this->appointmentModel->findByHospitalId($hospitalId);

        $pendingCount = 0; $approvedCount = 0; $vaccinatedCount = 0;
        $pendingAppointments = []; $approvedAppointments = [];
        foreach ($appointments as $apt) {
            $st = strtolower($apt['status'] ?? '');
            if ($st === 'pending') { $pendingCount++; $pendingAppointments[] = $apt; }
            elseif ($st === 'approved') { $approvedCount++; $approvedAppointments[] = $apt; }
            elseif ($st === 'vaccinated') { $vaccinatedCount++; }
        }

        $inventory = $this->inventoryModel->findByHospitalId($hospitalId);
        $lowStock = 0;
        foreach ($inventory as $item) {
            if ($item['available_stock'] <= $item['reorder_level']) $lowStock++;
        }

        $data = [
            'pageTitle'            => 'Hospital Dashboard',
            'hospital'             => $hospital,
            'appointments'         => $appointments,
            'totalAppointments'    => count($appointments),
            'pendingCount'         => $pendingCount,
            'approvedCount'        => $approvedCount,
            'vaccinatedCount'      => $vaccinatedCount,
            'pendingAppointments'  => $pendingAppointments,
            'approvedAppointments' => $approvedAppointments,
            'totalVaccines'        => count($inventory),
            'lowStock'             => $lowStock,
            'pendingOrders'        => $this->orderModel->countByHospitalAndStatus($hospitalId, 'pending')
        ];
        $this->view('hospital/dashboard', $data);
    }

    public function appointments(): void {
        $hospital = $this->getHospital();
        $hospitalId = $hospital['hospital_id'];

        $appointments = $this->appointmentModel->findByHospitalId($hospitalId);

        $pendingCount = 0; $approvedCount = 0; $vaccinatedCount = 0; $rejectedCount = 0;
        foreach ($appointments as $apt) {
            match (strtolower($apt['status'] ?? '')) {
                'pending' => $pendingCount++,
                'approved' => $approvedCount++,
                'vaccinated' => $vaccinatedCount++,
                'rejected' => $rejectedCount++,
                default => null
            };
        }

        $data = [
            'pageTitle'        => 'My Appointments',
            'hospital'         => $hospital,
            'appointments'     => $appointments,
            'totalAppointments'=> count($appointments),
            'pendingCount'     => $pendingCount,
            'approvedCount'    => $approvedCount,
            'vaccinatedCount'  => $vaccinatedCount,
            'rejectedCount'    => $rejectedCount
        ];
        $this->view('hospital/appointments', $data);
    }

    public function inventory(): void {
        $hospital = $this->getHospital();
        $hospitalId = $hospital['hospital_id'];

        $inventory = $this->inventoryModel->findByHospitalId($hospitalId);
        $orders = $this->orderModel->findByHospitalId($hospitalId);

        foreach ($inventory as &$item) {
            $item['is_low'] = $item['available_stock'] <= $item['reorder_level'];
            $item['is_out'] = $item['available_stock'] == 0;
        }
        unset($item);

        $data = [
            'pageTitle' => 'My Vaccine Inventory',
            'hospital'  => $hospital,
            'inventory' => $inventory,
            'orders'    => $orders
        ];
        $this->view('hospital/inventory', $data);
    }

    public function placeOrder(): void {
        $input = json_decode(file_get_contents('php://input'), true);
        if (!Security::validateCsrfToken($input['csrf_token'] ?? null)) {
            $this->jsonResponse(['success' => false, 'message' => 'Invalid token.']);
        }

        $inventoryId = (int)($input['inventory_id'] ?? 0);
        $quantity = (int)($input['quantity'] ?? 0);
        $notes = trim($input['notes'] ?? '');

        if ($inventoryId <= 0 || $quantity <= 0) {
            $this->jsonResponse(['success' => false, 'message' => 'Invalid order details.']);
        }

        if ($quantity > 1000) {
            $this->jsonResponse(['success' => false, 'message' => 'Maximum order quantity is 1000.']);
        }

        $item = $this->inventoryModel->findById($inventoryId);
        if (!$item) {
            $this->jsonResponse(['success' => false, 'message' => 'Inventory item not found.']);
        }

        $hospitalId = Security::getHospitalId();
        if ((int)$item['hospital_id'] !== $hospitalId) {
            $this->jsonResponse(['success' => false, 'message' => 'Access denied.']);
        }

        $orderId = $this->orderModel->create([
            'hospital_id'      => $hospitalId,
            'inventory_id'     => $inventoryId,
            'vaccine_name'     => $item['vaccine_name'],
            'quantity_ordered' => $quantity,
            'ordered_by'       => $_SESSION['user_id'],
            'notes'            => $notes
        ]);

        if ($orderId) {
            $this->jsonResponse(['success' => true, 'message' => "Order #$orderId placed for {$item['vaccine_name']} (x{$quantity}). Awaiting admin approval."]);
        } else {
            $this->jsonResponse(['success' => false, 'message' => 'Failed to place order.']);
        }
    }

    public function restock(): void {
        $input = json_decode(file_get_contents('php://input'), true);
        if (!Security::validateCsrfToken($input['csrf_token'] ?? null)) {
            $this->jsonResponse(['success' => false, 'message' => 'Invalid token.']);
        }

        $inventoryId = (int)($input['inventory_id'] ?? 0);
        $quantity = (int)($input['quantity'] ?? 0);

        if ($inventoryId <= 0 || $quantity <= 0) {
            $this->jsonResponse(['success' => false, 'message' => 'Invalid restock details.']);
        }

        $item = $this->inventoryModel->findById($inventoryId);
        if (!$item || (int)$item['hospital_id'] !== Security::getHospitalId()) {
            $this->jsonResponse(['success' => false, 'message' => 'Access denied.']);
        }

        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("UPDATE hospital_vaccine_inventory SET available_stock = available_stock + :qty, last_restocked_at = NOW(), is_available = 1 WHERE inventory_id = :iid");
        $result = $stmt->execute(['qty' => $quantity, 'iid' => $inventoryId]);

        if ($result) {
            $newStock = $item['available_stock'] + $quantity;
            $this->jsonResponse(['success' => true, 'message' => "Stock updated. New stock: {$newStock}", 'new_stock' => $newStock]);
        } else {
            $this->jsonResponse(['success' => false, 'message' => 'Restock failed.']);
        }
    }

    public function orders(): void {
        $hospital = $this->getHospital();
        $orders = $this->orderModel->findByHospitalId($hospital['hospital_id']);

        $data = [
            'pageTitle' => 'My Orders',
            'hospital'  => $hospital,
            'orders'    => $orders
        ];
        $this->view('hospital/orders', $data);
    }

    private function jsonResponse(array $data): void {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    private function validateAppointmentOwnership(int $appointmentId): ?array {
        $apt = $this->appointmentModel->findById($appointmentId);
        if (!$apt) {
            $this->jsonResponse(['success' => false, 'message' => 'Appointment not found.']);
        }
        $hospitalId = Security::getHospitalId();
        if ((int)$apt['hospital_id'] !== $hospitalId) {
            $this->jsonResponse(['success' => false, 'message' => 'Access denied. This appointment belongs to another hospital.']);
        }
        return $apt;
    }

    public function approveAppointment(): void {
        $input = json_decode(file_get_contents('php://input'), true);
        if (!Security::validateCsrfToken($input['csrf_token'] ?? null)) {
            $this->jsonResponse(['success' => false, 'message' => 'Invalid token.']);
        }

        $id = (int)($input['appointment_id'] ?? 0);
        $apt = $this->validateAppointmentOwnership($id);

        if (strtolower($apt['status'] ?? '') !== 'pending') {
            $this->jsonResponse(['success' => false, 'message' => 'Only pending appointments can be approved.']);
        }

        $this->appointmentModel->updateStatus($id, 'approved');
        $this->jsonResponse(['success' => true, 'message' => 'Appointment approved.']);
    }

    public function rejectAppointment(): void {
        $input = json_decode(file_get_contents('php://input'), true);
        if (!Security::validateCsrfToken($input['csrf_token'] ?? null)) {
            $this->jsonResponse(['success' => false, 'message' => 'Invalid token.']);
        }

        $id = (int)($input['appointment_id'] ?? 0);
        $apt = $this->validateAppointmentOwnership($id);

        $this->appointmentModel->updateStatus($id, 'rejected');
        $this->jsonResponse(['success' => true, 'message' => 'Appointment rejected.']);
    }

    public function vaccinateAppointment(): void {
        $input = json_decode(file_get_contents('php://input'), true);
        if (!Security::validateCsrfToken($input['csrf_token'] ?? null)) {
            $this->jsonResponse(['success' => false, 'message' => 'Invalid token.']);
        }

        $appointmentId = (int)($input['appointment_id'] ?? 0);
        $appointment = $this->validateAppointmentOwnership($appointmentId);

        if (strtolower($appointment['status'] ?? '') !== 'approved') {
            $this->jsonResponse(['success' => false, 'message' => 'Only approved appointments can be vaccinated.']);
        }

        if ($this->appointmentModel->updateStatus($appointmentId, 'vaccinated')) {
            $this->jsonResponse(['success' => true, 'message' => 'Marked as vaccinated!']);
        } else {
            $this->jsonResponse(['success' => false, 'message' => 'Update failed.']);
        }
    }
}
