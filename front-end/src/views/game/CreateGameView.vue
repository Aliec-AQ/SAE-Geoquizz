<script setup>
import { ref, onMounted } from 'vue';
import { useGame } from '@/services/game';
import { useUserStore } from '@/stores/user';
import { useRouter } from 'vue-router';
import { useToast } from 'vue-toastification';

const { startGame, getPublicGames, getSeries } = useGame();
const userStore = useUserStore();
const router = useRouter();
const toast = useToast();

const publicGames = ref([]);
const series = ref([]);
const selectedGame = ref(null);
const showModal = ref(false);

const fetchPublicGames = async () => {
    try {
        publicGames.value = await getPublicGames();
    } catch (error) {
        toast.error('Failed to fetch public games');
    }
};

const getSeriesData = async () => {
    try {
        series.value = await getSeries();
    } catch (error) {
        toast.error('Failed to fetch series');
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

onMounted(() => {
    fetchPublicGames();
    getSeriesData();
    if (!userStore.isSignedIn) {
        showModal.value = true;
    }
});
</script>

<template>
    <div class="container">
        <div class="public-games">
            <h2>Parties publiques</h2>
            <div class="games-grid">
                <div v-for="game in publicGames" :key="game.id" class="game-card" v-if="publicGames.length > 0">
                    <p>{{ game.name }}</p>
                </div>
                <p v-else>Aucune partie publique disponible</p>
            </div>
        </div>
        <div class="create-game-container">
            <div class="create-game-form">
                <h2>Créer une partie</h2>
                <select v-model="selectedGame">
                    <option disabled value="">Sélectionnez un thème de photos</option>
                    <option v-for="serie in series" :key="serie.id" :value="serie.id">{{ serie.nom }}</option>
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
    justify-content: space-between;
    padding: 20px;
    background-color: var(--background-color);
    color: var(--text-color);
}

.public-games, .create-game-container {
    width: 45%;
    position: relative;
    background-color: var(--secondary-color);
    padding: 20px;
    border-radius: 10px;
}

.games-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
    gap: 10px;
}

.game-card {
    padding: 10px;
    font-size: 1.3rem;
    border: 1px solid var(--dark-color);
    border-radius: 5px;
    text-align: center;
    background-color: var(--dark-color);
}

.create-game-form {
    display: flex;
    flex-direction: column;
    gap: 10px;
    height: 300px;
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
</style>