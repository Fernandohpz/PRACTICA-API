<?php
header("Content-Type: application/json; charset=UTF-8");

include_once "../conexion.php";
include_once "../Usuario.php";

$id = $_GET['id'] ?? die(json_encode(["message" => "Falta el ID"]));

$db = (new Database())->getConnection();
$usuario = new Usuario($db);
$usuario->id = $id;
$data = $usuario->readOne();

echo $data ? json_encode($data) : json_encode(["message" => "Usuario no encontrado"]);