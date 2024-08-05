<?php

// Initialize the session
session_start();



//require 'vendor/autoload.php';
// Update the following variables
$google_oauth_client_id = '867981729326-if7o8hu8mq8mnaenqhnfrdjjfqu5fah0.apps.googleusercontent.com';
$google_oauth_client_secret = '';
$google_oauth_redirect_uri = 'http://localhost:81/google-oauth.php';
$google_oauth_version = 'v3';


// If the captured code param exists and is valid
if (isset($_GET['code']) && !empty($_GET['code'])) {
    // Execute cURL request to retrieve the access token
    $params = [
        'code' => $_GET['code'],
        'client_id' => $google_oauth_client_id,
        'client_secret' => $google_oauth_client_secret,
        'redirect_uri' => $google_oauth_redirect_uri,
        'grant_type' => 'authorization_code'
    ];
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://accounts.google.com/o/oauth2/token');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    if (curl_errno($ch)) {
        echo 'Error:' . curl_error($ch);
    }
    curl_close($ch);
    $response = json_decode($response, true);
    // Make sure access token is valid
    if (isset($response['access_token']) && !empty($response['access_token'])) {
        // Execute cURL request to retrieve the user info associated with the Google account
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://www.googleapis.com/oauth2/' . $google_oauth_version . '/userinfo');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Bearer ' . $response['access_token']]);
        $response = curl_exec($ch);
        curl_close($ch);
        $profile = json_decode($response, true);
        // Make sure the profile data exists
        if (isset($profile['email'])) {
            $google_name_parts = [];
            $google_name_parts[] = isset($profile['given_name']) ? preg_replace('/[^a-zA-Z0-9]/s', '', $profile['given_name']) : '';
            $google_name_parts[] = isset($profile['family_name']) ? preg_replace('/[^a-zA-Z0-9]/s', '', $profile['family_name']) : '';
            // Authenticate the user
  

            $datos = array(
                'accion' => 1, // Accion para iniciar sesion con google
                'google_loggedin' => true,
                'google_email' => $profile['email']
            );

            $options = array(
                'http' => array(
                    'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                    'method'  => 'POST',
                    'content' => http_build_query($datos),
                    'timeout' => 60,  // Tiempo de espera en segundos, si no se recibe respuesta se genera un error,
                ),
            );
            $context  = stream_context_create($options);
            $result = file_get_contents('http://backend/endpoint.php', false, $context);

            if ($result === FALSE) {
                // Maneja el error
                $dato = "Error en la solicitud";
            } else {
                // Elimina el texto "Conectado exitosamente" de la respuesta
                $json = str_replace("Connected successfully", "", $result);

                // Decodifica la respuesta JSON a una variable PHP
                $datos = json_decode($json, true);


                if (isset($datos['estado'])) {
                    $_SESSION['id'] = $datos['usuarioId']; // Guarda el id del usuario en la sesion
                    $_SESSION['nombre'] = $datos['nombre']; // Guarda el nombre del usuario en la sesion
                    $_SESSION['apellido'] = $datos['apellido']; // Guarda el apellido del usuario en la sesion
                    $_SESSION['correo'] = $datos['correo']; // Guarda el correo del usuario en la sesion
                    header('Location: index.php');
                    exit;
                } else {

                    header('Location: index.php?error=error al iniciar sesion con google');
                    exit;
                }
            }
            // Redirect to profile page

            exit;
        } else {
            header('Location: index.php?mensaje=Error al iniciar sesion con google');
        }
    } else {

        header('Location: index.php?mensaje=Error al iniciar sesion con google');
    }
} else {
    // Define params and redirect to Google Authentication page
    $params = [
        'response_type' => 'code',
        'client_id' => $google_oauth_client_id,
        'redirect_uri' => $google_oauth_redirect_uri,
        'scope' => 'https://www.googleapis.com/auth/userinfo.email https://www.googleapis.com/auth/userinfo.profile',
        'access_type' => 'offline',
        'prompt' => 'consent'
    ];
    header('Location: https://accounts.google.com/o/oauth2/auth?' . http_build_query($params));
    exit;
}
