<?php
header("Content-Type: application/json; charset=UTF-8");

include_once "../conexion.php";
include_once "../Usuario.php";

try {
    $data = json_decode(file_get_contents("php://input"));

    if (empty($data->nombre) || empty($data->email)) {
        throw new Exception("Datos incompletos.", 400);
    }

    $db = (new Database())->getConnection();
    $usuario = new Usuario($db);

    $usuario->nombre = $data->nombre;
    $usuario->email = $data->email;
    $usuario->edad = $data->edad ?? null;

    if ($usuario->create()) {
        echo json_encode([
            "message" => "Usuario creado correctamente.",
            "codeServer" => 201
        ]);
    } else {
        throw new Exception("Error al crear usuario.", 500);
    }

} catch (PDOException $e) {
    // Si el email ya existe y hay una restricción UNIQUE en la BD
    if ($e->getCode() == 23000) { // Código SQLSTATE para "Duplicate entry"
        echo json_encode([
            "message" => "El correo electrónico ya está registrado.",
            "codeServer" => 400
        ]);
    } else {
        echo json_encode([
            "message" => "Error en la base de datos: " . $e->getMessage(),
            "codeServer" => 500
        ]);
    }

} catch (Exception $e) {
    echo json_encode([
        "message" => $e->getMessage(),
        "codeServer" => $e->getCode() ?: 500
    ]);
}
