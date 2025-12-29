<script setup lang="ts">
import { RouterView } from 'vue-router';
</script>

<template>
  <header>
    <span>SWStarter</span>
  </header>
  <div class="app">
    <RouterView />
  </div>
</template>

<style scoped>

  header {
    width: auto;
    height: 38px;
    margin: 0 0 15px;
    padding: 7px;
    box-shadow: 0 0.5px 1px 0 #848484bf;
    background-color: #fff;
    display: flex;
    justify-content: center;
    align-items: baseline;
    span {
      font-family: Montserrat, serif;
      font-weight: bold;
      font-size: 26px;
      font-stretch: normal;
      font-style: normal;
      line-height: normal;
      letter-spacing: normal;
      color: #0ab463;
    }
  }

  @media screen and (max-width: 768px) {
    .app{
      display: flex;
      flex-direction: column;
      align-items: center;
    }
  }

  @media screen and (max-width: 425px) {

  }
</style>
