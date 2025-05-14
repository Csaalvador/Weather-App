<template>
  <div class="app-container">
    <header class="app-header">
      <h1>Consulta de Clima</h1>
    </header>
    
    <main class="app-content">
      <SearchComponent 
        @search="searchWeather" 
        @loading="setLoading"
      />
      
      <WeatherCard 
        :weather="weatherData" 
        :loading="loading" 
        :error="error"
      />
    </main>
    
    <footer class="app-footer">
      <p>Desenvolvido com Vue 3 e TypeScript | Dados fornecidos por WeatherAPI</p>
    </footer>
  </div>
</template>

<script lang="ts">
import { defineComponent, ref } from 'vue';
import WeatherService, { WeatherData } from './services/WeatherService';
import SearchComponent from './components/SearchComponent.vue';
import WeatherCard from './components/WeatherCard.vue';

export default defineComponent({
  name: 'App',
  
  components: {
    SearchComponent,
    WeatherCard
  },
  
  setup() {
    const weatherData = ref<WeatherData | null>(null);
    const loading = ref(false);
    const error = ref('');
    
    const searchWeather = async (city: string) => {
      try {
        error.value = '';
        loading.value = true;
        weatherData.value = await WeatherService.getWeather(city);
      } catch (err) {
        error.value = err instanceof Error ? err.message : 'Ocorreu um erro ao buscar o clima';
        weatherData.value = null;
      } finally {
        loading.value = false;
      }
    };
    
    const setLoading = (isLoading: boolean) => {
      loading.value = isLoading;
    };
    
    return {
      weatherData,
      loading,
      error,
      searchWeather,
      setLoading
    };
  }
});
</script>

<style>
* {
  box-sizing: border-box;
  margin: 0;
  padding: 0;
}

body {
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  line-height: 1.6;
  color: #333;
  background-color: #f5f5f5;
}

.app-container {
  min-height: 100vh;
  display: flex;
  flex-direction: column;
}

.app-header {
  background: linear-gradient(120deg, #0077c2, #004c8c);
  color: white;
  text-align: center;
  padding: 20px;
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

.app-header h1 {
  margin: 0;
  font-size: 2rem;
}

.app-content {
  flex: 1;
  padding: 30px 15px;
  max-width: 800px;
  margin: 0 auto;
  width: 100%;
}

.app-footer {
  background-color: #333;
  color: #fff;
  text-align: center;
  padding: 15px;
  margin-top: auto;
  font-size: 0.9rem;
}

@media (max-width: 600px) {
  .app-header h1 {
    font-size: 1.5rem;
  }
  
  .app-content {
    padding: 20px 10px;
  }
}
</style>