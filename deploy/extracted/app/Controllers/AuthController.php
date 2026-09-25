<?php
class AuthController extends Controller {
    private User $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    public function login(): void {
        if (Security::isLoggedIn()) {
            $this->redirectToDashboard($_SESSION['user_role']);
        }
        $db = Database::getInstance()->getConnection();
        $hospitalCount = (int)$db->query("SELECT COUNT(*) FROM hospitals WHERE is_active = 1")->fetchColumn();
        $vaccineCount = (int)$db->query("SELECT COUNT(*) FROM vaccines")->fetchColumn();
        $this->view('auth/login', ['hospitalCount' => $hospitalCount, 'vaccineCount' => $vaccineCount], false);
    }

    public function authenticate(): void {
        $_POST = Security::sanitize($_POST);
        if (!Security::validateCsrfToken($_POST['csrf_token'] ?? null)) {
            $_SESSION['error'] = "Invalid security token.";
            $this->redirect('login');
        }

        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
            $_SESSION['error'] = "Please fill in all fields.";
            $this->redirect('login');
        }

        $user = $this->userModel->findByEmail($email);

        if ($user && password_verify($password, $user['password_hash']) && $user['is_active']) {
            session_regenerate_id(true);
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['user_name'] = $user['full_name'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_role'] = $user['role_key'];

            if ($user['role_key'] === 'hospital') {
                $db = Database::getInstance()->getConnection();
                $stmt = $db->prepare("SELECT hospital_id FROM hospital_users WHERE user_id = :uid AND is_active = 1 LIMIT 1");
                $stmt->execute(['uid' => $user['user_id']]);
                $link = $stmt->fetch();
                $_SESSION['hospital_id'] = $link ? (int)$link['hospital_id'] : 0;
            }

            $_SESSION['success'] = "Welcome back, " . $user['full_name'] . "!";
            $this->redirectToDashboard($user['role_key']);
        } else {
            $_SESSION['error'] = "Invalid email or password, or account is disabled.";
            $this->redirect('login');
        }
    }

    public function register(): void {
        if (Security::isLoggedIn()) {
            $this->redirectToDashboard($_SESSION['user_role']);
        }
        $this->view('auth/register', [], false);
    }

    public function storeParent(): void {
        $_POST = Security::sanitize($_POST);
        if (!Security::validateCsrfToken($_POST['csrf_token'] ?? null)) {
            $_SESSION['error'] = "Invalid security token.";
            $this->redirect('register');
        }

        $fullName = trim($_POST['full_name'] ?? '');
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';
        $phone = trim($_POST['phone'] ?? '');

        if (empty($fullName) || empty($email) || empty($password)) {
            $_SESSION['error'] = "All fields are required.";
            $this->redirect('register');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error'] = "Invalid email format.";
            $this->redirect('register');
        }

        if ($password !== $confirmPassword) {
            $_SESSION['error'] = "Passwords do not match.";
            $this->redirect('register');
        }

        if (strlen($password) < 6) {
            $_SESSION['error'] = "Password must be at least 6 characters.";
            $this->redirect('register');
        }

        if ($this->userModel->findByEmail($email) !== null) {
            $_SESSION['error'] = "This email address is already registered.";
            $this->redirect('register');
        }

        $userId = $this->userModel->create([
            'full_name' => $fullName,
            'email'     => $email,
            'password'  => $password,
            'role'      => 'parent',
            'phone'     => $phone
        ]);

        if ($userId) {
            $_SESSION['user_id'] = $userId;
            $_SESSION['user_name'] = $fullName;
            $_SESSION['user_email'] = $email;
            $_SESSION['user_role'] = 'parent';
            $_SESSION['success'] = "Account created successfully!";
            $this->redirect('parent/dashboard');
        } else {
            $_SESSION['error'] = "Failed to register.";
            $this->redirect('register');
        }
    }

    public function logout(): void {
        $_SESSION = [];
        session_destroy();
        session_start();
        $_SESSION['success'] = "Logged out successfully.";
        $this->redirect('login');
    }

    private function redirectToDashboard(string $role): void {
        switch ($role) {
            case 'admin': $this->redirect('admin/dashboard'); break;
            case 'hospital': $this->redirect('hospital/dashboard'); break;
            case 'parent': $this->redirect('parent/dashboard'); break;
            default: $this->redirect('login');
        }
    }
}
