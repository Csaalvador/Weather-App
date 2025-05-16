// WeatherApp.vue - Sem frases, apenas design e funcionalidades
<template>
  <div class="app-container">
    <!-- Card principal com efeito de vidro -->
    <div class="glass-card">
      <!-- Estado de carregamento -->
      <div v-if="loading" class="loading-state">
        <div class="loading-animation"></div>
        <p>Buscando dados...</p>
      </div>
      
      <!-- Conteúdo principal -->
      <template v-else-if="weatherData">
        <!-- Cabeçalho com localização -->
        <div class="card-header">
          <div class="location-info">
            <h2>{{ weatherData.city }}</h2>
            <p>{{ weatherData.country }}</p>
            <div class="time">{{ formattedTime }}</div>
          </div>
          
          <!-- Ícone do clima -->
          <div class="weather-icon"></div>
        </div>
        
        <!-- Temperatura principal -->
        <div class="temperature-display">
          <div class="temp-now">
            <h1>{{ Math.round(weatherData.temperature.celsius) }}°</h1>
            <p>{{ weatherData.condition.text }}</p>
          </div>
          <div class="temp-feel">
            <p>Sensação</p>
            <span>{{ Math.round(weatherData.feelslike.celsius) }}°</span>
          </div>
        </div>
        
        <!-- Métricas do clima -->
        <div class="weather-metrics">
          <div class="metric">
            <div class="value">{{ weatherData.humidity }}%</div>
            <div class="label">Umidade</div>
          </div>
          <div class="metric">
            <div class="value">{{ Math.round(weatherData.wind.kph) }}km/h</div>
            <div class="label">Vento</div>
          </div>
          <div class="metric">
            <div class="value">{{ weatherData.uv }}</div>
            <div class="label">Índice UV</div>
          </div>
          <div class="metric">
            <div class="value">{{ weatherData.precipitation.mm }}mm</div>
            <div class="label">Chuva</div>
          </div>
        </div>
      </template>
      
      <!-- Estado de erro -->
      <div v-else-if="error" class="error-state">
        <div class="error-icon"></div>
        <p>{{ error }}</p>
        <button @click="fetchWeather(currentCity)">Tentar novamente</button>
      </div>
    </div>
    
    <!-- Campo de busca único -->
    <div class="search-container">
      <form @submit.prevent="searchCity">
        <input 
          type="text" 
          v-model="cityInput" 
          placeholder="Digite o nome da cidade" 
        />
        <button type="button" class="location-button" @click="useCurrentLocation" title="Usar localização">
          <span class="location-icon"></span>
        </button>
      </form>
    </div>
  </div>
</template>

<script>
export default {
  name: 'WeatherApp',
  data() {
    return {
      weatherData: null,
      loading: true,
      error: '',
      cityInput: '',
      currentCity: ''
    }
  },
  computed: {
    formattedTime() {
      if (!this.weatherData?.localtime) return '';
      try {
        const date = new Date(this.weatherData.localtime);
        return date.toLocaleTimeString('pt-BR', { 
          weekday: 'short',
          hour: '2-digit', 
          minute: '2-digit'
        });
      } catch {
        return '';
      }
    }
  },
  methods: {
    // Buscar dados do clima
    async fetchWeather(city) {
      if (!city) {
        city = 'Sao Paulo';
      }
      
      this.loading = true;
      this.error = '';
      
      try {
        console.log(`Buscando clima para: ${city}`);
        
        const response = await fetch(`http://localhost/clima-app/backend/weather?city=${encodeURIComponent(city)}`);
        
        if (!response.ok) {
          throw new Error(`Erro ${response.status}`);
        }
        
        this.weatherData = await response.json();
        this.currentCity = city;
        this.updateUrl();
        
      } catch (err) {
        console.error('Erro ao buscar dados:', err);
        this.error = 'Não foi possível carregar os dados do clima';
      } finally {
        this.loading = false;
      }
    },
    
    // Processar o envio do formulário de busca
    searchCity() {
      if (this.cityInput.trim()) {
        this.fetchWeather(this.cityInput.trim());
        this.cityInput = '';
      }
    },
    
    // Atualizar a URL com o parâmetro da cidade
    updateUrl() {
      const url = new URL(window.location.href);
      url.searchParams.set('city', this.currentCity);
      window.history.pushState({}, '', url);
    },
    
    // Usar a localização atual do usuário
    useCurrentLocation() {
      if (!navigator.geolocation) {
        alert('Geolocalização não é suportada pelo seu navegador');
        return;
      }
      
      navigator.geolocation.getCurrentPosition(
        position => {
          const lat = position.coords.latitude;
          const lon = position.coords.longitude;
          this.fetchWeather(`${lat},${lon}`);
        },
        error => {
          console.error('Erro de localização:', error);
          alert('Não foi possível obter sua localização');
        }
      );
    },
    
    // Verificar parâmetros da URL
    checkUrlParams() {
      const urlParams = new URLSearchParams(window.location.search);
      return urlParams.get('city');
    }
  },
  mounted() {
    // Iniciar com a cidade da URL ou padrão
    const cityFromUrl = this.checkUrlParams();
    this.fetchWeather(cityFromUrl || 'Sao Paulo');
    
    // Suporte ao botão de voltar do navegador
    window.addEventListener('popstate', () => {
      const cityFromUrl = this.checkUrlParams();
      if (cityFromUrl && cityFromUrl !== this.currentCity) {
        this.fetchWeather(cityFromUrl);
      }
    });
  }
}
</script>

<style>
/* Estilos base */
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

