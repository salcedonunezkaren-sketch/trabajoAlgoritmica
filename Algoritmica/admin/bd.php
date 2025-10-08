<?php

$servidor="localhost";
$baseDatos="Algoritmica";
$usuario="root";
$contrasenia="";

try{ 
    $conexion= new PDO("mysql:host=$servidor;dbname=$baseDatos;", $usuario, $contrasenia);
}catch(Exeption $error){
    echo $error -> getMessage();
}

?> 