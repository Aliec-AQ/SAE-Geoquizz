<script setup>
import { onMounted, ref } from 'vue';
import L from 'leaflet';

const props = defineProps({
    guess: {
        type: Object,
        required: true
    },
    score: {
        type: Number,
        required: true
    }
});

const emit = defineEmits(['next']);
const timeout = ref(false);

onMounted(() => {
    const map = L.map('map').setView([props.guess.photoLat, props.guess.photoLong], 16);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    const photoMarker = L.marker([props.guess.photoLat, props.guess.photoLong], {
        icon: L.icon({
            iconUrl: '/img/location.png',
            iconSize: [32, 32],
            shadowSize: [32, 32],
            iconAnchor: [16, 32],
            shadowAnchor: [16, 32],
            popupAnchor: [0, -32]
        })
    }).addTo(map);
    
    if (props.guess.lat === null) {
        timeout.value = true;
        return;
    }

    const guessMarker = L.marker([props.guess.lat, props.guess.long], {
        icon: L.icon({
            iconUrl: '/img/pin.png',
            iconSize: [32, 32],
            shadowSize: [32, 32],
            iconAnchor: [16, 32],
            shadowAnchor: [16, 32],
            popupAnchor: [0, -32]
        })
    }).addTo(map);

    const latlngs = [
        [props.guess.lat, props.guess.long],
        [props.guess.photoLat, props.guess.photoLong]
    ];

    const polyline = L.polyline(latlngs, { color: 'black' }).addTo(map);

    map.fitBounds(polyline.getBounds());
});

const nextPhoto = () => {
    console.log('next');
    emit('next');
};
</script>

<template>
    <div id="modal">
        <div id="modal-content">
            <div id="map" style="height: 500px;"></div>
            <div v-if="timeout" class="info-container">
                <p>Pas répondu à temps</p>
            </div>
            <div class="info-container">
                <p><i class="fas fa-map-marker-alt"></i> Distance de l'emplacement : {{ props.guess.distance }} km</p>
                <p><i class="fas fa-star"></i> Score gagné : {{ props.guess.score }}</p>
                <p><i class="fas fa-trophy"></i> Score total : {{ score }}</p>
                <button @click="nextPhoto" class="btn btn-primary">Round Suivant</button>
            </div>
        </div>
    </div>
</template>

<style scoped>

#modal {
    background-color: rgba(0, 0, 0, 0.5);
    
    display: flex;
    justify-content: center;
    align-items: center;

    width:100%;
    height:100%;

    
    position: absolute;
    top:0;
    left:0;
}

#modal-content {
    display: flex;
    flex-direction: column;
    min-width: 800px;
    max-width: 1200px;
    background-color: var(--dark-color);
    border: 3px solid var(--accent-color);
    border-radius: 10px;
    box-shadow: 0 0 10px var(--accent-color);
}

#map {
    border-radius: 10px 10px 0 0;
}

.info-container {
    margin-top: 20px;
    text-align: center;
    padding: 20px;
}

.info-container p {
    color: var(--text-color);
    font-size: 1.5rem;
    margin: 10px 0;
}

.btn {
    margin-top: 10px;
    padding: 10px 20px;
    font-size: 16px;
    cursor: pointer;
    background-color: var(--primary-color);
    color: white;
    border: none;
    border-radius: 5px;
    transition: background-color 0.3s ease;
    font-family: "Lexend", sans-serif;
    font-size: 1.5rem;
}

.btn:hover {
    background-color: var(--accent-color);
}

@media (max-width: 768px) {
    #modal-content {
        min-width: 90%;
        border-radius: 0;
    }
    
}
</style>
