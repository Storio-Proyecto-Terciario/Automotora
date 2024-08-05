<?php
require_once '../Modelo/claseCliente.php';
$text = date('d-m-Y H:i:s') . " - Control registro\n";
file_put_contents('log.txt', $text, FILE_APPEND);
session_start();

if (isset($_POST['accion']) && $_POST['accion'] == 2) {

    $_SESSION['accion'] = "Registro de cliente";

    $cliente = new Cliente();
    $cliente->setNombre($_POST['nombre']);
    $cliente->setApellido($_POST['apellido']);
    $cliente->setCorreo($_POST['correo']);
    $cliente->setContrasena($_POST['contrasena']);
    $resultado = $cliente->registrarCliente();
    if ($resultado) {
       

        $text = date('d-m-Y H:i:s') . " - Registro exitoso\n";
        file_put_contents('log.txt', $text, FILE_APPEND);

        echo json_encode(array('estado' => 'true', 'msg' => 'Registro exitoso, inicie sesion'));
        exit();
    } else {
      

        $text = date('d-m-Y H:i:s') . " - [Error con el registro]\n";
        file_put_contents('log.txt', $text, FILE_APPEND);
        
        echo json_encode(array('estado' => 'true', 'msg' => 'Error con el registro.'));
        exit();
    }
} else {
    $text = date('d-m-Y H:i:s') . " - Error en la validacion del proceso registro\n";
    file_put_contents('log.txt', $text, FILE_APPEND);
}
