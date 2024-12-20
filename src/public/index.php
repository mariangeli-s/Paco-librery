<?php

// Autoload de Composer (si usas Composer)
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../app/routes.php';
require_once __DIR__ . '/../app/config/config.php';

require_once __DIR__.'/../app/config/config.php';
// Toda la definición de los controladores
require_once __DIR__ . '/../app/controllers/HomeController.php';
require_once __DIR__ . '/../app/controllers/ClientesController.php';
require_once __DIR__ . '/../app/controllers/VentasController.php';
require_once __DIR__ . '/../app/controllers/LibreriasController.php';
require_once __DIR__ . '/../app/controllers/EditorialesController.php';
require_once __DIR__ . '/../app/controllers/CarritoController.php';
require_once __DIR__ .'/../app/controllers/InventarioController.php';
require_once __DIR__ . '/../app/controllers/PagoController.php';
require_once __DIR__ . '/../app/controllers/FacturaController.php';
require_once __DIR__ . '/../app/controllers/InventarioApiController.php';
require_once __DIR__ .'/../app/controllers/AdministradorController.php';
require_once __DIR__ . '/../app/controllers/EmpleadoController.php';


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

        // Si es una solicitud POST, obtener los parámetros del cuerpo de la solicitud
        if ($httpMethod === 'POST') {
            $params = array_merge($params, $_POST);
        }

        // Instanciar el controlador y llamar al método correspondiente
        $controllerInstance = new $controller($pdo);

        // Asegúrate de que los parámetros se están pasando correctamente
        try {
            $reflectionMethod = new ReflectionMethod($controllerInstance, $method);
            $reflectionParams = $reflectionMethod->getParameters();
            $paramsToPass = [];

            foreach ($reflectionParams as $param) {
                $paramName = $param->getName();
                if (isset($params[$paramName])) {
                    $paramsToPass[] = $params[$paramName];
                } elseif ($param->isDefaultValueAvailable()) {
                    $paramsToPass[] = $param->getDefaultValue();
                } else {
                    throw new ArgumentCountError("Falta el parámetro requerido: $paramName");
                }
            }

            call_user_func_array([$controllerInstance, $method], $paramsToPass);
        } catch (ArgumentCountError $e) {
            http_response_code(400);
            echo "Error en los parámetros: " . $e->getMessage();
        } catch (TypeError $e) {
            http_response_code(400);
            echo "Error en los tipos de parámetros: " . $e->getMessage();
        } catch (ReflectionException $e) {
            http_response_code(500);
            echo "Error en la reflexión del método: " . $e->getMessage();
        }
        break;
}
