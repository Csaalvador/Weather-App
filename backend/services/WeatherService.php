<?php
class WeatherService {
    private $apiKey = 'c61b77037bc7434b857191325251405';
    private $apiUrl = 'https://api.weatherapi.com/v1/current.json';
    
    /**
     * Busca dados do clima na API externa
     * 
     * @param string $city Nome da cidade
     * @return array Dados do clima formatados
     */
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
    
    /**
     * Formata os dados recebidos da API para o formato desejado
     * 
     * @param array $data Dados brutos da API
     * @return array Dados formatados
     */
    private function formatWeatherData($data) {
        return [
            'city' => $data['location']['name'],
            'region' => $data['location']['region'],
            'country' => $data['location']['country'],
            'localtime' => $data['location']['localtime'],
            'temperature' => [
                'celsius' => $data['current']['temp_c'],
                'fahrenheit' => $data['current']['temp_f']
            ],
            'feelslike' => [
                'celsius' => $data['current']['feelslike_c'],
                'fahrenheit' => $data['current']['feelslike_f']
            ],
            'condition' => [
                'text' => $data['current']['condition']['text'],
                'icon' => $data['current']['condition']['icon']
            ],
            'wind' => [
                'kph' => $data['current']['wind_kph'],
                'mph' => $data['current']['wind_mph'],
                'direction' => $data['current']['wind_dir']
            ],
            'humidity' => $data['current']['humidity'],
            'precipitation' => [
                'mm' => $data['current']['precip_mm'],
                'in' => $data['current']['precip_in']
            ],
            'uv' => $data['current']['uv']
        ];
    }
}