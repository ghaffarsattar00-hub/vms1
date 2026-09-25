<?php
// core/Router.php
// A clean, robust routing engine for GET & POST requests

class Router {
    private array $routes = [];

    /**
     * Registers a GET route.
     */
    public function get(string $path, string $handler): void {
        $this->routes['GET'][$this->normalizePath($path)] = $handler;
    }

    /**
     * Registers a POST route.
     */
    public function post(string $path, string $handler): void {
        $this->routes['POST'][$this->normalizePath($path)] = $handler;
    }

    /**
     * Normalizes the path by removing leading/trailing slashes and query strings.
     */
    private function normalizePath(string $path): string {
        $path = parse_url($path, PHP_URL_PATH);
        $path = trim($path, '/');
        return $path === '' ? '/' : $path;
    }

    /**
     * Resolves the request by matching the URI and method, then invoking the controller action.
     */
    public function dispatch(string $uri, string $method): void {
        $path = $this->normalizePath($uri);
        $method = strtoupper($method);

        // First check for direct exact match
        if (isset($this->routes[$method][$path])) {
            $handler = $this->routes[$method][$path];
            $this->executeHandler($handler);
            return;
        }

        // Check for parameterized routes (e.g., admin/hospitals/delete/{id})
        foreach ($this->routes[$method] as $route => $handler) {
            $pattern = preg_replace('/\{[a-zA-Z0-9_]+\}/', '([a-zA-Z0-9_]+)', $route);
            $pattern = '@^' . $pattern . '$@';

            if (preg_match($pattern, $path, $matches)) {
                array_shift($matches); // Remove first match which is full path
                $this->executeHandler($handler, $matches);
                return;
            }
        }

        // If no route matches, render a beautiful 404 page
        $this->render404();
    }

    /**
     * Instantiates the controller and invokes the action.
     */
    private function executeHandler(string $handler, array $params = []): void {
        list($controllerName, $actionName) = explode('@', $handler);

        // File check and require
        $controllerFile = __DIR__ . '/../app/Controllers/' . $controllerName . '.php';
        if (file_exists($controllerFile)) {
            require_once $controllerFile;
        } else {
            die("Controller file not found: $controllerFile");
        }

        if (class_exists($controllerName)) {
            $controller = new $controllerName();
            if (method_exists($controller, $actionName)) {
                call_user_func_array([$controller, $actionName], $params);
            } else {
                die("Action '$actionName' not found in Controller '$controllerName'.");
            }
        } else {
            die("Class '$controllerName' not found.");
        }
    }

    /**
     * Displays a polished 404 Error page using Tailwind CSS
     */
    private function render404(): void {
        http_response_code(404);
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>404 - Page Not Found</title>
            <script src="https://cdn.tailwindcss.com"></script>
        </head>
        <body class="bg-slate-50 flex items-center justify-center min-h-screen">
            <div class="text-center p-8 bg-white rounded-2xl shadow-xl max-w-md border border-slate-100">
                <div class="inline-flex p-4 bg-red-50 rounded-full text-red-500 mb-4 animate-bounce">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <h1 class="text-6xl font-extrabold text-slate-800 mb-2">404</h1>
                <p class="text-slate-600 font-medium mb-6">Oops! The page you are looking for does not exist or has been moved.</p>
                <a href="<?php echo BASE_URL; ?>" class="inline-flex items-center px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-xl transition duration-200 shadow-md shadow-indigo-100">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    Go Back Home
                </a>
            </div>
        </body>
        </html>
        <?php
        exit();
    }
}
