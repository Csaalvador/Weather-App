<?php
require_once __DIR__ . '/../services/WeatherService.php';

class WeatherController {
    private $weatherService;
    
    public function __construct() {
        $this->weatherService = new WeatherService();
    }
    
    
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