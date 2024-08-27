<?php
include_once '../database/database.php';
include_once './user.php';

header("Access-Control-Allow-Origin: http://localhost:8080"); 
header("Access-Control-Allow-Methods: POST, GET, OPTIONS"); 
header("Access-Control-Allow-Headers: Content-Type"); 
header("Content-Type: application/json"); 


if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit; 
}

$database = new Database();
$db = $database->getConnection();

$data = json_decode(file_get_contents("php://input"));

$user = new User($db);
$user->nickname = $data->nickname;
$user->password = $data->password;

if ($user->login()) {
    $userData = $user->getUserData(); 

    http_response_code(200);

    echo json_encode(array(
        "message" => "Login exitoso",
        "user" => $userData
    ));
} else {
    http_response_code(401);

    echo json_encode(array("message" => "Credenciales inválidas"));
}
?>
