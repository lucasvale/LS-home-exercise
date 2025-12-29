<script setup lang="ts">
import ListItem from "@/components/ListItem.vue";
import Card from "@/components/shared/Card.vue";
import type { SearchResult } from "@/types/api";

interface Props {
  results: SearchResult[];
  searchType: 'people' | 'movies';
  isLoading?: boolean;
  error?: string | null;
}

const props = withDefaults(defineProps<Props>(), {
  isLoading: false,
  error: null
});

const emit = defineEmits(['clear']);

function clear(){
  emit('clear');
}
</script>

<template>
  <Card title="Results">
    <template #body>
      <div class="items">
        <div class="clear" v-if="results.length > 0">
          <a @click="clear">clear results</a>
        </div>

        <div v-if="isLoading" class="loading-state">
          <p>Searching...</p>
        </div>

        <div v-else-if="error" class="error-state">
          <p>{{ error }}</p>
        </div>

        <div v-else-if="results.length === 0" class="empty-state">
          <p>There are zero matches.</p>
          <p class="hint">Use the form to search for People or Movies.</p>
        </div>

        <ListItem
          v-else
          v-for="result in results"
          :key="result.id"
          :id="result.id"
          :name="result.name"
          :search-type="searchType"
        />
      </div>
    </template>
  </Card>
</template>

<style scoped>
  .items {
    display: flex;
    flex-direction: column;
    align-items: center;
    width: fit-content;
    min-width: 450px;
  }

  .clear {
    display: flex;
    align-self: flex-end;
    cursor: pointer;
    text-decoration: underline;
  }

  .empty-state,
  .loading-state,
  .error-state {
    text-align: center;
    padding: 40px 20px;
    font-family: Montserrat, serif;
  }

  .empty-state,
  .loading-state {
    color: #c4c4c4;
  }

  .error-state {
    color: #e74c3c;
  }

  .empty-state p,
  .loading-state p,
  .error-state p {
    margin: 8px 0;
    font-size: 14px;
  }

  .empty-state .hint {
    font-size: 12px;
  }

  @media screen and (max-width: 768px) {
    .items {
      min-width: auto;
      max-width: 100%;
      width: 100%;
    }
  }

  @media screen and (max-width: 425px) {
    .items {
      min-width: auto;
      max-width: 100%;
      width: 100%;
    }
  }
</style>
