<script setup lang="ts">
import { ref, computed, onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import DetailsComponent from '@/components/Details.vue';
import { getPersonById, getFilmById } from '@/services/api';
import type { Person, Film } from '@/types/api';

interface Props {
  id: string;
}

interface DetailItem {
  label: string;
  value: string;
}

interface Link {
  label: string;
  url: string;
}

const props = defineProps<Props>();
const route = useRoute();
const router = useRouter();

const isLoading = ref(true);
const error = ref<string | null>(null);
const personData = ref<Person | null>(null);
const filmData = ref<Film | null>(null);

const isFilm = computed(() => route.name === 'film-details');

const title = computed(() => {
  if (isFilm.value && filmData.value) {
    return filmData.value.title;
  } else if (!isFilm.value && personData.value) {
    return personData.value.name;
  }
  return '';
});

const details = computed<DetailItem[]>(() => {
  if (isFilm.value && filmData.value) {
    return [
      { label: '', value: filmData.value.opening_crawl || '' }
    ];
  } else if (!isFilm.value && personData.value) {
    return [
      { label: 'Birth Year', value: personData.value.birth_year || 'unknown' },
      { label: 'Gender', value: personData.value.gender || 'unknown' },
      { label: 'Eye Color', value: personData.value.eye_color || 'unknown' },
      { label: 'Hair Color', value: personData.value.hair_color || 'unknown' },
      { label: 'Height', value: personData.value.height || 'unknown' },
      { label: 'Mass', value: personData.value.mass || 'unknown' }
    ];
  }
  return [];
});

const links = computed<Link[]>(() => {
  if (isFilm.value && filmData.value && filmData.value.characters) {
    return filmData.value.characters.map(url => ({
      label: url,
      url: '#'
    }));
  } else if (!isFilm.value && personData.value && personData.value.films) {
    return personData.value.films.map(url => ({
      label: url,
      url: '#'
    }));
  }
  return [];
});

const detailsTitle = computed(() => {
  return isFilm.value ? 'Opening Crawl' : 'Details';
});

const linksTitle = computed(() => {
  return isFilm.value ? 'Characters' : 'Movies';
});

const hasData = computed(() => {
  return (isFilm.value && filmData.value) || (!isFilm.value && personData.value);
});

async function loadData() {
  isLoading.value = true;
  error.value = null;

  try {
    if (isFilm.value) {
      const data = await getFilmById(props.id);
      filmData.value = data[0];
    } else {
      const data = await getPersonById(props.id);
      personData.value = data[0];
    }
  } catch (err) {
    error.value = 'Failed to load details. Please try again.';
  } finally {
    isLoading.value = false;
  }
}

function handleBack() {
  router.push({ name: 'home' });
}

onMounted(() => {
  loadData();
});
</script>

<template>
  <div class="details-page">
    <div v-if="isLoading" class="loading-state">
      <p>Loading...</p>
    </div>

    <div v-else-if="error" class="error-state">
      <p>{{ error }}</p>
    </div>

    <DetailsComponent
      v-else-if="hasData"
      :title="title"
      :details-title="detailsTitle"
      :details="details"
      :links-title="linksTitle"
      :links="links"
      @back="handleBack"
    />

    <div v-else class="not-found">
      <p>Details not found</p>
    </div>
  </div>
</template>

<style scoped>
.details-page {
  display: flex;
  justify-content: center;
  padding: 20px;
  width: auto;
  @media screen and (max-width: 768px) {
    width: 100%;
  }
}

.not-found,
.loading-state,
.error-state {
  text-align: center;
  padding: 40px;
  font-family: Montserrat, serif;
}

.not-found,
.loading-state {
  color: #c4c4c4;
}

.error-state {
  color: #e74c3c;
}

@media screen and (max-width: 768px) {
  .details-page {
    padding: 15px;
  }
}

@media screen and (max-width: 425px) {
  .details-page {
    padding: 10px;
  }

  .not-found,
  .loading-state,
  .error-state {
    padding: 20px;
  }
}
</style>
