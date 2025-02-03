<script setup>
import { onBeforeUnmount, onMounted, ref } from 'vue';
import L from 'leaflet';
import { useGameStore } from '@/stores/game';
import { useRouter } from 'vue-router';

const gameStore = useGameStore();
const guesses = ref([]);
const router = useRouter();

function createMap(){
    const map = L.map('map').setView([gameStore.defaultCoordinates.lat, gameStore.defaultCoordinates.long], 13);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    guesses.value.forEach(guess => {
        if(guess.lat === null){
            return;
        }
        const guessMarker = L.marker([guess.lat, guess.long], {
            icon: L.icon({
                iconUrl: '/pin.png',
                iconSize: [32, 32],
                shadowSize: [32, 32],
                iconAnchor: [16, 32],
                popupAnchor: [0, -32]
            })
        }).addTo(map);
        const photoMarker = L.marker([guess.photoLat, guess.photoLong], {
            icon: L.icon({
                iconUrl: '/location.png',
                iconSize: [32, 32],
                shadowSize: [32, 32],
                iconAnchor: [16, 32],
                popupAnchor: [0, -32]
            })
        }).addTo(map);

        const latlngs = [
            [guess.lat, guess.long],
            [guess.photoLat, guess.photoLong]
        ];

        const polyline = L.polyline(latlngs, { color: 'black' }).addTo(map);
    });
}

function exit(){
    router.push({name: 'home'});
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
    flex:1;
    flex-direction: column;
    align-items: center;
    width: 100%;
}

h1 {
    color: var(--primary-color-dark);
    text-align: center;
    margin-bottom: 20px;
}

#map {
    height: 500px;
    width: 100%;
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
    flex:1;
    font-size: 1.5rem;
}

.guesses-header, .guesses-row {
    display: flex;
    justify-content: space-between;
    color: white;
    padding: 10px;
    border-bottom: 2px solid var(--primary-color-dark);
}

.guesses-row {
    background-color: var(--primary-color-medium);
}

.guesses-header {
    background-color: var(--secondary-color-dark);
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