import axios from 'axios';

// Tipos para os dados do clima
export interface WeatherCondition {
  text: string;
  icon: string;
}

export interface Temperature {
  celsius: number;
  fahrenheit: number;
}

export interface Wind {
  kph: number;
  mph: number;
  direction: string;
}

export interface Precipitation {
  mm: number;
  in: number;
}

export interface WeatherData {
  city: string;
  region: string;
  country: string;
  localtime: string;
  temperature: Temperature;
  feelslike: Temperature;
  condition: WeatherCondition;
  wind: Wind;
  humidity: number;
  precipitation: Precipitation;
  uv: number;
}

// Base URL da API
const API_URL = 'http://localhost/clima-app/backend';

/**
 * Serviço para buscar dados do clima
 */
export default {
  /**
   * Busca dados do clima para uma cidade específica
   * @param city Nome da cidade
   * @returns Dados do clima
   */
  async getWeather(city: string): Promise<WeatherData> {
    try {
      const response = await axios.get(`${API_URL}/weather?city=${encodeURIComponent(city)}`);
      return response.data;
    } catch (error) {
      if (axios.isAxiosError(error) && error.response) {
        throw new Error(error.response.data.error || 'Erro ao buscar dados do clima');
      }
      throw new Error('Não foi possível conectar ao servidor');
    }
  }
};