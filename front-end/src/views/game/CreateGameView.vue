<script setup>
import { useGameStore } from '@/stores/game';
import { useRouter } from 'vue-router';

const gameStore = useGameStore();
const router = useRouter();

const createGame = async () => {
    await gameStore.createGame();
    router.push({ name: 'game-play' });
}

</script>

<template>
    <button @click="createGame" class="create-game-button">
        Créer une partie
    </button>
</template>

<style scoped>
.create-game-button {
    background-color: #4CAF50; /* Green */
    border: none;
    color: white;
    padding: 15px 32px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: 16px;
    margin: 4px 2px;
    cursor: pointer;
    border-radius: 4px;
}

.create-game-button:hover {
    background-color: #45a049;
}
</style>