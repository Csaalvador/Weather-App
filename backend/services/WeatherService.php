<?php
class WeatherService {
    private $apiKey = 'c61b77037bc7434b857191325251405';
    private $apiUrl = 'https://api.weatherapi.com/v1/current.json';
    

    public function fetchWeatherData($city) {
        // Construir a URL com parâmetros
        $url = $this->apiUrl . '?key=' . $this->apiKey . '&q=' . urlencode($city);
        
        // Fazer a requisição à API
        $response = file_get_contents($url);
        
        if ($response === false) {
            throw new Exception('Não foi possível conectar à API de clima');
        }
        
        $data = json_decode($response, true);
        
        if (isset($data['error'])) {
            throw new Exception($data['error']['message'] ?? 'Erro ao buscar dados do clima');
        }
        
        // Extrair e formatar os dados relevantes
        return $this->formatWeatherData($data);
    }
    
    private function formatWeatherData($data) {
        return [
            'city' => $data['location']['name'],
            'region' => $data['location']['region'],
            'country' => $data['location']['country'],
            'localtime' => $data['location']['localtime'],
            'temperature' => [
            'celsius' => $data['current']['temp_c'],
            ],
            'feelslike' => [
                'celsius' => $data['current']['feelslike_c'],
            ],
            'condition' => [
               
            ],
            'wind' => [
                'kph' => $data['current']['wind_kph'],
                'mph' => $data['current']['wind_mph'],
            ],
            'humidity' => $data['current']['humidity'],
            'precipitation' => [
                'mm' => $data['current']['precip_mm'],
            ],
            'uv' => $data['current']['uv']
        ];
    }
}