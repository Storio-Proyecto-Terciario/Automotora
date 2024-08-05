<?php

require_once 'DB.php';
require_once 'claseUsuario.php';


class Cliente extends Usuario {
    public function __construct() {
        parent::__construct(); // Llama al constructor de Usuario
        // Inicialización adicional para Cliente
    }

    public function registrarCliente() {
        $sql = "CALL registrarCliente (?,?,?,?)";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("ssss",$this->correo,  $this->nombre, $this->apellido, $this->contrasena);
        if ($stmt->execute()) {
            $stmt->close();
            return true;
        } else {
            $stmt->close();
            return false;
        }

    }
}