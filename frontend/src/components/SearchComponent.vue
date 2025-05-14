<template>
  <div class="search-container">
    <form @submit.prevent="searchWeather" class="search-form">
      <input 
        v-model="city" 
        type="text" 
        placeholder="Digite o nome da cidade" 
        class="search-input"
        required
      />
      <button type="submit" class="search-button">
        <span v-if="loading">Buscando...</span>
        <span v-else>Buscar</span>
      </button>
    </form>
  </div>
</template>

<script lang="ts">
import { defineComponent, ref } from 'vue';

export default defineComponent({
  name: 'SearchComponent',
  
  emits: ['search', 'loading'],
  
  setup(_, { emit }) {
    const city = ref('');
    const loading = ref(false);
    
    const searchWeather = async () => {
      if (city.value.trim()) {
        loading.value = true;
        emit('loading', true);
        
        // Emitir evento para o componente pai
        emit('search', city.value);
        
        // Resetar estado de loading após um pequeno delay
        setTimeout(() => {
          loading.value = false;
          emit('loading', false);
        }, 1000);
      }
    };
    
    return {
      city,
      loading,
      searchWeather
    };
  }
});
</script>

<style scoped>
.search-container {
  width: 100%;
  max-width: 500px;
  margin: 0 auto 20px;
}

.search-form {
  display: flex;
  gap: 10px;
}

.search-input {
  flex: 1;
  padding: 12px;
  border: 1px solid #ddd;
  border-radius: 4px;
  font-size: 16px;
}

.search-button {
  padding: 12px 20px;
  background-color: #4CAF50;
  color: white;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  font-size: 16px;
  transition: background-color 0.3s;
}

.search-button:hover {
  background-color: #45a049;
}

.search-button:disabled {
  background-color: #cccccc;
  cursor: not-allowed;
}
</style>