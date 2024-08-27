<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once '../database/database.php';
include_once './clients.php';

// Permitir solicitudes desde cualquier origen (ajustar según sea necesario)
header("Access-Control-Allow-Origin: http://localhost:8080");
header("Access-Control-Allow-Methods: POST, GET, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    // Responder a las solicitudes preflight
    http_response_code(200);
    exit;
}

$database = new Database();
$db = $database->getConnection();

$client = new Client($db);

$method = $_SERVER['REQUEST_METHOD'];

switch($method) {
    case 'POST':
        $data = json_decode(file_get_contents("php://input"));

        $client->name = $data->name;
        $client->lastname = $data->lastname;
        $client->address = $data->address;
        $client->email = $data->email;

        if($client->create()) {
            http_response_code(201);
            echo json_encode(["message" => "Cliente creado exitosamente."]);
        } else {
            http_response_code(503);
            echo json_encode(["message" => "No se pudo crear el cliente."]);
        }
        break;

    case 'PUT':
        $data = json_decode(file_get_contents("php://input"));

        $client->id = $data->id;
        $client->name = $data->name;
        $client->lastname = $data->lastname;
        $client->address = $data->address;
        $client->email = $data->email;

        if($client->update()) {
            http_response_code(200);
            echo json_encode(["message" => "Cliente actualizado exitosamente."]);
        } else {
            http_response_code(503);
            echo json_encode(["message" => "No se pudo actualizar el cliente."]);
        }
        break;

    case 'DELETE':
        $data = json_decode(file_get_contents("php://input"));

        $client->id = $data->id;

        if($client->delete()) {
            http_response_code(200);
            echo json_encode(["message" => "Cliente eliminado exitosamente."]);
        } else {
            http_response_code(503);
            echo json_encode(["message" => "No se pudo eliminar el cliente."]);
        }
        break;

    case 'GET':
        // Endpoint para obtener todos los clientes
        $clients = $client->getAllClients();
        
        // Verifica si se encontraron clientes
        if ($clients) {
            http_response_code(200);
            echo json_encode(array(
                "message" => "Todos los clientes registrados",
                "clients" => $clients
            ));
        } else {
            http_response_code(404);
            echo json_encode(["message" => "No se encontraron clientes."]);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(["message" => "Método no permitido"]);
        break;
}
?>
