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

$gc = new \Webdev\Filmforge\GenericQuery();
$filmModel = new \Webdev\Filmforge\FilmModel($gc);
$userModel = new \Webdev\Filmforge\UserModel($gc);

$router = new Router();

$router->addRoute('GET', '/api.php/films', function () use ($filmModel) {
    $jsonString = json_encode(['page'=>1, 'items'=>$filmModel->getList()]);
    echo $jsonString;
});

$router->addRoute('DELETE', '/api.php/films/{id}', function ($id) use($filmModel) {
    $id = strval(clearString(urldecode($id)));
    $filmModel->deleteFilm($id);
    echo json_encode(['status'=>'ok', 'data'=>$id]);
});

$router->addRoute('GET', '/api.php/films/title/{title}', function ($title) use($filmModel) {
    $title = clearString(urldecode($title));
    if (strlen($title)>1)
        echo json_encode(['status'=>'ok', 'data'=>$filmModel->getByTitle($title)]);
    else
        echo json_encode(['status'=>'error', 'msg'=>'too short search string']);
});

$router->addRoute('GET', '/api.php/films/actor/{fullname}', function ($fullname) use($filmModel) {
    $fullname = clearString(urldecode($fullname));
    echo json_encode($filmModel->getByActor($fullname));
});

$router->addRoute('POST', '/api.php/films', function () use ($filmModel) {
    $data = filmDataValidator(json_decode(file_get_contents('php://input'), true));

    $id = $filmModel->addFilm($data);
    echo json_encode(['msg'=>"Creating a film with id: $id"]);
//    echo json_encode($data);
});

$router->addRoute('POST', '/api.php/auth/register', function () use ($userModel) {
    $data = filmDataValidator(json_decode(file_get_contents('php://input'), true));
    echo json_encode(['msg'=>"error, while creation user"]); return;

    $id = $userModel->addUser($data);
    if ($id) echo json_encode(['msg'=>"Creating a user with id: $id"]);
    else echo json_encode(['msg'=>"error, while creation user"]);
});

$router->addRoute('POST', '/api.php/auth/login', function () use ($userModel) {
    $data = (json_decode(file_get_contents('php://input'), true));
    error_log('user: '.json_encode($data));

    $rez = $userModel->loginUser($data);
    if (!$rez) {echo json_encode(['status'=>'error', 'msg'=>"error, while logining user"]); return;}
    echo json_encode(['id'=>$rez,'status'=>'ok', 'msg'=>"logined user $rez"]); return;
});

// Set the content type to JSON
header('Content-Type: application/json');
$requestMethod = $_SERVER['REQUEST_METHOD'];
$requestUri = $_SERVER['REQUEST_URI'];
error_log(" $requestMethod, $requestUri ");
$router->match($requestMethod, $requestUri);
