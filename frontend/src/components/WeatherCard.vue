<template>
  <div v-if="weather" class="weather-card">
    <div class="weather-header">
      <h2>{{ weather.city }}, {{ weather.country }}</h2>
      <p class="weather-time">{{ formatLocalTime(weather.localtime) }}</p>
    </div>
    
    <div class="weather-main">
      <div class="weather-condition">
        <img :src="weather.condition.icon" :alt="weather.condition.text" class="weather-icon" />
        <p class="condition-text">{{ weather.condition.text }}</p>
      </div>
      
      <div class="weather-temp">
        <h3>{{ weather.temperature.celsius }}°C</h3>
        <p>Sensação: {{ weather.feelslike.celsius }}°C</p>
      </div>
    </div>
    
    <div class="weather-details">
      <div class="detail-item">
        <span class="detail-label">Umidade</span>
        <span class="detail-value">{{ weather.humidity }}%</span>
      </div>
      
      <div class="detail-item">
        <span class="detail-label">Vento</span>
        <span class="detail-value">{{ weather.wind.kph }} km/h ({{ weather.wind.direction }})</span>
      </div>
      
      <div class="detail-item">
        <span class="detail-label">Precipitação</span>
        <span class="detail-value">{{ weather.precipitation.mm }} mm</span>
      </div>
      
      <div class="detail-item">
        <span class="detail-label">Índice UV</span>
        <span class="detail-value">{{ weather.uv }}</span>
      </div>
    </div>
  </div>
  
  <div v-else-if="error" class="error-message">
    {{ error }}
  </div>
  
  <div v-else-if="loading" class="loading-container">
    <p>Carregando dados do clima...</p>
  </div>
  
  <div v-else class="empty-state">
    <p>Busque pelo nome de uma cidade para ver o clima atual</p>
  </div>
</template>

<script lang="ts">
import { defineComponent, PropType } from 'vue';
import { WeatherData } from '@/services/WeatherService';

export default defineComponent({
  name: 'WeatherCard',
  
  props: {
    weather: {
      type: Object as PropType<WeatherData>,
      required: false,
      default: null
    },
    loading: {
      type: Boolean,
      default: false
    },
    error: {
      type: String,
      default: ''
    }
  },
  
  setup() {
    const formatLocalTime = (localtime: string): string => {
      try {
        const date = new Date(localtime);
        return date.toLocaleString('pt-BR', {
          weekday: 'long',
          day: '2-digit',
          month: 'long',
          hour: '2-digit',
          minute: '2-digit'
        });
      } catch (e) {
        return localtime;
      }
    };
    
    return {
      formatLocalTime
    };
  }
});
</script>

<style scoped>
.weather-card {
  background-color: #fff;
  border-radius: 8px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
  padding: 20px;
  max-width: 500px;
  margin: 0 auto;
}

.weather-header {
  margin-bottom: 20px;
  text-align: center;
}

.weather-header h2 {
  margin: 0;
  font-size: 24px;
  color: #333;
}

.weather-time {
  color: #666;
  margin: 5px 0 0;
  font-size: 14px;
}

.weather-main {
  display: flex;
  justify-content: space-around;
  align-items: center;
  margin-bottom: 20px;
}

.weather-condition {
  text-align: center;
}

.weather-icon {
  width: 64px;
  height: 64px;
}

.condition-text {
  margin: 5px 0 0;
  font-size: 16px;
}

.weather-temp h3 {
  font-size: 36px;
  margin: 0;
  color: #333;
}

.weather-temp p {
  margin: 5px 0 0;
  color: #666;
}

.weather-details {
  background-color: #f9f9f9;
  border-radius: 6px;
  padding: 15px;
}

.detail-item {
  display: flex;
  justify-content: space-between;
  padding: 8px 0;
  border-bottom: 1px solid #eee;
}

.detail-item:last-child {
  border-bottom: none;
}

.detail-label {
  color: #666;
  font-weight: 500;
}

.detail-value {
  font-weight: 600;
  color: #333;
}

.loading-container, .error-message, .empty-state {
  text-align: center;
  padding: 30px;
  background-color: #fff;
  border-radius: 8px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
  max-width: 500px;
  margin: 0 auto;
}

.error-message {
  color: #d32f2f;
}

.empty-state {
  color: #666;
}
</style>
