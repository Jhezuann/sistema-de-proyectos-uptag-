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

    // Función para autenticar un usuario
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


        // Encriptar la contraseña y la respuesta usando SHA-256
        $password = hash('sha256', $password);
        $resp = hash('sha256', $resp);

        // Consulta SQL para obtener los datos del usuario

        $sql = "SELECT * FROM usuarios WHERE usuario = '$username' AND clave = '$password'";
        $result = $conn->query($sql);
        // Verificar si se encontró un usuario con ese nombre y contraseña
        if ($result->num_rows == 1) {
            $token = new Token();
            $fila = $result->fetch_assoc();
            $id = $fila["id_usuario"];
            return $token->create($id);
        } else {
            // El usuario no es válido
            return false;
        }


        // Cerrar la conexión a la base de datos
        $CONN->desconnect();
    }

    public function create($username, $nombre, $pregunta, $resp, $password, $email) {
        require_once('token.php');

        $CONN = new ConexionMySQL($this->db_host, $this->db_username, $this->db_password, $this->db_name);
        $CONN->connectDb();
        $conn = $CONN->conexion;

        // Verificar si la conexión fue exitosa o no
        if ($conn->connect_error) {
            die("Error en la conexión: " . $conn->connect_error);
        }

        // Encriptar la contraseña y la respuesta usando SHA-256
        $password = hash('sha256', $password);
        $resp = hash('sha256', $resp);
        // Consulta SQL para insertar los datos del usuario
        $sql = "INSERT INTO usuarios (usuario, nombre, clave, email, pregunta, respuesta) VALUES ('$username', '$nombre', '$password', '$email', '$pregunta', '$resp')";
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