<script setup lang="ts">
interface Props {
  title: string
}
const props = defineProps<Props>()

</script>

<template>
  <div class="card">
    <div class="title-div" v-if="title">
      <span class="title">{{title}}</span>
    </div>
    <div class="body">
      <slot name="body"
      ></slot>
    </div>
  </div>
</template>

<style scoped>

.card {
  display: flex;
  flex-direction: column;
  width: inherit;
  padding: 15px 15px 15px;
  height: fit-content;
  border-radius: 5px;
  box-shadow: 0 0.5px 1px 0 #848484bf;
  border: solid 0.5px #dadada;
  background-color: #fff;
  .title-div {
    display: flex;
    flex-direction: row;
    align-content: start;
    width: 100%;
    padding-bottom: 10px;

    .title{
      font-family: Montserrat,serif;
      font-size: 16px;
      font-weight: bold;
      font-stretch: normal;
      font-style: normal;
      line-height: normal;
      letter-spacing: normal;
      color: #000;
    }

    .body {
      display: flex;
      flex-direction: column;
      width: fit-content;
    }
  }

}

</style>
