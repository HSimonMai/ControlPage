<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

require_once './../Entidades/Password.php';

//GENERA LA CONTRASENA
$gen = new Password();
$result = $gen->generateWithHash(PASSWORD_BCRYPT);

$plain = $result['password']; // texto plano generado
$hash  = $result['hash'];     // hash generado


echo "Texto plano: <b>{$plain}</b><br>";
echo "Hash: <b>{$hash}</b><br><br>";

//VERIFICA CONTRASENA CORRECTA
if ($gen->verifyPassword($plain, $hash)) {
    echo "La contraseña <b>{$plain}</b> coincide con el hash.<br>";
} else {
    echo " La contraseña generada no coincide.<br>";
}

