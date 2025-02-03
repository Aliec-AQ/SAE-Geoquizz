<script setup>
import { ref, onMounted, watch, computed } from 'vue';
import L from 'leaflet';
import { defineProps, onBeforeUnmount } from 'vue';

const props = defineProps({
    defaultCoordinates: {
        type: Object,
        required: true
    },
    realCoordinates: {
        type: Object,
        required: true
    }
});

const map = ref(null);
const marker = ref(null);
const coordinates = ref({ lat: null, lng: null });
const isExpanded = ref(false);

const onMapClick = (e) => {
    if (marker.value) {
        map.value.removeLayer(marker.value);
    }
    coordinates.value = e.latlng;
    marker.value = L.marker(e.latlng, {
        icon: L.icon({
            iconUrl: './pin.png',
            iconSize: [32, 32],
            shadowSize: [32, 32],
            iconAnchor: [16, 32],
            shadowAnchor: [16, 32],
            popupAnchor: [0, -32]
        })
    }).addTo(map.value);
};

onMounted(() => {
    map.value = L.map('map').setView([props.defaultCoordinates.lat, props.defaultCoordinates.lng], 13);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map.value);

    map.value.on('click', onMapClick);
    document.addEventListener('click', handleDocumentClick);
});

onBeforeUnmount(() => {
    document.removeEventListener('click', handleDocumentClick);
});

const logCoordinates = () => {
    L.marker(props.realCoordinates, {
        icon: L.icon({
            iconUrl: './location.png',
            iconSize: [32, 32],
            shadowSize: [32, 32],
            iconAnchor: [16, 32],
            shadowAnchor: [16, 32],
            popupAnchor: [0, -32]
        })
    }).addTo(map.value);

    if (coordinates.value.lat !== null && coordinates.value.lng !== null) {
        L.polyline([coordinates.value, props.realCoordinates], { color: 'black' }).addTo(map.value);
    }

    map.value.off('click', onMapClick);
    map.value.setView(props.realCoordinates, 15);
};

const isCoordinatesSet = computed(() => {
    return coordinates.value.lat !== null && coordinates.value.lng !== null;
});

watch(() => props.realCoordinates, () => {
    if (map.value) {
        logCoordinates();
    }
});

const toggleMapSize = () => {
    isExpanded.value = !isExpanded.value;
};

const handleDocumentClick = (e) => {
    if (!e.target.closest('#map-container') && isExpanded.value) {
        isExpanded.value = false;
    }
};
</script>

<template>
    <div id="map-container" :class="{ expanded: isExpanded }" @click.stop="toggleMapSize">
        <button @click="logCoordinates" :disabled="!isCoordinatesSet">Valider</button>
        <div id="map" style="height: 500px;"></div>
    </div>
</template>

<style scoped>
#map-container {
    position: fixed;
    bottom: 10px;
    right: 10px;
    width: 300px;
    height: 200px;
    transition: all 0.3s ease;
    z-index: 1000;

    border: 2px solid var(--primary-color-dark);
    border-radius: 5px;
}

#map-container.expanded {
    width:50%;
    height: 50%;
    bottom: 0;
    right: 0;
}

#map {
    height: 100%;
    width: 100%;
}

button {
    width: 100%;
    padding: 0.5rem;
    background-color: var(--primary-color-dark);
    color: var(--secondary-color-light);
}
</style>
