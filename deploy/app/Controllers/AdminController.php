<?php
class AdminController extends Controller {
    private Hospital $hospitalModel;
    private Vaccine $vaccineModel;
    private Appointment $appointmentModel;
    private Inventory $inventoryModel;
    private User $userModel;
    private Child $childModel;
    private Order $orderModel;

    public function __construct() {
        Security::requireRole('admin');
        $this->hospitalModel = new Hospital();
        $this->vaccineModel = new Vaccine();
        $this->appointmentModel = new Appointment();
        $this->inventoryModel = new Inventory();
        $this->userModel = new User();
        $this->childModel = new Child();
        $this->orderModel = new Order();
    }

    public function dashboard(): void {
        $data = [
            'pageTitle' => 'Admin Dashboard',
            'totalHospitals' => $this->hospitalModel->countAll(),
            'totalVaccines' => $this->vaccineModel->countAll(),
            'totalChildren' => $this->childModel->countAll(),
            'totalAppointments' => $this->appointmentModel->countAll(),
            'pendingCount' => $this->appointmentModel->countByStatus('pending'),
            'approvedCount' => $this->appointmentModel->countByStatus('approved'),
            'vaccinatedCount' => $this->appointmentModel->countByStatus('vaccinated'),
            'rejectedCount' => $this->appointmentModel->countByStatus('rejected'),
            'activeVaccines' => $this->vaccineModel->countActive(),
            'inactiveVaccines' => $this->vaccineModel->countInactive(),
            'monthlyStats' => $this->appointmentModel->getMonthlyStats()
        ];
        $this->view('admin/dashboard', $data);
    }

    public function hospitals(): void {
        $data = [
            'pageTitle' => 'Manage Hospitals',
            'hospitals' => $this->hospitalModel->getAll()
        ];
        $this->view('admin/hospitals', $data);
    }

    public function createHospital(): void {
        $_POST = Security::sanitize($_POST);
        if (!Security::validateCsrfToken($_POST['csrf_token'] ?? null)) {
            $_SESSION['error'] = "Invalid security token.";
            $this->redirect('admin/hospitals');
        }

        $name = trim($_POST['name'] ?? '');
        if (empty($name)) {
            $_SESSION['error'] = "Hospital name is required.";
            $this->redirect('admin/hospitals');
        }

        $hospitalEmail = trim($_POST['email'] ?? '');
        $hospitalPhone = trim($_POST['phone'] ?? '');
        $defaultPassword = 'hospital123';

        $db = Database::getInstance()->getConnection();

        try {
            $db->beginTransaction();

            $hospitalId = $this->hospitalModel->create([
                'name'               => $name,
                'registration_number'=> $_POST['registration_number'] ?? '',
                'email'              => $hospitalEmail,
                'phone'              => $hospitalPhone,
                'address_line1'      => $_POST['address_line1'] ?? '',
                'city'               => $_POST['city'] ?? '',
                'state'              => $_POST['state'] ?? '',
                'postal_code'        => $_POST['postal_code'] ?? '',
                'created_by'         => $_SESSION['user_id']
            ]);

            if (!$hospitalId) {
                throw new Exception("Failed to create hospital record.");
            }

            $userId = null;
            if (!empty($hospitalEmail)) {
                $existingUser = $this->userModel->findByEmail($hospitalEmail);
                if (!$existingUser) {
                    $userId = $this->userModel->create([
                        'full_name' => $name,
                        'email'     => $hospitalEmail,
                        'password'  => $defaultPassword,
                        'role'      => 'hospital',
                        'phone'     => $hospitalPhone
                    ]);

                    if (!$userId) {
                        throw new Exception("Failed to create user account.");
                    }

                    $linkStmt = $db->prepare("INSERT INTO hospital_users (hospital_id, user_id, is_active) VALUES (:hid, :uid, 1)");
                    $linkStmt->execute(['hid' => $hospitalId, 'uid' => $userId]);
                } else {
                    $userId = $existingUser['user_id'];
                    $linkCheck = $db->prepare("SELECT 1 FROM hospital_users WHERE hospital_id = :hid AND user_id = :uid");
                    $linkCheck->execute(['hid' => $hospitalId, 'uid' => $userId]);
                    if (!$linkCheck->fetch()) {
                        $linkStmt = $db->prepare("INSERT INTO hospital_users (hospital_id, user_id, is_active) VALUES (:hid, :uid, 1)");
                        $linkStmt->execute(['hid' => $hospitalId, 'uid' => $userId]);
                    }
                }
            }

            $db->commit();

            if ($userId && !empty($hospitalEmail)) {
                $_SESSION['success'] = "Hospital '$name' created. Login: $hospitalEmail / $defaultPassword";
            } else {
                $_SESSION['success'] = "Hospital '$name' created (no email — manual user setup needed).";
            }
        } catch (Exception $e) {
            $db->rollBack();
            $_SESSION['error'] = "Failed to create hospital: " . $e->getMessage();
        }

        $this->redirect('admin/hospitals');
    }

