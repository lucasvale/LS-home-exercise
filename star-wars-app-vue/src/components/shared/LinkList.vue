<script setup lang="ts">
interface Link {
  label: string;
  url: string;
}

interface Props {
  links: Link[];
  title: string;
}

defineProps<Props>();

</script>

<template>
  <div class="link-list">
    <h3 class="link-list-title">{{ title }}</h3>
    <div class="links">
      <a
        v-for="(link, index) in links"
        target="_blank"
        :key="index"
        :href="link.url"
        class="link"
      >
        {{ link.label }}
      </a>
    </div>
  </div>
</template>

<style scoped>
.link-list {
  display: flex;
  flex-direction: column;
  gap: 12px;
}

.link-list-title {
  font-family: Montserrat, serif;
  font-size: 16px;
  font-weight: bold;
  color: #000;
  margin: 0;
  padding-bottom: 8px;
  border-bottom: 1px solid #dadada;
}

.links {
  display: flex;
  flex-wrap: wrap;
  gap: 8px;
}

.link {
  font-family: Montserrat, serif;
  font-size: 14px;
  color: #0ab463;
  text-decoration: none;
  cursor: pointer;
  width: auto;
}

.link:hover {
  text-decoration: underline;
}

.link:not(:last-child)::after {
  content: ',';
  color: #000000;
}
</style>
