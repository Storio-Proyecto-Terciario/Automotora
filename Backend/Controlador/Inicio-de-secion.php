<?php
require_once '../Modelo/claseUsuario.php';

session_start();
$text = date('d-m-Y H:i:s') . " - Control secion " . $_POST['accion'] . "\n";

file_put_contents('log.txt', $text, FILE_APPEND);

$usuario = new Usuario();

if (isset($_POST['accion'] ) && $_POST['accion'] == 1) {
    if (isset($_POST['google_loggedin'])) {
        $usuario->setCorreo($_POST['google_email']);
        $usuario->loginGoogle() ? iniciarSesion($usuario) : error();
    } else {
        $usuario->setCorreo($_POST['correo']);
        $usuario->setContrasena($_POST['contrasena']);

        $usuario->login() ? iniciarSesion($usuario) : error();
    }
}

function iniciarSesion($usuario)
{
    $datos = array(
        'estado' => true,
        'usuarioId' => $usuario->getID(),
        'nombre' => $usuario->getNombre(),
        'apellido' => $usuario->getApellido(),
        'correo' => $usuario->getCorreo(),
        'tipoUsuario' => $usuario->getTipoUsuario()

    );

    echo json_encode($datos);
}

function error()
{
    echo json_encode(array('estado' => 'false', 'msg' => 'Error con el inicio de secion'));
}
