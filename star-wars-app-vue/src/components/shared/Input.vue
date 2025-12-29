<script setup lang="ts">
interface Props {
  modelValue: string;
  placeholder?: string;
  type?: string;
}
const props = defineProps<Props>()

const emit = defineEmits<{
  'update:modelValue': [value: string]
}>();

function handleInput(event: Event) {
  const target = event.target as HTMLInputElement;
  emit('update:modelValue', target.value);
}
</script>

<template>
  <input
    :value="props.modelValue"
    :placeholder="props.placeholder"
    :type="props.type"
    @input="handleInput"
    class="input-field"
  />
</template>

<style scoped>
.input-field {
  width: auto;
  height: 40px;
  padding: 0 12px;
  border-radius: 8px;
  border: 1px solid #ddd;
  font-family: Montserrat, serif;
  font-size: 14px;
  outline: none;
}

.input-field:focus {
  border-color: #0ab463;
}
</style>
