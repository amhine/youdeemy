<?php 

class Rooter
{
    private $routes = [];
    public function getRoutes($Request , $methode){
        array_push($this->routes,[$Request , $methode]);
    }

    public function dispatch($request)
{
    foreach ($this->routes as $route => $callback) {
        $routePattern = preg_replace('/{([a-zA-Z0-9]+)}/', '([^/]+)', $route);


        if (preg_match("#^$routePattern$#", $request, $matches)) {
            array_shift($matches);
            return call_user_func_array($callback, $matches);
        }
    }
    echo "404 - Page Not Found";
}

}

?>