    public function updateHospital(): void {
        $_POST = Security::sanitize($_POST);
        if (!Security::validateCsrfToken($_POST['csrf_token'] ?? null)) {
            $_SESSION['error'] = "Invalid security token.";
            $this->redirect('admin/hospitals');
        }

        $id = (int)($_POST['hospital_id'] ?? 0);
        if ($id <= 0) { $this->redirect('admin/hospitals'); }

        $this->hospitalModel->update($id, [
            'name'          => $_POST['name'] ?? '',
            'address_line1' => $_POST['address_line1'] ?? '',
            'city'          => $_POST['city'] ?? '',
            'state'         => $_POST['state'] ?? '',
            'postal_code'   => $_POST['postal_code'] ?? '',
            'phone'         => $_POST['phone'] ?? ''
        ]);

        $_SESSION['success'] = "Hospital updated.";
        $this->redirect('admin/hospitals');
    }

    public function deleteHospital(int $id): void {
        $this->hospitalModel->delete($id);
        $_SESSION['success'] = "Hospital deactivated.";
        $this->redirect('admin/hospitals');
    }

    public function inventory(): void {
        $data = [
            'pageTitle' => 'Vaccine Inventory',
            'inventory' => $this->inventoryModel->getAll()
        ];
        $this->view('admin/inventory', $data);
    }

    public function toggleVaccine(): void {
        header('Content-Type: application/json');
        $input = json_decode(file_get_contents('php://input'), true);

        if (!Security::validateCsrfToken($input['csrf_token'] ?? null)) {
            echo json_encode(['success' => false, 'message' => 'Invalid token.']);
            exit;
        }

        $inventoryId = (int)($input['inventory_id'] ?? 0);
        $available = (bool)($input['is_available'] ?? false);

        if ($inventoryId <= 0) {
            echo json_encode(['success' => false, 'message' => 'Invalid ID.']);
            exit;
        }

        if ($this->inventoryModel->toggleAvailability($inventoryId, $available)) {
            echo json_encode(['success' => true, 'message' => 'Availability updated.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Update failed.']);
        }
        exit;
    }

    public function appointments(): void {
        $all = $this->appointmentModel->getAll();
        $pendingCount = 0; $approvedCount = 0; $vaccinatedCount = 0; $rejectedCount = 0;
        foreach ($all as $apt) {
            match (strtolower($apt['status'] ?? '')) {
                'pending' => $pendingCount++,
                'approved' => $approvedCount++,
                'vaccinated' => $vaccinatedCount++,
                'rejected' => $rejectedCount++,
                default => null
            };
        }
        $data = [
            'pageTitle'       => 'Manage Appointments',
            'appointments'    => $all,
            'totalAppointments'=> count($all),
            'pendingCount'    => $pendingCount,
            'approvedCount'   => $approvedCount,
            'vaccinatedCount' => $vaccinatedCount,
            'rejectedCount'   => $rejectedCount
        ];
        $this->view('admin/appointments', $data);
    }

