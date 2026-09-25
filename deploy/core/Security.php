<?php
// core/Security.php
// Security utility class for handling XSS sanitization, CSRF token generation/validation, and authentication helper checks

class Security {
    
    /**
     * Sanitizes a string or array of values to protect against Cross-Site Scripting (XSS).
     *
     * @param mixed $data Input string or array of strings
     * @return mixed Sanitized output
     */
    public static function sanitize(mixed $data): mixed {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $data[$key] = self::sanitize($value);
            }
            return $data;
        }
        
        if (is_string($data)) {
            return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
        }
        
        return $data;
    }

    /**
     * Generates a secure CSRF token and stores it in the session if not already set.
     *
     * @return string CSRF token
     */
    public static function generateCsrfToken(): string {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    /**
     * Validates the provided CSRF token against the one stored in session.
     *
     * @param string|null $token CSRF token to check
     * @return bool True if valid, false otherwise
     */
    public static function validateCsrfToken(?string $token): bool {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (empty($_SESSION['csrf_token']) || empty($token)) {
            return false;
        }
        return hash_equals($_SESSION['csrf_token'], $token);
    }

    /**
     * Outputs an HTML input tag with the CSRF token.
     *
     * @return string Hidden input field HTML
     */
    public static function csrfInput(): string {
        $token = self::generateCsrfToken();
        return '<input type="hidden" name="csrf_token" value="' . $token . '">';
    }

    /**
     * Checks if a user is logged in.
     *
     * @return bool
     */
    public static function isLoggedIn(): bool {
        return isset($_SESSION['user_id']);
    }

    /**
     * Requires the user to be logged in, redirects to login if not.
     */
    public static function requireLogin(): void {
        if (!self::isLoggedIn()) {
            $_SESSION['error'] = "Authentication required. Please login.";
            header("Location: " . BASE_URL . "login");
            exit();
        }
    }

    /**
     * Requires the logged-in user to have specific role(s).
     * Redirects to their own dashboard if denied (not login page).
     *
     * @param array|string $roles Role or list of acceptable roles
     */
    public static function requireRole(array|string $roles): void {
        self::requireLogin();
        $userRole = $_SESSION['user_role'] ?? '';
        $allowedRoles = is_array($roles) ? $roles : [$roles];
        
        if (!in_array($userRole, $allowedRoles)) {
            $_SESSION['error'] = "Access denied. You do not have permission to view this page.";
            self::redirectToOwnDashboard($userRole);
        }
    }

    /**
     * Requires the logged-in user to have a valid hospital_id in session.
     */
    public static function requireHospitalId(): void {
        self::requireRole('hospital');
        if (empty($_SESSION['hospital_id']) || $_SESSION['hospital_id'] <= 0) {
            $_SESSION['error'] = "No hospital account linked. Please contact administrator.";
            header("Location: " . BASE_URL . "login");
            exit();
        }
    }

    /**
     * Gets the current user's role from session.
     */
    public static function getRole(): string {
        return $_SESSION['user_role'] ?? '';
    }

    /**
     * Gets the current hospital's ID from session.
     */
    public static function getHospitalId(): int {
        return (int)($_SESSION['hospital_id'] ?? 0);
    }

    /**
     * Redirects user to their own dashboard based on role.
     */
    private static function redirectToOwnDashboard(string $role): void {
        switch ($role) {
            case 'admin': header("Location: " . BASE_URL . "admin/dashboard"); break;
            case 'hospital': header("Location: " . BASE_URL . "hospital/dashboard"); break;
            case 'parent': header("Location: " . BASE_URL . "parent/dashboard"); break;
            default: header("Location: " . BASE_URL . "login");
        }
        exit();
    }
}
