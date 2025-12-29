<script setup lang="ts">
import { useRouter } from 'vue-router';
import Button from "@/components/shared/Button.vue";

interface Props {
  id: string;
  name: string;
  searchType: 'people' | 'movies';
}

const props = defineProps<Props>();
const router = useRouter();

function handleClick() {
  router.push({
    name: props.searchType === 'people' ? 'person-details' : 'film-details',
    params: { id: props.id }
  });
}
</script>

<template>
  <div class="list-item">
    <div class="label">
      <span>{{ name }}</span>
    </div>
    <div class="action-button">
      <Button
        label="SEE DETAILS"
        @clicked="handleClick"
      />
    </div>
  </div>
</template>

<style scoped>

.list-item {
  display: flex;
  flex-direction: row;
  align-items: center;
  border-top: 1px #c4c4c4 solid;
  height: 55px;
  justify-content: space-between;
  min-width: 450px;
  @media screen and (max-width: 768px) {
    flex-direction: column;
    height: auto;
    padding: 10px;
    align-items: flex-start;
    gap: 10px;
    min-width: 100%;
  }

  .label {
    display: flex;
    height: 10px;
    padding-bottom: 10px;
    span {
      width: inherit;
      height: inherit;
      font-family: Montserrat,serif;
      font-size: 16px;
      font-weight: bold;
      font-stretch: normal;
      font-style: normal;
      line-height: normal;
      letter-spacing: normal;
      color: #000;
    }
  }
  .action-button {
    width: 200px;
    height: 40px;
    @media screen and (max-width: 768px) {
      width: 100%;
      min-width: 100%;
    }
  }
}

</style>
