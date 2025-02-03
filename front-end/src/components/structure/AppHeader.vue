<script setup>
import { useUserStore } from '@/stores/user'
import { useRouter } from 'vue-router'

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
                <i class="fas fa-globe"></i>
                <span>Geoquizz</span>
            </div>
            
            <span class="separator"></span>
            
            <button @click="router.push({ name: 'game' })" title="Jouer">
                <i class="fas fa-play"></i>
            </button>

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
    background-color: var(--primary-color-dark);
    color: var(--secondary-color-light);
    padding: 0.5rem;
}

#app-nav {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.app-title {
  font-size: 1.5rem;
  font-weight: bold;
  margin-right: auto;
  cursor: pointer;
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.separator {
  width: 2px;
  height: 2rem;
  background-color: var(--primary-color-medium);
}

#app-nav button {
    background-color: transparent;
    color: var(--secondary-color-light);
    border: none;
    font-size: 1.5rem;
    cursor: pointer;
}

#app-nav button:hover {
    color: var(--primary-color-light);
}
</style>