    public function updateAppointment(): void {
        header('Content-Type: application/json');
        $input = json_decode(file_get_contents('php://input'), true);

        if (!Security::validateCsrfToken($input['csrf_token'] ?? null)) {
            echo json_encode(['success' => false, 'message' => 'Invalid token.']);
            exit;
        }

        $id = (int)($input['appointment_id'] ?? 0);
        $status = $input['status'] ?? '';
        $allowed = ['approved', 'rejected', 'vaccinated', 'cancelled'];

        if ($id <= 0 || !in_array($status, $allowed)) {
            echo json_encode(['success' => false, 'message' => 'Invalid request.']);
            exit;
        }

        $apt = $this->appointmentModel->findById($id);
        if (!$apt) {
            echo json_encode(['success' => false, 'message' => 'Appointment not found.']);
            exit;
        }

        $this->appointmentModel->updateStatus($id, $status);
        echo json_encode(['success' => true, 'message' => 'Appointment status updated.']);
        exit;
    }

    public function setupHospitalUsers(): void {
        $db = Database::getInstance()->getConnection();

        $hospitals = $db->query("SELECT hospital_id, name, email FROM hospitals WHERE is_active = 1")->fetchAll();
        $created = 0;

        try {
            $db->beginTransaction();

            foreach ($hospitals as $h) {
                if (empty($h['email'])) continue;

                $check = $db->prepare("SELECT user_id FROM users WHERE email = :email LIMIT 1");
                $check->execute(['email' => $h['email']]);
                if ($check->fetch()) continue;

                $userId = $this->userModel->create([
                    'full_name' => $h['name'],
                    'email'     => $h['email'],
                    'password'  => 'hospital123',
                    'role'      => 'hospital',
                    'phone'     => ''
                ]);

                if ($userId) {
                    $link = $db->prepare("INSERT INTO hospital_users (hospital_id, user_id, is_active) VALUES (:hid, :uid, 1)");
                    $link->execute(['hid' => $h['hospital_id'], 'uid' => $userId]);
                    $created++;
                }
            }

            $db->commit();
        } catch (Exception $e) {
            $db->rollBack();
            $_SESSION['error'] = "Setup failed: " . $e->getMessage();
            $this->redirect('admin/hospitals');
        }

        $_SESSION['success'] = "Setup complete. $created hospital user accounts created. Default password: hospital123";
        $this->redirect('admin/hospitals');
    }

    public function orders(): void {
        $orders = $this->orderModel->getAll();
        $pendingCount = $this->orderModel->countByStatus('pending');
        $approvedCount = $this->orderModel->countByStatus('approved');
        $deliveredCount = $this->orderModel->countByStatus('delivered');
        $cancelledCount = $this->orderModel->countByStatus('cancelled');

        $data = [
            'pageTitle'      => 'Vaccine Orders',
            'orders'         => $orders,
            'totalOrders'    => count($orders),
            'pendingCount'   => $pendingCount,
            'approvedCount'  => $approvedCount,
            'deliveredCount' => $deliveredCount,
            'cancelledCount' => $cancelledCount
        ];
        $this->view('admin/orders', $data);
    }

    public function updateOrder(): void {
        header('Content-Type: application/json');
        $input = json_decode(file_get_contents('php://input'), true);

        if (!Security::validateCsrfToken($input['csrf_token'] ?? null)) {
            echo json_encode(['success' => false, 'message' => 'Invalid token.']);
            exit;
        }

        $id = (int)($input['order_id'] ?? 0);
        $status = $input['status'] ?? '';
        $allowed = ['approved', 'delivered', 'cancelled'];

        if ($id <= 0 || !in_array($status, $allowed)) {
            echo json_encode(['success' => false, 'message' => 'Invalid request.']);
            exit;
        }

        $order = $this->orderModel->findById($id);
        if (!$order) {
            echo json_encode(['success' => false, 'message' => 'Order not found.']);
            exit;
        }

        $this->orderModel->updateStatus($id, $status);
        $statusLabel = ucfirst($status);
        echo json_encode(['success' => true, 'message' => "Order #$id {$statusLabel}."]);
        exit;
    }
}
