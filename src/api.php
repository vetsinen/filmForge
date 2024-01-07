<?php
require_once (__DIR__.'/vendor/autoload.php');
require_once (__DIR__.'/validate.php');
class Router {
    private $routes = [];
    private $filmModel;

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
                array_shift($matches);
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
        echo 'route Not Found';
    }
}

$filmModel = new \Webdev\Filmforge\FilmModel(new \Webdev\Filmforge\GenericQuery());
$router = new Router();

$router->addRoute('GET', '/api.php/films', function () use ($filmModel) {
    $jsonString = json_encode($filmModel->getList());
    echo $jsonString;
});

$router->addRoute('GET', '/api.php/films/title/{title}', function ($title) use($filmModel) {
    $title = clearString($title);
    if (strlen($title)>1)
        echo json_encode(['status'=>'ok', 'data'=>$filmModel->getByTitle($title)]);
    else
        echo json_encode(['status'=>'error', 'msg'=>'too short search string']);
});

$router->addRoute('GET', '/api.php/films/actor/{fullname}', function ($fullname) use($filmModel) {
    echo json_encode($filmModel->getByTitle($fullname));
});

$router->addRoute('POST', '/api.php/films', function () use ($filmModel) {
    $data = filmDataValidator(json_decode(file_get_contents('php://input'), true));

    $id = $filmModel->addFilm($data);
    echo json_encode(['msg'=>"Creating a film with id: $id"]);
//    echo json_encode($data);
});

// Set the content type to JSON
header('Content-Type: application/json');
$requestMethod = $_SERVER['REQUEST_METHOD'];
$requestUri = $_SERVER['REQUEST_URI'];
error_log(" $requestMethod, $requestUri ");
$router->match($requestMethod, $requestUri);
