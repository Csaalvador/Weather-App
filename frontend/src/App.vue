<template>
  <div class="app-container">
    <weather-card :city="currentCity" />
    
    <div class="search-container">

    </div>
  </div>
</template>

<script>
import WeatherCard from '../src/components/WeatherCard.vue';

export default {
  name: 'App',
  components: {
    WeatherCard
  },
  data() {
    return {
      currentCity: '',
      searchCity: ''
    }
  },
  methods: {
    updateCity() {
      if (this.searchCity.trim()) {
        this.currentCity = this.searchCity.trim();
        this.updateUrl();
        this.searchCity = '';
      }
    },
    updateUrl() {
      const url = new URL(window.location.href);
      url.searchParams.set('city', this.currentCity);
      window.history.pushState({}, '', url);
    }
  },
  mounted() {
    // Verificar se há um parâmetro city na URL ao carregar
    const urlParams = new URLSearchParams(window.location.search);
    this.currentCity = urlParams.get('city') || 'Sao Paulo';
  }
}
</script>

<style>
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

body {
  font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
  background-color: #f5f7fa;
  color: #333;
  min-height: 100vh;
}

.app-container {
  padding: 20px;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  min-height: 100vh;
  gap: 25px;
}

.search-container {
  display: flex;
  gap: 8px;
  width: 100%;
  max-width: 320px;
}

.search-container input {
  flex: 1;
  padding: 10px 15px;
  border: 1px solid #ddd;
  border-radius: 20px;
  font-size: 14px;
  outline: none;
  transition: border-color 0.2s;
}

.search-container input:focus {
  border-color: #4a8af4;
}

.search-container button {
  padding: 10px 15px;
  background-color: #4a8af4;
  color: white;
  border: none;
  border-radius: 20px;
  cursor: pointer;
  font-weight: 500;
  transition: background-color 0.2s;
}

.search-container button:hover {
  background-color: #3b7de8;
}

@media (max-width: 400px) {
  .app-container {
    padding: 0;
  }
  
  .search-container {
    position: fixed;
    bottom: 20px;
    left: 50%;
    transform: translateX(-50%);
    z-index: 10;
    padding: 0 20px;
  }
}
</style>