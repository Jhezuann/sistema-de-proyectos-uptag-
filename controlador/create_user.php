<?php
include_once('../repositories/user.php');

session_start();

$userd = new User();

$usuario = $_POST["username"];
$nombre = $_POST["name"]; 
$clave = $_POST["password"];
$email = $_POST["email"];
$question = $_POST["question"];
$response  = $_POST["answer"];

$result =  $userd -> create($usuario, $nombre,$question,$response, $clave, $email );
if ($result == false) {
    echo "<script text='text/javascript'>
    alert('Data incorrect');
    window.location = '../index.php';
    </script>";
} 

$_SESSION['token'] = $result;
header('location:../index.php');
