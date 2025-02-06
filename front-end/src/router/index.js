import { createRouter, createWebHistory } from 'vue-router'
import { useGameStore } from '@/stores/game'
import HomeView from '@/views/HomeView.vue'
import ConnexionView from '@/views/ConnexionView.vue'
import CreateGameView from '@/views/game/CreateGameView.vue'
import GameView from '@/views/game/GameView.vue'
import EndGameView from '@/views/game/EndGameView.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'home',
      component: HomeView,
    },
    {
      path: '/connexion',
      name: 'connexion',
      component: ConnexionView,
    },
    {
      path: '/game',
      name: 'game',
      redirect: { name: 'game-create' },
      children: [
        {
          path: 'create',
          name: 'game-create',
          component: CreateGameView,
        },
        {
          path: 'play',
          name: 'game-play',
          component: GameView,
        },
        {
          path: 'end',
          name: 'game-end',
          component: EndGameView,
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
  } else {
    next()
  }
})

export default router