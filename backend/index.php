<?php
header('Access-Control-Allow-Origin: *'); // Permite acesso de qualquer origem (para desenvolvimento)
header('Content-Type: application/json; charset=UTF-8');

// Carregar os arquivos necessários
require_once __DIR__ . '/controllers/WeatherController.php';

// Processar a solicitação
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$queryString = $_SERVER['QUERY_STRING'] ?? '';
parse_str($queryString, $queryParams);

// Roteamento simples
if (strpos($uri, '/weather') !== false) {
    $controller = new WeatherController();
    
    // GET /weather?city=NomeDaCidade
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