.app-container {
  min-height: 100vh;
  width: 100%;
  display: flex;
  justify-content: center;
  align-items: center;
  padding: 20px;
  background: linear-gradient(135deg, #4a8af4, #5b9cf5);
  position: relative;
}

/* Card principal */
.glass-card {
  background: rgba(255, 255, 255, 0.15);
  border-radius: 24px;
  box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
  backdrop-filter: blur(10px);
  border: 1px solid rgba(255, 255, 255, 0.2);
  width: 100%;
  max-width: 360px;
  min-height: 500px;
  padding: 25px;
  position: relative;
  z-index: 1;
  display: flex;
  flex-direction: column;
  transition: transform 0.3s;
  color: white;
}

.glass-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
}

/* Estrutura do conteúdo */
.card-header {
  display: flex;
  justify-content: space-between;
  margin-bottom: 25px;
}

.location-info h2 {
  font-size: 24px;
  font-weight: 600;
  margin: 0;
}

.location-info p {
  font-size: 14px;
  opacity: 0.8;
  margin: 5px 0;
}

.time {
  font-size: 14px;
  opacity: 0.7;
  margin-top: 8px;
}

.weather-icon {
  width: 50px;
  height: 50px;
  background-size: 100%;
  background-repeat: no-repeat;
}

.temperature-display {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 25px;
}

.temp-now h1 {
  font-size: 64px;
  font-weight: 700;
  margin: 0;
  line-height: 1;
}

.temp-now p {
  font-size: 16px;
  margin-top: 5px;
}

.temp-feel {
  text-align: right;
  font-size: 14px;
}

.temp-feel p {
  opacity: 0.7;
  margin-bottom: 5px;
}

.temp-feel span {
  font-size: 20px;
  font-weight: 600;
}

.weather-metrics {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 15px;
  margin-bottom: 25px;
  margin-top: auto;
}

.metric {
  padding: 12px;
  background: rgba(255, 255, 255, 0.1);
  border-radius: 16px;
  text-align: center;
  transition: transform 0.2s;
}

.metric:hover {
  transform: scale(1.03);
}

.metric .value {
  font-size: 16px;
  font-weight: 600;
}

.metric .label {
  font-size: 12px;
  opacity: 0.7;
  margin-top: 4px;
}

/* Estados especiais */
.loading-state, .error-state {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  background: rgba(255, 255, 255, 0.15);
  backdrop-filter: blur(10px);
  z-index: 10;
  border-radius: 24px;
}

.loading-animation {
  width: 40px;
  height: 40px;
  border: 3px solid rgba(255, 255, 255, 0.3);
  border-radius: 50%;
  border-top-color: white;
  animation: spin 1s linear infinite;
  margin-bottom: 15px;
}

@keyframes spin {
  to { transform: rotate(360deg); }
}

.error-icon {
  width: 50px;
  height: 50px;
  position: relative;
  margin-bottom: 15px;
}

.error-icon:before, .error-icon:after {
  content: '';
  position: absolute;
  top: 50%;
  left: 50%;
  width: 5px;
  height: 30px;
  background: white;
  border-radius: 5px;
}

.error-icon:before {
  transform: translate(-50%, -50%) rotate(45deg);
}

.error-icon:after {
  transform: translate(-50%, -50%) rotate(-45deg);
}

.error-state button {
  margin-top: 15px;
  padding: 8px 16px;
  background: rgba(255, 255, 255, 0.2);
  border: none;
  color: white;
  border-radius: 20px;
  cursor: pointer;
}

/* Campo de busca */
.search-container {
  position: fixed;
  bottom: 25px;
  left: 50%;
  transform: translateX(-50%);
  width: 100%;
  max-width: 360px;
  padding: 0 20px;
  z-index: 100;
}

.search-container form {
  display: flex;
  gap: 10px;
  width: 100%;
}

.search-container input {
  flex: 1;
  padding: 12px 20px;
  border: none;
  background: rgba(255, 255, 255, 0.2);
  backdrop-filter: blur(5px);
  color: white;
  border-radius: 30px;
  font-size: 15px;
}

.search-container input::placeholder {
  color: rgba(255, 255, 255, 0.7);
}

.search-container input:focus {
  outline: none;
  background: rgba(255, 255, 255, 0.25);
}

.search-button, .location-button {
  width: 42px;
  height: 42px;
  border: none;
  border-radius: 50%;
  background: rgba(255, 255, 255, 0.25);
  cursor: pointer;
  color: white;
  display: flex;
  align-items: center;
  justify-content: center;
}

.search-icon:before {
  content: '';
  display: inline-block;
  width: 16px;
  height: 16px;
  border: 2px solid white;
  border-radius: 50%;
  position: relative;
}

.search-icon:after {
  content: '';
  position: absolute;
  width: 2px;
  height: 8px;
  background: white;
  transform: rotate(45deg);
  margin-left: 13px;
  margin-top: 13px;
}

.location-icon:before {
  content: '';
  display: inline-block;
  width: 16px;
  height: 16px;
  border: 2px solid white;
  border-radius: 50%;
  position: relative;
}

.location-icon:after {
  content: '';
  position: absolute;
  width: 6px;
  height: 6px;
  background: white;
  border-radius: 50%;
  margin-left: -3px;
  margin-top: 5px;
}

/* Responsividade */
@media (max-width: 480px) {
  .glass-card {
    min-height: calc(100vh - 100px);
    border-radius: 18px;
  }
  
  .temp-now h1 {
    font-size: 56px;
  }
}
</style>