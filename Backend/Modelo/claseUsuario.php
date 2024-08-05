<?php
require_once 'DB.php';

class Usuario
{
    protected $conexion;

    protected $id;
    protected $nombre;
    protected $apellido;
    protected $correo;
    protected $contrasena;

    private $tipoUsuario;

    public function __construct()
    {
        $this->conexion = Conectar::conexion();
    }


    public function getID()
    {
        return $this->id;
    }
    public function setID($id)
    {
        $this->id = $id;
        return $this;
    }

    public function getNombre()
    {
        return $this->nombre;
    }
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
        return $this;
    }

    public function getApellido()
    {
        return $this->apellido;
    }
    public function setApellido($apellido)
    {
        $this->apellido = $apellido;
        return $this;
    }

    public function getCorreo()
    {
        return $this->correo;
    }
    public function setCorreo($correo)
    {
        $this->correo = $correo;
        return $this;
    }

    public function getContrasena()
    {
        return $this->contrasena;
    }
    public function setContrasena($contrasena)
    {
        $this->contrasena = $contrasena;
        return $this;
    }

    public function getTipoUsuario()
    {
        return $this->tipoUsuario;
    }
    public function setTipoUsuario($tipoUsuario)
    {
        $this->tipoUsuario = $tipoUsuario;
        return $this;
    }


    private function existeUsuario()
    {
        $sql = "SELECT existe FROM usuarios WHERE correo = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("s", $this->correo); // "ss" indica que ambos parámetros son strings
        $stmt->execute();
        $resultado = $stmt->get_result();
        if ($resultado->num_rows > 0) {
            $re = $resultado->fetch_assoc();
            $stmt->close();
            if ($re['existe'] == 1) {
                return true;
            } else {
                return false;
            }
        } else {
            $stmt->close();
            return false;
        }
    }


    public function loginGoogle()
    {
        if ($this->existeUsuario()) { // Verifica si el usuario existe
            $this->verificarCorreo();
            $sql = "SELECT * FROM usuarios WHERE correo = '$this->correo'";
            $resultado = $this->conexion->query($sql);
            if ($resultado->num_rows > 0) {
                $re = $resultado->fetch_assoc();
                $resultado->free();
                $this->id = $re['usuarioId'];
                $this->nombre = $re['nombre'];
                $this->apellido = $re['apellido'];
                $this->correo = $re['correo'];
                $this->tipoUsuario = $re['tipoUsuario'];


                return true;
            } else {
                return false;
            }
        }
    }


    public function login()
    {

        if ($this->existeUsuario()) { // Verifica si el usuario existe
            $sql = "SELECT * FROM usuarios WHERE correo = ? AND contrasena = ?";
            $stmt = $this->conexion->prepare($sql);
            $stmt->bind_param("ss", $this->correo, $this->contrasena); // "ss" indica que ambos parámetros son strings
            $stmt->execute();
            $resultado = $stmt->get_result();
            if ($resultado->num_rows > 0) {
                $re = $resultado->fetch_assoc();
                $stmt->close();
                $this->contrasena = null;
                $this->id = $re['usuarioId'];
                $this->nombre = $re['nombre'];
                $this->apellido = $re['apellido'];
                $this->correo = $re['correo'];
                $this->tipoUsuario = $re['tipoUsuario'];

                return true;
            } else {
                $stmt->close();
                return false;
            }
        } else {
            return false;
        }
    }

    public function valCorreo()
    {
        $sql = "SELECT correoVal FROM usuarios WHERE correo = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("s", $this->correo); // "s" indica que el parámetro es un string
        $stmt->execute();
        $resultado = $stmt->get_result();
        if ($resultado->num_rows > 0) {
            $re = $resultado->fetch_assoc();
            $stmt->close();
            if ($re['correoVal'] == 1) {
                return true;
            } else {
                return false;
            }
        } else {
            $stmt->close();
            return false;
        }
    }

    public function verificarCorreo()
    {

        if (!$this->valCorreo()) {
            $correoVal = 1;
            $sql = "UPDATE usuarios SET correoVal = ? WHERE correo = ?";
            $stmt = $this->conexion->prepare($sql);
            $stmt->bind_param("is",$correoVal , $this->correo); // "s" indica que el parámetro es un string
            $stmt->execute();
            $resultado = $stmt->get_result();
            if ($resultado->num_rows > 0) {
                $stmt->close();
                return true;
            } else {
                $stmt->close();
                return false;
            }
        }
        return true;
    }
}
