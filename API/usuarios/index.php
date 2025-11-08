<?php
header("Content-Type: application/json; charset=UTF-8");

include_once "../conexion.php";
include_once "../Usuario.php";

$db = (new Database())->getConnection();
$usuario = new Usuario($db);

$stmt = $usuario->read();
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($usuarios);