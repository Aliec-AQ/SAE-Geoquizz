<script setup>
import { ref, onMounted, watch, computed } from 'vue';
import L from 'leaflet';

const props = defineProps({
    defaultCoordinates: {
        type: Object,
        required: true
    },
});

const emit = defineEmits(['validate']);

const map = ref(null);
const marker = ref(null);
const coordinates = ref({ lat: null, lng: null });
const isExpanded = ref(false);

const onMapClick = (e) => {
    if(!isExpanded.value) {
        return;
    }

    if (marker.value) {
        map.value.removeLayer(marker.value);
    }
    coordinates.value = e.latlng;
    marker.value = L.marker(e.latlng, {
        icon: L.icon({
            iconUrl: '/img/pin.png',
            iconSize: [32, 32],
            shadowSize: [32, 32],
            iconAnchor: [16, 32],
            shadowAnchor: [16, 32],
            popupAnchor: [0, -32]
        })
    }).addTo(map.value);
};

onMounted(() => {
    map.value = L.map('map').setView([props.defaultCoordinates.lat, props.defaultCoordinates.long], 13);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map.value);

    map.value.on('click', onMapClick);
});

const confirmGuess = () => {
    emit('validate', { "lat": coordinates.value.lat, "long": coordinates.value.lng });
};

const isCoordinatesSet = computed(() => {
    return coordinates.value.lat !== null && coordinates.value.lng !== null;
});

watch(isExpanded, (newVal) => {
    if (map.value) {
        setTimeout(() => {
            map.value.invalidateSize();
        }, 200);
    }
});
</script>

<template>
    <div id="map-container" :class="{ expanded: isExpanded }" @click.stop="isExpanded = true">
        <button @click="confirmGuess" :disabled="!isCoordinatesSet" title="Confirmer l'emplacement">Valider</button>
        <div id="map-wrapper">
            <div id="map"></div>
        </div>
    </div>
    <span v-if="isExpanded" class="cover" @click="isExpanded = false"></span>
</template>

<style scoped>
:root {
    --background-color: #313338;
    --primary-color: #7289da;
    --secondary-color: #2b2d31;
    --dark-color: #232428;
    --accent-color: #5865f2;
    --text-color: #ffffff;
}

#map-container {
    position: fixed;
    bottom: 0;
    right: 0;
    width: 300px;
    height: 200px;
    transition: all 0.3s ease;
    z-index: 1000;
    background-color: var(--secondary-color);
    color: var(--text-color);
}

#map-container.expanded {
    width: 50%;
    height: 50%;
    bottom: 0;
    right: 0;
}

#map-wrapper {
    height: 100%;
    width: 100%;
}

#map {
    height: 100%;
}

button {
    width: 100%;
    padding: 0.5rem;
    background-color: var(--accent-color);
    color: var(--text-color);
    font-family: "Geo", sans-serif;
    font-size: 1.5rem;
    border: none;
    cursor: pointer;
}

button:disabled {
    background-color: var(--dark-color);
    cursor: not-allowed;
}

.cover {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    z-index: 999;
}
</style>