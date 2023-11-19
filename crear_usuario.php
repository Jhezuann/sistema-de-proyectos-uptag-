<?php
session_start();

if ($_POST) {
    $usuario = $_POST['username'];
    $clave = $_POST['password'];
    $re_password = $_POST['re_password'];

    if (empty($usuario) || empty($clave) || empty($re_password)) {
        $mensaje = "Por favor, ingrese todos los campos.";
    } elseif (strlen($clave) < 8) {
        $mensaje = "La contraseña debe tener al menos 8 caracteres.";
    } elseif ($clave != $re_password) {
        $mensaje = "La contraseña y la confirmación de contraseña no coinciden.";
    } else {
        // Encripta la contraseña usando SHA-256
        $clave_encriptada = hash('sha256', $clave);
        $respuesta_encriptada = hash('sha256', $respuesta);

        // El resto del código para guardar la cuenta
        // ...
    }
}
?>


<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--bootstrap-->
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css">
    <!--custom css-->
    <link rel="stylesheet" type="text/css" href="css2/estilos.css">
    <title>Crear Usuario</title>
  </head>
  <body>
    <div class="container w-75 bg-secondary mt-5 mb-5 rounded shadow">
      <div class="row align-items-stretch">
        <div class="col bg d-none d-lg-block col-md-5 col-lg-5 col-xl-6 rounded">
        </div>
        <div class="col bg-white p-5 rounded-end">
          <div class="text-end">
            <img src="img/logo.png" width="48" alt="logo">
          </div>
          <h2 class="fw-bold text-center py-5 box-shadow">Crear Usuario</h2>
          <form action="controlador/create_user.php" method="POST">
            <div class="mb-4">
              <label for="username" class="form-label">Usuario</label>
              <input type="text" class="form-control" name="username" aria-describedby="emailHelp">
            </div>
            <div class="mb-4">
              <label for="name" class="form-label">Nombre de usuario</label>
              <input type="text" class="form-control" name="name" aria-describedby="emailHelp">
            </div>
            <div class="mb-4">
              <label for="email" class="form-label">Email</label>
              <input type="email" class="form-control" name="email">
            </div>      
            <div class="mb-4">
              <label for="password" class="form-label">Contraseña</label>
              <input type="password" class="form-control" name="password">
            </div>
            <div class="mb-4">
              <label for="answer" class="form-label">Confirmar Contraseña</label>
              <input type="password" class="form-control" name="answer">
            </div>
            <div class="mb-4">
              <label for="question" class="form-label">Pregunta de seguridad</label>
              <input type="text" class="form-control" name="question">
            </div>
            <div class="mb-4">
              <label for="re_password" class="form-label">Respuesta</label>
              <input type="text" class="form-control" name="re_password">
            </div>

            <?php if (isset($mensaje)): ?>
              <div class="alert alert-danger" role="alert">
                <?php echo $mensaje; ?>
              </div>
            <?php endif; ?>
            <div class="d-grid">
              <button type="submit" class="btn btn-dark"><h5>Crear Usuario</h5></button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <div class="">
      <footer class="sticky-bottom mt-2 pb-1">
        <h1 class="text-center text-light "><img src="img/logo.png" width="30" alt="">SGCPSP</h1>
        <p class="text-center text-light fw-bold"><font style="vertical-align: inherit;">© 2023 Sistema de Gestión y Control de los Proyectos Socio Productivos</font></p><hr><br>
      </footer>
    </div>
  </body>

</html>