<script setup lang="ts">
import { ref } from 'vue';
import Form from '@/components/Form.vue';
import Results from '@/components/Results.vue';
import type { SearchResult } from '@/types/api';
import { searchPeople, searchFilms } from '@/services/api';

const results = ref<SearchResult[]>([]);
const currentSearchType = ref<'people' | 'movies'>('people');
const isLoading = ref(false);
const error = ref<string | null>(null);

async function handleSearch(searchType: 'people' | 'movies', query: string) {
  // Clear previous results
  results.value = [];
  currentSearchType.value = searchType;
  error.value = null;
  isLoading.value = true;

  try {
    if (searchType === 'people') {
      results.value = await searchPeople(query);
    } else {
      results.value = await searchFilms(query);
    }
  } catch (err) {
    error.value = 'Failed to fetch results. Please try again.';
    console.error('Search error:', err);
  } finally {
    isLoading.value = false;
  }
}

function handleClear(){
  results.value = [];
  error.value = null;
}
</script>

<template>
  <div class="home">
    <Form @search="handleSearch" />
    <Results
      :results="results"
      :search-type="currentSearchType"
      :is-loading="isLoading"
      :error="error"
      @clear="handleClear"
    />
  </div>
</template>

<style scoped>
.home {
  display: flex;
  gap: 50px;
  padding: 20px;
  justify-content: center;
  align-items: flex-start;
}

@media screen and (max-width: 768px) {
  .home {
    flex-direction: column;
    align-items: center;
    width: 95%;
  }
}

@media screen and (max-width: 425px) {

}
</style>
