<script setup>
import { ref, onMounted } from 'vue';
import { useGame } from '@/services/game';
import { useUserStore } from '@/stores/user';
import { useThemeStore } from '@/stores/theme';
import { useRouter } from 'vue-router';
import { useToast } from 'vue-toastification';

const { startGame, getPublicGames, replayGame } = useGame();
const userStore = useUserStore();
const themeStore = useThemeStore();
const router = useRouter();
const toast = useToast();

const publicGames = ref([]);
const selectedGame = ref(null);
const showModal = ref(false);

const fetchPublicGames = async () => {
    try {
        publicGames.value = await getPublicGames();
    } catch (error) {
        toast.error('Erreur lors de la récupération des parties publiques');
    }
};

const createGame = async () => {
    if(!selectedGame.value){
        toast.warning('Veuillez selectionner un thème de photos');
        return;
    }
    await startGame(selectedGame.value);
    router.push({ name: 'game-play' });
};

const replayPublicGame = async (gameId) => {
    await replayGame(gameId);
    router.push({ name: 'game-play' });
};

onMounted(() => {
    fetchPublicGames();
    if (!userStore.isSignedIn) {
        showModal.value = true;
    }
    selectedGame.value = themeStore.getSeries[0].id;
});
</script>

<template>
    <div class="container">
        <div class="public-games">
            <h2>Parties publiques</h2>
            <div>
        <div v-for="game in publicGames" :key="game.id" class="game-card" v-if="publicGames.length">
            <div class="game-info">
                <div>{{ game.theme }} ({{ game.sequence_id }})</div>
                <div>Score: {{ game.score }}</div>
            </div>
            <div class="game-actions">
                <button @click="replayPublicGame(game.sequence_id)" title="Jouer la séquence" class="game-button">
                    <i class="fa fa-play"></i>
                </button>
            </div>
        </div>
        <p v-else>Aucune partie publique disponible</p>
    </div>
        </div>
        <div class="create-game-container">
            <div class="create-game-form">
                <h2>Créer une partie</h2>
                <select v-model="selectedGame" class="custom-select">
                    <option disabled value="">Sélectionnez un thème de photos</option>
                    <option v-for="(serie, index) in themeStore.getSeries" :key="serie.id" :value="serie.id">{{ serie.nom }}</option>
                </select>
                <button @click="createGame" class="create-game-button">
                    Créer la partie
                </button>
            </div>
            <div v-if="showModal" class="modal">
                <div class="modal-content">
                    <p>Il faut être authentifé pour créer une partie</p>
                    <button @click="router.push({ name: 'connexion' })">Se connecter</button>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.container {
    display: flex;
    flex-direction: column-reverse;
    padding: 20px;
    background-color: var(--background-color);
    color: var(--text-color);
}

.public-games, .create-game-container {
    margin-bottom: 20px;
    background-color: var(--secondary-color);
    padding: 20px;
    border-radius: 10px;
}

.create-game-container{
    position: relative;
}

.game-card {
  background-color: var(--secondary-color);
  margin-bottom: 10px;
  padding: 10px;
  border-radius: 4px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  transition: background-color 0.3s;
  border: 2px solid var(--dark-color);
}

.game-card:hover {
  background-color: var(--dark-color);
}

.game-info {
  flex: 1;
}

.game-actions {
  display: flex;
  gap: 10px;
}

.game-button{
  background-color: var(--primary-color);
  color: var(--text-color);
  font-size: 1.2em;
  border: none;
  padding: 5px;
  border-radius: 4px;
  cursor: pointer;
}


.create-game-form {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.custom-select {
    padding: 10px;
    font-size: 1rem;
    border: 1px solid var(--dark-color);
    border-radius: 5px;
    background-color: var(--background-color);
    color: var(--text-color);
    appearance: none;
    -webkit-appearance: none;
    -moz-appearance: none;
    background-image: url('data:image/svg+xml;charset=US-ASCII,<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chevron-down" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z"/></svg>');
    background-repeat: no-repeat;
    background-position: right 10px center;
    background-size: 16px;
}

.custom-select:focus {
    outline: none;
    border-color: var(--primary-color);
}

.create-game-button {
    background-color: var(--primary-color);
    border: none;
    color: var(--text-color);
    padding: 15px 32px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: 16px;
    margin: 4px 2px;
    cursor: pointer;
    border-radius: 4px;
    transition: background-color 0.3s ease;
}

.create-game-button:hover {
    background-color: var(--accent-color);
}

.modal {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.767);
    display: flex;
    justify-content: center;
    align-items: center;
}

.modal-content {
    background-color: var(--dark-color);
    padding: 20px;
    font-size: 1.3rem;
    border-radius: 5px;
    text-align: center;
    color: var(--text-color);
}

.modal-content button {
    background-color: var(--primary-color);
    border: none;
    color: var(--text-color);
    padding: 15px 32px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: 16px;
    margin: 4px 2px;
    cursor: pointer;
    border-radius: 4px;
    transition: background-color 0.3s ease;
}

.modal-content button:hover {
    background-color: var(--accent-color);
}

@media (min-width: 768px) {
    .container {
        flex-direction: row;
        justify-content: space-between;
    }

    .public-games, .create-game-container {
        width: 45%;
        margin-bottom: 0;
    }
}
</style>