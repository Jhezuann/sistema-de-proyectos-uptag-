<?php

session_start();

include('conexion/conect.php');

$user = $_SESSION['user'];
$respuesta = hash('sha256', $_POST['respuesta']);



$sql= "SELECT * FROM usuarios WHERE usuario = '$user' and respuesta ='$respuesta'";

	$resultado = mysqli_query($conexion,$sql);
	if (mysqli_num_rows($resultado) == 0)
	{
		echo "<script text='text/javascript'>
		alert('Respuesta incorrecta');
		window.location = 'pregunta.php';
		</script>";
	}else
	{
	

	    $row=mysqli_fetch_assoc($resultado);
	    $_SESSION["id"] =$row['id_usuario'];
	    $_SESSION["user"] =$row['usuario'];
		header("Location: nueva_clave.php");
	}
