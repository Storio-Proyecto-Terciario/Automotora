<?php
require_once 'vista/navegadorPrincipal.php';
session_start();

$seguro = true;
if (isset($_SESSION['id'])) {
    $tipo = 'Cliente';
    $usuario = $_SESSION['nombre'] . ' ' . $_SESSION['apellido'];
    $val = true;
} else {
    $tipo = 'Invitado';
    $usuario = 'Invitado';
    $val = false;
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css/nav.css">
    <link rel="stylesheet" href="css/formulario.css">
</head>

<body>
    <?php getNavegadorPrincipal($tipo, $usuario) ?>

    <?php
    if (isset($_GET['error'])) {
        echo 'alert("' . $_GET['error'] . '")';
    }
    if (isset($_GET['msg'])) {
        echo '<script>alert("' . $_GET['msg'] . '")</script>';
    }
    if (isset($_GET['accion']) && $seguro) {

        switch ($_GET['accion']) {
            case 'inicio':
                include 'vista/inicio.php';
                break;
            case 'iniciarSesion':
                $val ? header('Location: index.php') :
                    include 'vista/iniciarSesion.html';
                break;
            case 'registrarCliente':
                $val ? header('Location: index.php') :
                    include 'vista/registrarCliente.html';
                break;
            default:
                header('Location: index.php');
                break;
        }
    } else {
        echo '<h1>PAGINA PRINCIPAL </h1>';
    }

    ?>


</body>

</html>