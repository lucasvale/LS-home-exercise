<script setup lang="ts">
import { ref, computed } from 'vue';
import Card from './shared/Card.vue';
import Input from './shared/Input.vue';
import Button from './shared/Button.vue';

const searchType = ref<'people' | 'movies'>('people');
const searchQuery = ref('');
const isSearching = ref(false);

const emit = defineEmits<{
  search: [searchType: 'people' | 'movies', query: string]
}>();

const isButtonDisabled = computed(() => {
  return searchQuery.value.trim() === '' || isSearching.value;
});

const placeholderText = computed(() => {
  return searchType.value === 'people'
    ? 'e.g. Chewbacca, Yoda, Boba Fett'
    : 'e.g. A New Hope, The Empire Strikes Back';
});

function handleSearch() {
  if (searchQuery.value.trim()) {
    emit('search', searchType.value, searchQuery.value.trim());
  }
}
</script>

<template>
  <Card title="What are you searching for?">
    <template #body>
      <div class="form-body">

        <div class="radio-group">
          <label class="radio-option">
            <input
              type="radio"
              v-model="searchType"
              value="people"
            />
            <span>People</span>
          </label>

          <label class="radio-option">
            <input
              type="radio"
              v-model="searchType"
              value="movies"
            />
            <span>Movies</span>
          </label>
        </div>

        <div class="form-inputs">
          <Input
            v-model="searchQuery"
            :placeholder="placeholderText"
          />

          <Button
            label="SEARCH"
            :disabled="isButtonDisabled"
            @clicked="handleSearch"
          />
        </div>

      </div>
    </template>
  </Card>
</template>

<style scoped>

.form-body{
  display: flex;
  flex-direction: column;
  min-width: 450px;

  .radio-group {
    display: flex;
    gap: 24px;
    margin-bottom: 16px;
  }

  .form-inputs{
    display: flex;
    flex-direction: column;
    justify-content: center;
    gap: 16px;

  }

  .radio-option {
    display: flex;
    align-items: center;
    gap: 8px;
    cursor: pointer;
    font-family: Montserrat, serif;
    font-size: 14px;
    color: #000000;
    font-weight: bold;
    font-stretch: normal;
  }

  .radio-option input[type="radio"] {
    cursor: pointer;
    width: 18px;
    height: 18px;
  }

  .radio-option span {
    user-select: none;
  }
}

@media screen and (max-width: 768px) {
  .form-body {
    min-width: auto;
    max-width: 100%;
    width: 100%;
  }
}

</style>
