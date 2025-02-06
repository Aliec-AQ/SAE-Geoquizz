<script setup>
import { useUserStore } from '@/stores/user'
import { useRouter } from 'vue-router'
import SwitchThemeButton from './buttons/SwitchThemeButton.vue';

const userStore = useUserStore();
const router = useRouter();

const logout = () => {
  userStore.logout();
  router.push({ name: 'home' });
};
</script>

<template>
    <header>
        <nav id="app-nav">
            <div class="app-title" @click="router.push({ name: 'home' })" title="Accueil">
                <img src="/favicon.ico" alt="Logo" width="40" height="40">
                <span>Geoquizz</span>
            </div>
            
            <span class="separator"></span>
            
            <button @click="router.push({ name: 'game-create' })" title="Créer une partie">
                <i class="fas fa-plus-circle"></i>
            </button>

            <span class="separator"></span>

            <SwitchThemeButton />

            <span class="separator"></span>
            
            <template v-if="userStore.isSignedIn">
                <button @click="router.push({ name: 'profil' })" title="Profil">
                    <i class="fas fa-user"></i>
                </button>
                <button @click="logout" title="Déconnexion">
                    <i class="fas fa-sign-out-alt"></i>
                </button>
            </template>
            <template v-else>
                <button @click="router.push({ name: 'connexion' })" title="Connexion">
                    <i class="fas fa-sign-in-alt"></i>
                </button>
            </template>

        </nav>
    </header>
</template>

<style scoped>

header {
    padding: 0.5rem;
    background-color: var(--dark-color);
    color: var(--text-color);
}

#app-nav {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.app-title {
  font-size: 2rem;
  font-weight: bold;
  margin-right: auto;
  cursor: pointer;
  display: flex;
  align-items: center;
  gap: 0.5rem;
  color: var(--text-color);
}

.separator {
  width: 2px;
  height: 2rem;
  background-color: var(--accent-color);
}

#app-nav button {
    border: none;
    font-size: 1.5rem;
    cursor: pointer;
    background-color: transparent;
    color: var(--text-color);
}

#app-nav button:hover {
    color: var(--accent-color);
}
</style>