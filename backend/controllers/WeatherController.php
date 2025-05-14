<?php
require_once __DIR__ . '/../services/WeatherService.php';

class WeatherController {
    private $weatherService;
    
    public function __construct() {
        $this->weatherService = new WeatherService();
    }
    
    /**
     * Obtém informações do clima para uma cidade específica
     * 
     * @param string $city Nome da cidade
     * @return string JSON com dados do clima
     */
    public function getWeather($city) {
        try {
            $weatherData = $this->weatherService->fetchWeatherData($city);
            return json_encode($weatherData);
        } catch (Exception $e) {
            http_response_code(500);
            return json_encode(['error' => $e->getMessage()]);
        }
    }
}