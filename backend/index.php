<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json; charset=UTF-8');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

$logMessage = date('Y-m-d H:i:s') . " - Requisição recebida\n";
$logMessage .= "URI: " . $_SERVER['REQUEST_URI'] . "\n";
$logMessage .= "Query: " . ($_SERVER['QUERY_STRING'] ?? 'Nenhuma') . "\n";
$logMessage .= "Método: " . $_SERVER['REQUEST_METHOD'] . "\n";
file_put_contents(__DIR__ . '/api_debug.log', $logMessage, FILE_APPEND);

require_once __DIR__ . '/controllers/WeatherController.php';

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$queryString = $_SERVER['QUERY_STRING'] ?? '';
parse_str($queryString, $queryParams);

if (strpos($uri, '/weather') !== false) {
    $controller = new WeatherController();
    if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($queryParams['city'])) {
        echo $controller->getWeather($queryParams['city']);
    } else {
        http_response_code(400);
        echo json_encode(['error' => 'Cidade não fornecida']);
    }
} else {
    http_response_code(404);
    echo json_encode(['error' => 'Rota não encontrada']);
}
