<?php

class Pago {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function guardarVenta($clienteId, $total) {
        $query = "INSERT INTO Venta (ID_cliente_v, costo_total) VALUES (:clienteId, :total)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':clienteId', $clienteId);
        $stmt->bindParam(':total', $total);
        $stmt->execute();
        return $this->db->lastInsertId();
    }

    public function guardarDetalleVenta($ventaId, $isbn, $cantidad, $precio) {
        $query = "INSERT INTO VentaDetalle (ID_venta, ISBN, cantidad, precio) VALUES (:ventaId, :isbn, :cantidad, :precio)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':ventaId', $ventaId);
        $stmt->bindParam(':isbn', $isbn);
        $stmt->bindParam(':cantidad', $cantidad);
        $stmt->bindParam(':precio', $precio);
        $stmt->execute();
    }

    public function guardarPago($ventaId, $metodoPago, $monto, $estado) {
        $query = "INSERT INTO Pago (ID_venta, metodo_pago, monto, estado) VALUES (:ventaId, :metodoPago, :monto, :estado)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':ventaId', $ventaId);
        $stmt->bindParam(':metodoPago', $metodoPago);
        $stmt->bindParam(':monto', $monto);
        $stmt->bindParam(':estado', $estado);
        $stmt->execute();
    }
}
