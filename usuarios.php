<?php
include_once "conexion/conexion.php";
session_start();

// Verificar si el usuario tiene permiso para acceder a esta página
if ($_SESSION['tipo'] != 1) {
    header('location:inicio.php?pagina=1');
    exit(); // Asegúrate de salir del script después de redirigir
}

// Verificar si se envió el formulario de eliminar_usuario
if (isset($_POST['id_usuario'])) {
    // Obtener el ID del usuario a eliminar
    $idUsuarioEliminar = $_POST['id_usuario'];

    // Obtener la contraseña ingresada por el usuario
    $clave = $_POST['clave'];

    // Verificar si la contraseña es válida
    // Aquí debes implementar la lógica para verificar la contraseña del usuario actual
    // Puedes compararla con la contraseña almacenada en la base de datos o con cualquier otro método de autenticación que estés utilizando
    $claveValida = ($clave == $_SESSION['clave']); // Ejemplo: comparar la contraseña ingresada con la contraseña almacenada en la sesión

    // Si la contraseña es válida, proceder a eliminar al usuario
    if ($claveValida) {
        // Eliminar registros relacionados en la tabla 'tokens'
        $sql_eliminar_tokens = "DELETE FROM tokens WHERE user_id = :id";
        $sentencia_eliminar_tokens = $conex->prepare($sql_eliminar_tokens);
        $sentencia_eliminar_tokens->bindParam(':id', $idUsuarioEliminar, PDO::PARAM_INT);
        $sentencia_eliminar_tokens->execute();

        // Eliminar al usuario de la tabla 'usuarios'
        $sql_eliminar = "DELETE FROM usuarios WHERE id_usuario = :id";
        $sentencia_eliminar = $conex->prepare($sql_eliminar);
        $sentencia_eliminar->bindParam(':id', $idUsuarioEliminar, PDO::PARAM_INT);
        $sentencia_eliminar->execute();

        // Verificar si se eliminó correctamente
        if ($sentencia_eliminar->rowCount() > 0) {
            echo "Usuario eliminado exitosamente";
        } else {
            echo "Error al eliminar el usuario";
        }

        // Redirigir a la página principal después de eliminar el usuario
        header("Location: usuarios.php");
        exit(); // Asegúrate de salir del script después de redirigir
    } else {
        echo "Contraseña incorrecta";
    }
}

// Consulta para obtener todos los usuarios
$sql_usuarios = "SELECT * FROM usuarios";
$sentencia_usuarios = $conex->prepare($sql_usuarios);
$sentencia_usuarios->execute();
$usuarios = $sentencia_usuarios->fetchAll();
?>

<!doctype html>
<html lang="en">
  <head>
    <script>
    function confirmarEliminacion(idUsuario) {
        var clave = prompt("Ingrese su contraseña para confirmar:");

        if (clave !== null) {
            // Crear un formulario dinámicamente
            var form = document.createElement("form");
            form.method = "post";
            form.action = ""; // Coloca aquí la URL del archivo PHP que procesa el formulario

            // Agregar un campo oculto para el ID del usuario
            var idInput = document.createElement("input");
            idInput.type = "hidden";
            idInput.name = "id_usuario";
            idInput.value = idUsuario;

            // Agregar el campo oculto al formulario
            form.appendChild(idInput);

            // Agregar el formulario al cuerpo del documento
            document.body.appendChild(form);

            // Enviar el formulario
            form.submit();
        }
    }
</script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--bootstrap-->
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css">
    <!--custom css-->
    <link rel="stylesheet"  href="css/estilos1.css">
    <title>SGCPSP</title>
  </head>
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-3">
    <div class="container-fluid">
      <img src="img/logo.png" width="30" class="m-1" alt="">
      <a class="navbar-brand">SGCPSP</a>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="inicio.php">Inicio</a>
          </li>
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="usuarios_bloqueados.php">Usuarios bloqueados</a>
          </li>
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="permisos.php">Permisos</a>
          </li>
         </ul>
         <ul class="nav justify-content-center m-3 pt-2 mb-2 mt-0">
          <li class="nav-item"><a href="cerrar.php" class="nav-link px-2 text-danger btn btn-outline-danger"><font style="vertical-align: inherit;">Cerrar Sesion</font></a></li>
        </ul>
      </div>
   </div>
</nav>
<body>
    <div class="container">
        <h1>Listado de Usuarios</h1>

        <?php if (!empty($usuarios)): ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Tipo de Cuenta</th>
                        <!-- Agrega las columnas adicionales que necesites mostrar -->
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($usuarios as $usuario): ?>
                        <tr>
                            <td><?php echo $usuario['id_usuario']; ?></td>
                            <td><?php echo $usuario['nombre']; ?></td>
                            <td><?php echo $usuario['email']; ?></td>
                            <td><?php echo ($usuario['tipo'] == 1) ? 'Administrador' : 'Normal'; ?></td>
                            <td>
                                <?php if ($_SESSION['tipo'] == 1): ?>
                                    <form method="post" id="form_eliminar_<?php echo $usuario['id_usuario']; ?>">
                                        <input type="hidden" name="id_usuario" value="<?php echo $usuario['id_usuario']; ?>">
                                        <button type="button" class="btn btn-danger" onclick="confirmarEliminacion(<?php echo $usuario['id_usuario']; ?>)">Eliminar</button>
                                    </form>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No se encontraron usuarios.</p>
        <?php endif; ?>
    </div>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <div class="">
      <footer class="sticky-bottom mt-2 pb-1">
        <h1 class="text-center text-light "><img src="img/logo.png" width="30" alt="">SGCPSP</h1>
        <p class="text-center text-light fw-bold"><font style="vertical-align: inherit;">© 2023 Sistema de Gestión y Control de los Proyectos Socio Productivos</font></p><hr><br>
      </footer>
    </div>
   </body>

</html>