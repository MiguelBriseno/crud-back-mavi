<?php
include_once '../database/database.php';
include_once './user.php';

header("Access-Control-Allow-Origin: http://localhost:8080"); // Permitir solicitudes desde http://localhost:8080
header("Access-Control-Allow-Methods: POST, GET, OPTIONS"); // Métodos permitidos
header("Access-Control-Allow-Headers: Content-Type"); // Encabezados permitidos
header("Content-Type: application/json"); // Tipo de contenido de la respuesta

// Verifica si la solicitud es una pre-solicitud OPTIONS
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit; // Termina el script para solicitudes OPTIONS
}

// Obtén la conexión a la base de datos
$database = new Database();
$db = $database->getConnection();

// Decodifica los datos JSON enviados en la solicitud
$data = json_decode(file_get_contents("php://input"));

// Crea una instancia del objeto User
$user = new User($db);
$user->nickname = $data->nickname;
$user->password = $data->password;

// Intenta autenticar al usuario
if ($user->login()) {
    // Si la autenticación es exitosa, obtiene los detalles del usuario
    $userData = $user->getUserData(); // Método ficticio para obtener los datos del usuario

    // Establece el código de estado HTTP a 200
    http_response_code(200);

    // Envía una respuesta JSON con la información del usuario
    echo json_encode(array(
        "message" => "Login exitoso",
        "user" => $userData
    ));
} else {
    // Si la autenticación falla, establece el código de estado HTTP a 401 (No Autorizado)
    http_response_code(401);

    // Envía un mensaje de error
    echo json_encode(array("message" => "Credenciales inválidas"));
}
?>
