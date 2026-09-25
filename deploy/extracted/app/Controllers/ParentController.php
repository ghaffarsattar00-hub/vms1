<?php
class ParentController extends Controller {
    private Child $childModel;
    private Hospital $hospitalModel;
    private VaccineDose $doseModel;
    private Vaccine $vaccineModel;
    private Appointment $appointmentModel;

    public function __construct() {
        Security::requireRole('parent');
        $this->childModel = new Child();
        $this->hospitalModel = new Hospital();
        $this->doseModel = new VaccineDose();
        $this->vaccineModel = new Vaccine();
        $this->appointmentModel = new Appointment();
    }

    public function dashboard(): void {
        $pid = $_SESSION['user_id'];
        $children = $this->childModel->findByParentId($pid);
        $appointments = $this->appointmentModel->findByParentId($pid);

        $pendingCount = 0; $vaccinatedCount = 0;
        foreach ($appointments as $apt) {
            match (strtolower($apt['status'] ?? '')) {
                'pending' => $pendingCount++,
                'vaccinated' => $vaccinatedCount++,
                default => null
            };
        }

        // Calculate vaccinated count per child
        $childVaccines = [];
        foreach ($appointments as $apt) {
            if (strtolower($apt['status'] ?? '') === 'vaccinated') {
                $childVaccines[$apt['child_id']] = ($childVaccines[$apt['child_id']] ?? 0) + 1;
            }
        }
        foreach ($children as &$child) {
            $child['vaccinated_count'] = $childVaccines[$child['child_id']] ?? 0;
        }
        unset($child);

        $data = [
            'pageTitle'       => 'Parent Dashboard',
            'children'        => $children,
            'childCount'      => count($children),
            'appointments'    => $appointments,
            'pendingCount'    => $pendingCount,
            'vaccinatedCount' => $vaccinatedCount
        ];
        $this->view('parent/dashboard', $data);
    }

    public function addChild(): void {
        $_POST = Security::sanitize($_POST);
        if (!Security::validateCsrfToken($_POST['csrf_token'] ?? null)) {
            $_SESSION['error'] = "Invalid token.";
            $this->redirect('parent/dashboard');
        }

        $firstName = trim($_POST['first_name'] ?? '');
        $lastName = trim($_POST['last_name'] ?? '');
        $dob = $_POST['date_of_birth'] ?? '';

        if (empty($firstName) || empty($lastName) || empty($dob)) {
            $_SESSION['error'] = "All fields are required.";
            $this->redirect('parent/dashboard');
        }

        $this->childModel->create([
            'parent_user_id' => $_SESSION['user_id'],
            'first_name'     => $firstName,
            'last_name'      => $lastName,
            'date_of_birth'  => $dob
        ]);

        $_SESSION['success'] = "Child profile for '$firstName $lastName' created.";
        $this->redirect('parent/dashboard');
    }

    public function deleteChild(int $id): void {
        $child = $this->childModel->findById($id);
        if (!$child || $child['parent_user_id'] != $_SESSION['user_id']) {
            $_SESSION['error'] = "Access denied.";
            $this->redirect('parent/dashboard');
        }
        $this->childModel->delete($id);
        $_SESSION['success'] = "Child profile deleted.";
        $this->redirect('parent/dashboard');
    }

    public function book(): void {
        $pid = $_SESSION['user_id'];
        $hospitals = $this->hospitalModel->getAll();
        $allDoses = $this->doseModel->getAll();
        $vaccines = $this->vaccineModel->getAll();

        $data = [
            'pageTitle' => 'Book Appointment',
            'children'  => $this->childModel->findByParentId($pid),
            'hospitals' => $hospitals,
            'doses'     => $allDoses,
            'vaccines'  => $vaccines
        ];
        $this->view('parent/book', $data);
    }

    public function createAppointment(): void {
        $_POST = Security::sanitize($_POST);
        if (!Security::validateCsrfToken($_POST['csrf_token'] ?? null)) {
            $_SESSION['error'] = "Invalid token.";
            $this->redirect('parent/book');
        }

        $childId = (int)($_POST['child_id'] ?? 0);
        $hospitalId = (int)($_POST['hospital_id'] ?? 0);
        $doseId = (int)($_POST['dose_id'] ?? 0);
        $date = $_POST['scheduled_date'] ?? '';

        if ($childId <= 0 || $hospitalId <= 0 || $doseId <= 0 || empty($date)) {
            $_SESSION['error'] = "All fields are required.";
            $this->redirect('parent/book');
        }

        if (strtotime($date) < strtotime('today')) {
            $_SESSION['error'] = "Date must be today or in the future.";
            $this->redirect('parent/book');
        }

        $child = $this->childModel->findById($childId);
        if (!$child || $child['parent_user_id'] != $_SESSION['user_id']) {
            $_SESSION['error'] = "Invalid child.";
            $this->redirect('parent/book');
        }

        $id = $this->appointmentModel->create([
            'child_id'       => $childId,
            'hospital_id'    => $hospitalId,
            'dose_id'        => $doseId,
            'scheduled_date' => $date
        ]);

        $_SESSION['success'] = $id ? "Appointment booked! Pending admin approval." : "Booking failed.";
        $this->redirect('parent/dashboard');
    }
}
