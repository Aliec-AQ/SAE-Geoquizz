<script setup>
import { onBeforeUnmount, onMounted, ref } from 'vue';
import L from 'leaflet';
import { useGameStore } from '@/stores/game';
import { useRouter } from 'vue-router';

const gameStore = useGameStore();
const guesses = ref([]);
const router = useRouter();

function createMap() {
    const map = L.map('map').setView([gameStore.defaultCoordinates.lat, gameStore.defaultCoordinates.long], 13);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    guesses.value.forEach(guess => {
        if (guess.lat === null) return;

        const markers = [
            { lat: guess.lat, long: guess.long, iconUrl: '/pin.png' },
            { lat: guess.photoLat, long: guess.photoLong, iconUrl: '/location.png' }
        ];

        markers.forEach(marker => {
            L.marker([marker.lat, marker.long], {
                icon: L.icon({
                    iconUrl: marker.iconUrl,
                    iconSize: [32, 32],
                    shadowSize: [32, 32],
                    iconAnchor: [16, 32],
                    popupAnchor: [0, -32]
                })
            }).addTo(map);
        });

        L.polyline([[guess.lat, guess.long], [guess.photoLat, guess.photoLong]], { color: 'black' }).addTo(map);
    });
}

function exit() {
    router.push({ name: 'home' });
}

onMounted(() => {
    guesses.value = gameStore.getGuesses;
    createMap();
    gameStore.saveGame();
});

onBeforeUnmount(() => {
    gameStore.finishGame();
});
</script>

<template>
    <div class="endgame-container">
        <h1><i class="fas fa-flag-checkered"></i> Fin de la partie, Score final : {{ gameStore.getScore }}</h1>
        <div id="map" class="box"></div>
        <div class="guesses-table">
            <div class="guesses-header">
                <div class="guesses-cell">Round</div>
                <div class="guesses-cell">Score</div>
                <div class="guesses-cell">Distance</div>
            </div>
            <div v-for="(guess, index) in guesses" :key="guess.id" :class="['guesses-row', { 'alt-row': index % 2 === 0 }]">
                <div class="guesses-cell">{{ index + 1 }}</div>
                <div class="guesses-cell" v-if="guess.lat !== null">{{ guess.score }}</div>
                <div class="guesses-cell" v-else>Pas répondu à temps</div>
                <div class="guesses-cell" v-if="guess.lat !== null">{{ guess.distance }} km</div>
                <div class="guesses-cell" v-else>Pas répondu à temps</div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.endgame-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    margin: 1.5%;
    border: 3px solid var(--secondary-color-medium);
    position: relative;
    box-shadow: 0 0 10px var(--secondary-color-medium);
}

h1 {
    color: var(--text-color);
    text-align: center;
    margin: 0;
    position: absolute;
    top: 0;
    left: 50%;
    transform: translateX(-50%);
    background-color: var(--primary-color-light);
    z-index: 1000;
    padding: 10px;
    border-radius: 0 0 15px 15px;
}

#map {
    height: 600px;
    width: 100%;
    border-bottom: 2px solid var(--primary-color-dark);
}

.box {
    border: 1px solid var(--primary-color);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    background-color: var(--primary-color-medium);
    text-align: center;
}

.guesses-table {
    display: flex;
    flex-direction: column;
    width: 100%;
    font-size: 1.5rem;
}

.guesses-header, .guesses-row {
    display: flex;
    justify-content: space-between;
    color: white;
    padding: 10px;
    border-bottom: 2px solid var(--primary-color-dark);
}

.guesses-header {
    background-color: var(--secondary-color-dark);
}

.guesses-row {
    background-color: var(--primary-color-medium);
}

.guesses-row.alt-row {
    background-color: var(--primary-color-light);
}

.guesses-cell {
    flex: 1;
    text-align: center;
}

button {
    background-color: var(--primary-color);
    color: white;
    border: none;
    padding: 10px 20px;
    cursor: pointer;
    transition: background-color 0.3s;
}

button:hover {
    background-color: var(--primary-color-dark);
}
</style>
