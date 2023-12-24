<?php

class SimpleRouter {
    private $routes = [];

    public function addRoute($method, $pattern, $callback) {
        $this->routes[] = [
            'method' => $method,
            'pattern' => $pattern,
            'callback' => $callback
        ];
    }

    public function match($method, $uri) {
        foreach ($this->routes as $route) {
            if ($route['method'] === $method && preg_match($this->patternToRegex($route['pattern']), $uri, $matches)) {
                array_shift($matches); // Remove the full match
                call_user_func_array($route['callback'], $matches);
                return;
            }
        }
        $this->notFound();
    }

    private function patternToRegex($pattern) {
        return '#^' . preg_replace('#{([\w]+)}#', '([^/]+)', $pattern) . '$#';
    }

    private function notFound() {
        header('HTTP/1.0 404 Not Found');
        echo '404 Not Found';
    }
}

// Usage example:

$router = new SimpleRouter();

// Define dynamic routes
$router->addRoute('GET', '/api/user/{id}', function ($userId) {
    echo "Fetching user with ID: $userId";
});

$router->addRoute('POST', '/api/post/{id}', function ($postId) {
    echo "Creating a post with ID: $postId";
});

// Example request
$requestMethod = $_SERVER['REQUEST_METHOD'];
$requestUri = $_SERVER['REQUEST_URI'];

$router->match($requestMethod, $requestUri);
