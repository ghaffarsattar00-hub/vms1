<?php
// core/Controller.php
// Base Controller with utility methods for rendering views and handling redirects

abstract class Controller {
    
    /**
     * Renders a view file and injects data variables.
     *
     * @param string $viewName Path to the view file relative to app/Views/ (excluding .php)
     * @param array $data Associative array of data variables to make available in the view
     * @param bool $useLayout Whether to wrap the view with global header/footer layouts
     */
    protected function view(string $viewName, array $data = [], bool $useLayout = true): void {
        // Extract variables to local symbol table
        extract($data);
        
        // Define helpers for views
        $csrfToken = Security::generateCsrfToken();
        $csrfInput = Security::csrfInput();

        if ($useLayout) {
            require_once __DIR__ . '/../app/Views/layout/header.php';
        }
        
        $viewFile = __DIR__ . '/../app/Views/' . $viewName . '.php';
        if (file_exists($viewFile)) {
            require_once $viewFile;
        } else {
            die("View file not found: $viewFile");
        }
        
        if ($useLayout) {
            require_once __DIR__ . '/../app/Views/layout/footer.php';
        }
    }

    /**
     * Redirects to a specified application path.
     *
     * @param string $path Target path (e.g., 'admin/dashboard')
     */
    protected function redirect(string $path): void {
        header("Location: " . BASE_URL . $path);
        exit();
    }

    /**
     * Sends a JSON response (for AJAX calls).
     *
     * @param array $data Response data
     * @param int $statusCode HTTP status code
     */
    protected function json(array $data, int $statusCode = 200): void {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit();
    }
}
