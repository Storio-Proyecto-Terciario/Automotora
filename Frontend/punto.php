<?php
session_start();
if (!isset($_POST['accion'])) {
    header('Location: index.php?error=1');
    die();
}
function solicitud($array)
{
     $options = array(
        'http' => array(
            'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
            'method'  => 'POST',
            'content' => http_build_query($array),
            'timeout' => 60,  // Tiempo de espera en segundos, si no se recibe respuesta se genera un error,
        ),
    );
    $context  = stream_context_create($options);
    $result = file_get_contents('http://backend/endpoint.php', false, $context);

    if ($result === FALSE) {
        // Maneja el error
        return "Error en la solicitud";
    } else {
        // Elimina el texto "Conectado exitosamente" de la respuesta
        $json = str_replace("Connected successfully", "", $result);

        // Decodifica la respuesta JSON a una variable PHP
        $datos = json_decode($json, true);
        // Ahora se puede acceder a los datos como un array de PHP
        return $datos;
    }


}





switch ($_POST['accion']) {
    case '1':
        $resultado = solicitud($_POST);
    
        if ($resultado['estado'] == 'true') {
            $_SESSION['id'] = $resultado['usuarioId']; // Guarda el id del usuario en la sesion
            $_SESSION['nombre'] = $resultado['nombre']; // Guarda el nombre del usuario en la sesion
            $_SESSION['apellido'] = $resultado['apellido']; // Guarda el apellido del usuario en la sesion
            $_SESSION['correo'] = $resultado['correo']; // Guarda el correo del usuario en la sesion
            header('Location: index.php');
            exit;

        }else{
            
            header('Location: index.php?error='.$resultado['msg']);
            exit;
        }


        
        break;
    case '2':
        $resultado = solicitud($_POST);
        
       header('Location: index.php?msg=' . $resultado['msg']);
        break;
    default:
        //header('Location: index.php?error=2');
        break;
}
