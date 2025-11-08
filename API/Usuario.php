<?php
class Usuario
{

    private $conn;
    private $table_name = "usuarios";


    public $id;
    public $nombre;
    public $email;
    public $edad;


    public function __construct($db)
    {
$this->conn = $db;
}


// Obtener todos
public function read()
{
    $query = "SELECT * FROM " . $this->table_name . " ORDER BY id DESC";

    $stmt = $this->conn->prepare($query);

    $stmt->execute();

    return $stmt;
}


// Crear
public function create()
{
    $query = "INSERT INTO " . $this->table_name . " (nombre, email, edad)
        VALUES (:nombre, :email, :edad)";

    $stmt = $this->conn->prepare($query);

$stmt->bindParam(":nombre", $this->nombre);
    $stmt->bindParam(":email", $this->email);
    $stmt->bindParam(":edad", $this->edad);

    return $stmt->execute();
}


// Obtener uno
public function readOne()
{
$query = "SELECT * FROM " . $this->table_name . " WHERE id = ? LIMIT 0,1";

    $stmt = $this->conn->prepare($query);

    $stmt->bindParam(1, $this->id);

    $stmt->execute();

    return $stmt->fetch(PDO::FETCH_ASSOC);
}


// Actualizar
public function update()
{
    $query = "UPDATE " . $this->table_name . " 
        SET nombre = :nombre, email = :email, edad = :edad
        WHERE id = :id";

    $stmt = $this->conn->prepare($query);

    $stmt->bindParam(":nombre", $this->nombre);
    $stmt->bindParam(":email", $this->email);
    $stmt->bindParam(":edad", $this->edad);
    $stmt->bindParam(":id", $this->id);

    return $stmt->execute();
}

// Eliminar
public function delete()
{
    $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(1, $this->id);
    return $stmt->execute();
}
}