<?php
session_start();
//detectar sesion para determinar pefil
$perfilCliente= isset($_SESSION['cliente_id']) && isset($_SESSION['email']);
//aqui debe ir  los parametros de la sesion de admin admin_id  email
$perfilAdmin= isset($_SESSION['admin_id']) && isset($_SESSION['correo']);
?>
<link rel="stylesheet" href="/css/header_footerEmpleado.css">
<body>
    <header>
        <!--LOGO Y REDES-->
        <div class="LogoRedes">
            <div class="logo">
                <img src="/images/LOGO.png" alt="Librerí­a ¡Donde Paco!">
            </div>
        </div>

        <div class="Menu">
            <!--HEADER PARA VISITANTE-->
            <?php if (!$perfilCliente && !$perfilAdmin): ?>
                <ul>
                    <li><a href="/">Home</a></li>
                    <li><a href="/catalogo">Catálogo</a></li>
                    <li><a href="/sobrenosotros">Sobre Nosotros</a></li>
                    <li><a href="/contacto">Contacto</a></li>
                </ul>
            <div class="Usuario">
                <a href="/perfiles"><img src="/images/Usuario.png" alt="Usuario"></a>
                <a href="/perfiles">Iniciar Sesión</a>
            </div>

            <!--HEADER PARA EMPLEADO-->
            <?php elseif ($perfilAdmin): ?>
                <ul>
                    <li><a href="/home.php">Home</a></li>
                    <li><a href="/inventario">Inventario</a></li>
                    <li><a href="/sobrenosotros">Sobre Nosotros</a></li>
                    <li><a href="/contacto">Contacto</a></li>
                    <li class="dropdown">
                        <a href="javascript:void(0)" class="dropbtn">Más</a>
                        <div class="dropdown-content">
                            <a href="/empleados/infoClientes">Clientes</a>
                            <a href="/empleados">Empleados</a>
                            <a href="/libreria">Librerías</a>
                            <a href="/editorial">Editoriales</a>
                        </div>
                    </li>
                </ul>
                <div class="Usuario">
                    <a href="/admin/perfil"><img src="/images/Usuario.png" alt="Usuario"></a>
                    <a href="/admin/perfil">Mi cuenta</a>
                </div>

            <!--HEADER PARA CLIENTE-->
            <?php elseif ($perfilCliente): ?>
                <ul>
                    <li class="username">¿Qué buscas hoy?, <?php echo htmlspecialchars($_SESSION['nombre']); ?></li>
                    <li><a href="/home.php">Home</a></li>
                    <li><a href="/catalogo">Catálogo</a></li>
                    <li><a href="/sobrenosotros">Sobre Nosotros</a></li>
                    <li><a href="/contacto">Contacto</a></li>
                </ul>
                <div class="Usuario">
                    <a href="/carrito" class="carrito"><img src="../images/shopcart.png" alt="carrito"></a>
                    <a href="/cliente/perfil"><img src="../images/Usuario.png" alt="Usuario"></a>
                    <a href="/cliente/perfil">Mi cuenta</a>

                </div>
            <?php endif; ?>
        </div>
    </header>
</body>
