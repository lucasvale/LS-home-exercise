<script setup lang="ts">
import Card from './shared/Card.vue';
import Button from './shared/Button.vue';
import LinkList from './shared/LinkList.vue';

interface DetailItem {
  label: string;
  value: string;
}

interface Link {
  label: string;
  url: string;
}

interface Props {
  title: string;
  details: DetailItem[];
  detailsTitle: string;
  links?: Link[];
  linksTitle?: string;
}

defineProps<Props>();

const emit = defineEmits(['back']);

function handleBack() {
  emit('back');
}
</script>

<template>
  <Card :title="title">
    <template #body>
      <div class="details-container">
        <div class="details-grid">
          <div class="details-section">
            <h3 class="section-title">{{ detailsTitle }}</h3>
            <div class="details-content">
              <div
                v-for="(detail, index) in details"
                :key="index"
                class="detail-item"
              >
                <span v-if="detail.label" class="detail-label">{{ detail.label }}:</span>
                <span class="detail-value">{{ detail.value }}</span>
              </div>
            </div>
          </div>

          <div v-if="links && links.length > 0" class="links-section">
            <LinkList :title="linksTitle || 'Links'" :links="links" />
          </div>
        </div>

        <div class="button-container">
          <Button label="BACK TO SEARCH" @clicked="handleBack" />
        </div>
      </div>
    </template>
  </Card>
</template>

<style scoped>
.details-container {
  display: flex;
  flex-direction: column;
  gap: 24px;
  width: 100%;
}

.details-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 120px;

  .details-section {
    min-width: 250px;
    max-width: 450px;
  }
}

.section-title {
  font-family: Montserrat, serif;
  font-size: 16px;
  font-weight: bold;
  color: #000;
  margin: 0 0 12px 0;
  padding-bottom: 8px;
  border-bottom: 1px solid #dadada;
}

.links-section{
  min-width: 250px;
  max-width: 450px;
}

.details-content {
  display: flex;
  flex-direction: column;
  gap: 8px;
}

.detail-item {
  font-family: Montserrat, serif;
  font-size: 14px;
  color: #000;
  line-height: 1.6;
}

.detail-label {
  font-weight: 600;
}

.detail-value {
  font-weight: normal;
  white-space: pre-wrap;
}

.button-container {
  display: flex;
  justify-content: flex-start;
  margin-top: 16px;
  width: 200px;
}

@media screen and (max-width: 768px) {
  .details-grid {
    grid-template-columns: 1fr;
    gap: 40px;
  }

  .details-grid .details-section {
    min-width: auto;
    max-width: 100%;
  }

  .links-section {
    min-width: auto;
    max-width: 100%;
  }

  .button-container {
    width: 100%;
  }
}

@media screen and (max-width: 425px) {
  .details-grid {
    gap: 24px;
  }

  .details-container {
    gap: 16px;
  }
}
</style>
