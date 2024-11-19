<?php

// Autoload de Composer (si usas Composer)
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../app/routes.php';
//toda la definicion de los controladores
require_once __DIR__ .'/../app/controllers/HomeController.php';
require_once __DIR__ .'/../app/controllers/ClientesController.php';

// Configurar rutas
$dispatcher = configurarRutas();

// Obtener la solicitud HTTP
$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

// Eliminar cualquier query string de la URI
if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);

// Despachar la solicitud
$routeInfo = $dispatcher->dispatch($httpMethod, $uri);
switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        // Manejar error 404 (ruta no encontrada)
        http_response_code(404);
        echo "Página no encontrada";
        break;

    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        // Manejar error 405 (método no permitido)
        $allowedMethods = $routeInfo[1];
        http_response_code(405);
        echo "Método no permitido. Métodos permitidos: " . implode(', ', $allowedMethods);
        break;

    case FastRoute\Dispatcher::FOUND:
        // Obtener controlador, método y parámetros
        [$controller, $method] = $routeInfo[1];
        $params = $routeInfo[2];

        // Instanciar el controlador y llamar al método correspondiente
        $controllerInstance = new $controller();
        call_user_func_array([$controllerInstance, $method], $params);
        break;
}