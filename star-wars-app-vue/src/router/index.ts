import { createRouter, createWebHistory } from 'vue-router'
import Home from "@/pages/Home.vue";
import Details from "@/pages/Details.vue";

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'home',
      component: Home
    },
    {
      path: '/details/film/:id',
      name: 'film-details',
      component: Details,
      props: true
    },
    {
      path: '/details/person/:id',
      name: 'person-details',
      component: Details,
      props: true
    },
  ],
})

export default router
