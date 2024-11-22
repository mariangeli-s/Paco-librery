<?php

require_once __DIR__ ."/../models/Venta.php";

class VentasController {

    private $ventaModel;

    public function __construct($db) {
        $this-> ventaModel = new Venta($db);
    }

    //mostrar el catalogo
    public function mostrarCatalogo() {
        $catalogo = $this->ventaModel->getCatalogo();
        include __DIR__ . '/../views/Compras/catalogo.php';
    }

    // Mostrar la vista de compra
    public function verCompra($isbn) {
        $Libro = $this->ventaModel->getLibroISBN($isbn);
        if (!$Libro) {
            die("El libro no existe.");
        }
        require __DIR__. '/../views/Compras/comprar.php';
    }

    public function detallesCompra() {
        include __DIR__ ."/../views/Compras/VerDetalles.html";
    }
}
