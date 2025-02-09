<?php 
class Rooter {
    private $routes = [];

    public function getRoutes($Request, $methode) {
        array_push($this->routes, [$Request, $methode]);
    }

    public function add($method, $path, $controller, $controllerMethod) {
        $this->routes[] = [
            'method' => strtoupper($method), 
            'path' => $path, 
            'controller' => $controller,
            'controllerMethod' => $controllerMethod
        ];
    }

    public function dispatch($requestMethod, $requestUri) {
        // Supprimer les query strings de l'URI si présents
        $requestUri = parse_url($requestUri, PHP_URL_PATH);
        
        foreach ($this->routes as $route) {
            // Convertir la route en expression régulière
            $routePattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '([^/]+)', $route['path']);
            $routePattern = str_replace('/', '\/', $routePattern);
            
            if (preg_match("/^" . $routePattern . "$/", $requestUri, $matches)) {
                if (strtoupper($route['method']) === $requestMethod) {
                    array_shift($matches); // Enlever la première correspondance (match complet)
                    
                    $controllerInstance = new $route['controller']();
                    return call_user_func_array(
                        [$controllerInstance, $route['controllerMethod']], 
                        $matches
                    );
                }
            }
        }
        
        // Si aucune route ne correspond
        header("HTTP/1.0 404 Not Found");
        echo "404 - Page Not Found";
    }

    public function run() {
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        $requestUri = $_SERVER['REQUEST_URI'];

        $this->dispatch($requestMethod, $requestUri);
    }
}
?>