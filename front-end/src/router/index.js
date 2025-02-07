import { createRouter, createWebHistory } from 'vue-router'
import { useGameStore } from '@/stores/game'
import { useUserStore } from '@/stores/user'
import { useThemeStore } from '@/stores/theme'
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
    },
    {
      path: '/profil',
      name: 'profil',
      component: () => import('@/views/ProfilView.vue'),
    }
  ],
})

router.beforeEach(async (to, from, next) => {




  // verifications pour les routes de jeu
  const gameStore = useGameStore()
  const themeStore = useThemeStore()
  
  if (!themeStore.isInit) {
    await themeStore.loadSeries()
  }

  if ((to.name == 'game-play' || to.name == 'game-end') && !gameStore.isInit) {
    next({ name: 'game-create' })
  }

  if(to.name == 'game-create' && gameStore.isInit) {
    next({ name: 'game-play' })
  }


  // verifications pour les routes de profil
  const userStore = useUserStore();
  if (to.name == 'profil' && !userStore.isSignedIn) {
    next({ name: 'connexion' })
  }else{
    next()
  }
})

export default router