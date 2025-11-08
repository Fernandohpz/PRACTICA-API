<?php
header("Content-Type: application/json; charset=UTF-8");


include_once "../conexion.php";
include_once "../Usuario.php";

$data = json_decode(file_get_contents("php://input"));

if (!empty($data->id)) {
    $db = (new Database())->getConnection();
    $usuario = new Usuario($db);
$usuario->id = $data->id;
$usuario->nombre = $data->nombre;
$usuario->email = $data->email;
$usuario->edad = $data->edad;

echo $usuario->update()
    ? json_encode(["message" => "Usuario actualizado"])
    : json_encode(["message" => "Error al actualizar"]);
} else {
    echo json_encode(["message" => "Falta el ID"]);
}