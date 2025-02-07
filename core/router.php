<?php 

class Rooter{
    private $routes = [];


    public function getRoutes($Request , $methode){
        array_push($this->routes,[$Request , $methode]);
    }

    public function add($method, $path, $controller, $controllerMethod) {
        $this->routes[] = [
            'method' => strtoupper($method), 
            'path' => $path, 
            'controller' => $controller,
            'controllerMethod' => $controllerMethod
        ];
    }

    public function dispatch($requestMethod, $requestUri){
        
        foreach ($this->routes as $route) {
            $routePattern = preg_replace('/{([a-zA-Z0-9]+)}/', '([^/]+)', $route['path']);
            
            
            if (preg_match("#^$routePattern$#", $requestUri, $matches)) {
                array_shift($matches); 
    
                if (strtoupper($route['method']) == $requestMethod) {
                    $controllerInstance = new $route['controller']();
                    return call_user_func_array([$controllerInstance, $route['controllerMethod']], $matches);
                }
            }
        }
        echo "404 - Page Not Found";
    }
public function run() {
    $requestMethod = $_SERVER['REQUEST_METHOD'];
    $requestUri = $_SERVER['REQUEST_URI'];

    $this->dispatch($requestMethod, $requestUri);
}

}

?>