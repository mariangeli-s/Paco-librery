<?php

require_once __DIR__ . '/../models/Empleado.php';
require_once __DIR__ . '/../models/Cliente.php';

class EmpleadoController {
    private $empleadoModel;
    private $clienteModel;

    public function __construct($db) {
        $this->empleadoModel = new Empleado($db);
        $this->clienteModel = new Cliente($db);
    }

    // Método para listar empleados
    public function listarEmpleados() {
        $empleados = $this->empleadoModel->obtenerEmpleados();
        include __DIR__ . '/../views/empleado/visualizarEmpleado.php';
    }

    // Método para mostrar el formulario de actualización de empleado
    public function mostrarFormularioActualizar($id) {
        $empleado = $this->empleadoModel->obtenerEmpleadoPorId($id);
        include __DIR__ . '/../views/empleado/actualizarEmpleado.php';
    }

    // Método para actualizar un empleado
    public function actualizarEmpleado() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? '';
            $data = $this->getEmpleadoDataFromRequest();

            if ($this->empleadoModel->actualizarEmpleado($id, $data)) {
                $this->redirectWithMessage('/empleados', 'Empleado actualizado con éxito');
            } else {
                $error = "Error al actualizar el empleado.";
                include __DIR__ . '/../views/empleado/actualizarEmpleado.php';
            }
        }
    }

    // Método para eliminar un empleado
    public function eliminarEmpleado() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? null;

            if ($id && $this->empleadoModel->eliminarEmpleado($id)) {
                $this->redirectWithMessage('/empleados', 'Empleado eliminado con éxito');
            } else {
                $error = "Error al eliminar el empleado.";
                include __DIR__ . '/../views/empleado/visualizarEmpleado.php';
            }
        }
    }

    // Método para mostrar el formulario de registro de empleado
    public function mostrarFormularioRegistro() {
        include __DIR__ . '/../views/empleado/registrarEmpleado.php';
    }

    // Método para registrar un empleado
    public function registrarEmpleado() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $this->getEmpleadoDataFromRequest();

            if ($this->empleadoModel->agregarEmpleado($data)) {
                $this->redirectWithMessage('/empleados', 'Empleado registrado con éxito');
            } else {
                $error = "Error al registrar el empleado.";
                include __DIR__ . '/../views/empleado/registrarEmpleado.php';
            }
        }
    }

    // Método para mostrar el formulario de actualización de cliente desde la vista del empleado
    public function mostrarFormularioActualizarCliente($id) {
        $cliente = $this->clienteModel->obtenerClientePorId($id);
        include __DIR__ . '/../views/empleado/actualizarCliente.php';
    }

    public function actualizarClientePorEmpleado() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? '';
            $nombre = trim($_POST['nombre']);
            $apellido = trim($_POST['apellido']);
            $correo = trim($_POST['correo']);
            $telefono = trim($_POST['telefono']);

            // Validar datos
            if (empty($nombre) || empty($apellido) || empty($correo) || empty($telefono)) {
                $error = 'Todos los campos son obligatorios.';
                include __DIR__ . '/../views/empleado/actualizarCliente.php';
                return;
            }

            // Actualizar en la base de datos
            $perfilActualizado = $this->clienteModel->actualizarCliente($id, $nombre, $apellido, $telefono, $correo);

            if ($perfilActualizado) {
                $this->redirectWithMessage('/empleados/infoClientes', 'Datos actualizados con éxito');
            } else {
                $error = 'Hubo un problema al actualizar los datos.';
                include __DIR__ . '/../views/empleado/actualizarCliente.php';
            }
            exit();
        }
    }

    private function getEmpleadoDataFromRequest() {
        return [
            'CIP' => $_POST['CIP'] ?? '',
            'nombre' => $_POST['nombre'] ?? '',
            'apellido' => $_POST['apellido'] ?? '',
            'nacionalidad' => $_POST['nacionalidad'] ?? '',
            'fecha_contrato' => $_POST['fecha_contrato'] ?? '',
            'fecha_nac' => $_POST['fecha_nac'] ?? '',
            'edad' => $_POST['edad'] ?? '',
            'sueldo' => $_POST['sueldo'] ?? '',
            'cargo' => $_POST['cargo'] ?? '',
            'telefono' => $_POST['telefono'] ?? '',
            'correo' => $_POST['correo'] ?? '',
            'contrasena' => password_hash($_POST['contrasena'] ?? '', PASSWORD_BCRYPT),
            'id_libreria' => $_POST['id_libreria'] ?? null
        ];
    }

    private function redirectWithMessage($url, $message) {
        header('Location: ' . $url . '?message=' . urlencode($message));
        exit();
    }
}
