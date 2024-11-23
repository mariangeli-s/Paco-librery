<?php

use FastRoute\RouteCollector;

function configurarRutas()
{
    return FastRoute\simpleDispatcher(function (RouteCollector $r) {
        // Rutas para el home
        $r->addRoute('GET', '/', [HomeController::class, 'index']);
        $r->addRoute('GET','/home.php', [HomeController::class,'index']);

        //rutas para el cliente
        $r->addRoute('GET','/cliente/registro',[ClientesController::class,'mostrarRegistro']);
        $r->addRoute('POST','/cliente/registro',[ClientesController::class,'crearCliente']);
        
        $r->addRoute('GET', '/cliente/login', [ClientesController::class, 'mostrarLogin']);//muestra log in cliente
        $r->addRoute('POST', '/cliente/login', [ClientesController::class, 'procesarLogin']);

        // rutas sobre la factura
        $r->addRoute('GET','/catalogo', [VentasController:: class,'mostrarCatalogo']);
        $r->addRoute('GET','/comprar/{isbn}', [VentasController::class, 'verCompra']);
        $r->addRoute('GET','/factura-detalles', [VentasController::class,'detallesCompra']);

        //rutas para editorial
        $r->addRoute('GET','/editorial/editoriales', [EditorialesController::class,'verEditorial']);
    });
}
