<?php

function getNavegadorPrincipal($tipo, $usuario){

    switch ($tipo) {
        case 'Cliente':
        ?>
            <nav>
                <a class="navOp" href="index.php">Inicio</a>
                <a class="navOp" href="cerrarSecion.php">Cerara secion</a>
                <a class="navOp usuario" href="#"><?=$usuario ?></a>
            </nav>
        <?php
        break;
        default:
        ?>
            <nav>
                <a class="navOp" href="index.php">Inicio</a>
                <a class="navOp" href="index.php?accion=iniciarSesion">Iniciar Secion</a>
                <a class="navOp" href="index.php?accion=registrarCliente">Registrar Usuario</a>
                
            </nav>
        <?php
        break;

    }
}
?>