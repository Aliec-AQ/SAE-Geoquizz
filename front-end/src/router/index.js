import { createRouter, createWebHistory } from 'vue-router'
import { useGameStore } from '@/stores/game'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'home',
      component: () => import('@/views/HomeView.vue'),
    },
    {
      path: '/connexion',
      name: 'connexion',
      component: () => import('@/views/ConnexionView.vue'),
    },
    {
      path: '/game',
      name: 'game',
      redirect: { name: 'game-create' },
      children: [
        {
          path: 'create',
          name: 'game-create',
          component: () => import('@/views/game/CreateGameView.vue'),
        },
        {
          path: 'play',
          name: 'game-play',
          component: () => import('@/views/game/GameView.vue'),
        },
        {
          path: 'end',
          name: 'game-end',
          component: () => import('@/views/game/EndGameView.vue'),
        },
      ],
    }
  ],
})

router.beforeEach((to, from, next) => {
  const gameStore = useGameStore()
  if ((to.name == 'game-play' || to.name == 'game-end') && !gameStore.isInit) {
    console.log('Redirect to create')
    console.log(gameStore.isInit)
    next({ name: 'game-create' })
  }else{
    next()
  }

})

export default router
