<script setup lang="ts">


interface Props {
  label: string;
  disabled?: boolean;
}
const props = defineProps<Props>();

const emit = defineEmits(['clicked']);

function buttonClick() {
  if (!props.disabled) {
    emit('clicked')
  }
}

</script>

<template>
  <button
    class="primary"
    :disabled="disabled"
    @click="buttonClick"
  >{{label}}</button>

</template>

<style scoped>

.primary {
  width: inherit;
  height: 40px;
  border-radius: 20px;
  border: none;
  background-color: #0ab463;
  font-family: Montserrat, serif;
  font-size: 16px;
  font-weight: bold;
  font-stretch: normal;
  font-style: normal;
  line-height: normal;
  letter-spacing: normal;
  color: #fff;
  cursor: pointer;
}

.primary:hover {
  background-color: #089954;
}

.primary:disabled {
  background-color: #cccccc;
  cursor: not-allowed;
  opacity: 0.6;
}

</style>
