<?php

class User {
    private $db_host;
    private $db_username;
    private $db_password;
    private $db_name;

    public function __construct() {
        require_once('connect.php');
        $this->db_host = $db_host;
        $this->db_username = $db_username;
        $this->db_password = $db_password;
        $this->db_name = $db_name;
    }

    public function authenticate($username, $password) {
    // Conexión a la base de datos
    require_once('token.php');
    $CONN = new ConexionMySQL($this->db_host, $this->db_username, $this->db_password, $this->db_name);
    $CONN->connectDb();
    $conn = $CONN->conexion;

    // Verificar si la conexión fue exitosa o no
    if ($conn->connect_error) {
        die("La conexión ha fallado: " . $conn->connect_error);
    }

    // Encriptar la contraseña usando SHA-256
    $password = hash('sha256', $password);

    // Consulta SQL para obtener los datos del usuario
    $sql = "SELECT * FROM usuarios WHERE usuario = '$username'";
    $result = $conn->query($sql);

    // Verificar si se encontró un usuario con ese nombre
    if ($result->num_rows == 1) {
        $fila = $result->fetch_assoc();
        $id = $fila["id_usuario"];
        $intentosFallidos = $fila["intentos_fallidos"];

        // Verificar si la cuenta está bloqueada
        if ($fila["estado"] === 'bloqueado') {
            return "Cuenta bloqueada";
        } else {
            // Verificar la contraseña
            $storedPassword = $fila["clave"];
            if ($password == $storedPassword) {
                // La contraseña es correcta, restablecer los intentos fallidos
                $this->resetFailedLoginAttempts($id, $conn);
                
                // Verificar el tipo de usuario
                $userType = $fila["tipo"];
                if ($userType == 1) {
                    $_SESSION['tipo'] = 1;
                }
                
                $token = new Token();
                return $token->create($id);
            } else {
                // La contraseña es incorrecta, registrar un intento fallido
                $this->recordFailedLoginAttempt($id, $conn);
                return "Contraseña incorrecta";
            }
        }
    } else {
        // El usuario no existe
        return "Usuario no encontrado";
    }

    // Cerrar la conexión a la base de datos
    $CONN->desconnect();
}

    private function getFailedLoginAttempts($userId, $conn) {
        $sql = "SELECT intentos_fallidos FROM usuarios WHERE id_usuario = $userId";
        $result = $conn->query($sql);
        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            return $row["intentos_fallidos"];
        } else {
            return 0;
        }
    }

    private function recordFailedLoginAttempt($userId, $conn) {
        $intentosFallidos = $this->getFailedLoginAttempts($userId, $conn);

        // Incrementar el número de intentos fallidos
        $intentosFallidos += 1;

        // Actualiza el número de intentos fallidos en la tabla usuarios
        $sql = "UPDATE usuarios SET intentos_fallidos = $intentosFallidos WHERE id_usuario = $userId";

        if ($intentosFallidos >= 3) {
            // Si se alcanzan 3 intentos fallidos, bloquea la cuenta
            $sql = "UPDATE usuarios SET intentos_fallidos = $intentosFallidos, estado = 'bloqueado' WHERE id_usuario = $userId";
        }

        $conn->query($sql);
    }

    private function resetFailedLoginAttempts($userId, $conn) {
        // Restablece el número de intentos fallidos y el estado en la tabla usuarios
        $sql = "UPDATE usuarios SET intentos_fallidos = 0, estado = 'activo' WHERE id_usuario = $userId";
        $conn->query($sql);
    }

    public function create($username, $nombre, $pregunta, $re_password, $password, $email) {
        require_once('token.php');

        // Verificar la longitud de la contraseña
        if (strlen($password) < 8) {
            return false;
        }

        $CONN = new ConexionMySQL($this->db_host, $this->db_username, $this->db_password, $this->db_name);
        $CONN->connectDb();
        $conn = $CONN->conexion;

        // Verificar si la conexión fue exitosa o no
        if ($conn->connect_error) {
            die("Error en la conexión: " . $conn->connect_error);
        }

        // Encriptar la contraseña y la respuesta usando SHA-256
        $password = hash('sha256', $password);
        $re_password = hash('sha256', $re_password);
        // Consulta SQL para insertar los datos del usuario
        $sql = "INSERT INTO usuarios (usuario, nombre, clave, email, pregunta, respuesta) VALUES ('$username', '$nombre', '$password', '$email', '$pregunta', '$re_password')";
        $result = $conn->query($sql);

        if ($result) {
            $id = $conn->insert_id;
            echo "El ID del insert es: " . $id;
        } else {
            echo "Error al ejecutar la consulta: " . $conn->error;
        }

        $token = new Token();
        return $token->create($id);

        // Cerrar la conexión a la base de datos
        $CONN->desconnect();
        return false;
    }
}
?>