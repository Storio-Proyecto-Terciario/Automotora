<?php
session_start();

if (isset($_POST['accion'])) {
    $url = 'http://localhost/';
    $data = $_POST; // Tomar los datos recibidos y reenviarlos

    switch ($_POST['accion']) {
        case 1:
            $url = 'http://localhost/Controlador/inicio-de-secion.php';
            $msg = 'Inicio de sesion de cliente ' . $_POST['correo'] ;
            break;
        case 2:
            $url = 'http://localhost/Controlador/registro-usuario.php';
            $msg = 'Registro de cliente ' . $_POST['correo'] ;
            
            break;
    }

    if ($url) {
        // Inicializar sesión cURL
        $ch = curl_init($url);
        // Configurar opciones de cURL
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        // Ejecutar sesión cURL
        $response = curl_exec($ch);
        // Cerrar sesión cURL
        curl_close($ch);
        $text = date('d-m-Y H:i:s') . " - " . $msg . $response." \n";
            file_put_contents('log.txt', $text, FILE_APPEND);
        // Manejar la respuesta
        echo $response;
        exit;
    }
}



/*
if (isset($_SESSION['accion'])) {
    $text = date('d-m-Y H:i:s') . " - Respuesta registro" . $_SESSION['accion']." \n";
            file_put_contents('log.txt', $text, FILE_APPEND);
    switch($_SESSION['accion']){
        case 1:
            $array = array(
                'id' => $_SESSION['id'], 
                'nombre' => $_SESSION['nombre'], 
                'apellido' => $_SESSION['apellido'], 
                'correo' => $_SESSION['correo']
            );
            echo json_encode($array);
        break;
        case 'Registro de cliente':

            $text = date('d-m-Y H:i:s') . " - Respuesta registro" . $_SESSION['mensage']." \n";
            file_put_contents('log.txt', $text, FILE_APPEND);

            $datos = array(
                'msg' => $_SESSION['mensage'],
                'accion' => 2
            );

            var_dump($datos);
            echo json_encode($datos);
            exit();
        break;
    }
}

if (isset($_POST['accion'])) {
    switch ($_POST['accion']) {
        case 1:
            $text = date('d-m-Y H:i:s') . " - iniciar secion\n";
            file_put_contents('log.txt', $text, FILE_APPEND);

            
            
            header('Location: Controlador/Inicio-de-secion.php?correo=' . $_POST['correo'] . '&contrasena=' . $_POST['contrasena'] . '&accion=1');
            break;
        case 2:
            $text = date('d-m-Y H:i:s') . " - Registrarse\n";
            file_put_contents('log.txt', $text, FILE_APPEND);
            $text = date('d-m-Y H:i:s') . " - Registrarse Controlador/registro-usuario.php?nombre=" . $_POST['nombre'] . "&apellido=". $_POST['apellido'] . "&correo=" . $_POST['correo'] . "&contrasena=" . $_POST['contrasena'] . "&accion=2\n";
            file_put_contents('log.txt', $text, FILE_APPEND);
            
            
          /*  $_SESSION['nombre'] = $_POST['nombre'];
            $_SESSION['apellido'] = $_POST['apellido'];
            $_SESSION['correo'] = $_POST['correo'];
            $_SESSION['contrasena'] = $_POST['contrasena'];
            $_SESSION['accion'] = 2;
           
            header('Location: Controlador/registro-usuario.php?nombre=' . $_POST['nombre'] . "&apellido=". $_POST['apellido'] . "&correo=" . $_POST['correo'] . "&contrasena=" . $_POST['contrasena'] . '&accion=2');
            exit();
            break;
        default:
        echo json_encode(array('msg' => 'Error con el proceso'));
            break;
    }
}*/


