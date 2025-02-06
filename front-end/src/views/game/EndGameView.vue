<script setup>
import { onBeforeUnmount, onMounted, ref } from 'vue';
import L from 'leaflet';
import { useGameStore } from '@/stores/game';
import { useGame } from '@/services/game';
import { useRouter } from 'vue-router';

const { resetGame } = useGame();
const gameStore = useGameStore();
const guesses = ref([]);
const router = useRouter();

function createMap() {
    const map = L.map('map').setView([gameStore.defaultCoordinates.lat, gameStore.defaultCoordinates.long], 13);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    guesses.value.forEach((guess, index) => {
        const markers = [];

        if (guess.lat !== null) {
            markers.push({ lat: guess.lat, long: guess.long, iconUrl: '/img/pin.png', label: `Round ${index + 1}` });
        }

        markers.push({ lat: guess.photoLat, long: guess.photoLong, iconUrl: '/img/location.png', label: `Photo ${index + 1}` });

        markers.forEach(marker => {
            const markerInstance = L.marker([marker.lat, marker.long], {
                icon: L.icon({
                    iconUrl: marker.iconUrl,
                    iconSize: [32, 32],
                    shadowSize: [32, 32],
                    iconAnchor: [16, 32],
                    popupAnchor: [0, -32]
                })
            }).addTo(map);

            markerInstance.bindPopup(marker.label);
        });

        if (guess.lat !== null) {
            L.polyline([[guess.lat, guess.long], [guess.photoLat, guess.photoLong]], { color: 'black' }).addTo(map);
        }
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
    resetGame();
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
                <div class="guesses-cell" v-else>
                    <i class="fas fa-xmark"></i>
                </div>
                <div class="guesses-cell" v-if="guess.lat !== null">{{ guess.distance }} km</div>
                <div class="guesses-cell" v-else>
                    <i class="fas fa-xmark"></i>
                </div>
            </div>
        </div>
        <button @click="exit">Retour au menu</button>
    </div>
</template>

<style scoped>
.endgame-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    margin: 1.5%;
    position: relative;
    
    border: 3px solid var(--secondary-color);
    box-shadow: 0 0 10px var(--secondary-color);

    height: 90%;
}

h1 {
    color: var(--text-color);
    text-align: center;
    margin: 0;
    position: absolute;
    top: 0;
    left: 50%;
    transform: translateX(-50%);
    background-color: var(--accent-color);
    z-index: 1000;
    padding: 10px;
    border-radius: 0 0 15px 15px;

    border-right: 4px solid var(--background-color);
    border-left: 4px solid var(--background-color);
    border-bottom: 4px solid var(--background-color);
}

#map {
    height: 600px;
    width: 100%;
    border-bottom: 2px solid var(--dark-color);
}

.box {
    border: 1px solid var(--primary-color);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    background-color: var(--secondary-color);
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
    color: var(--text-color);
    padding: 10px;
    border-bottom: 2px solid var(--dark-color);
}

.guesses-header {
    background-color: var(--dark-color);
}

.guesses-row {
    background-color: var(--secondary-color);
}

.guesses-row.alt-row {
    background-color: var(--background-color);
}

.guesses-cell {
    flex: 1;
    text-align: center;
}

button {
    background-color: var(--primary-color);
    color: var(--text-color);
    font-family: 'Lexend';
    border: none;
    padding: 10px 20px;
    cursor: pointer;
    transition: background-color 0.3s;
    margin: 10px;
}

button:hover {
    background-color: var(--accent-color);
}

@media (max-width: 768px) {
    h1 {
        font-size: 1.5rem;
        width: 90%;
        border-radius: 0;
        border: none;
    }

    #map {
        height: 400px;
    }

    .guesses-table {
        font-size: 1rem;
    }

    .guesses-cell {
        padding: 5px;
    }

    button {
        padding: 5px 10px;
        font-size: 2rem;
    }
}
</style>
