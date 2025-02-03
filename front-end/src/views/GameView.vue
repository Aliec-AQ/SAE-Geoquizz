<script setup>
import MapComponent from '@/components/game/MapComponent.vue';

</script>

<template>
    <img src="https://www.nancy-tourisme.fr/app/uploads/nancy/2019/01/place-stanislas-002-andre-thomas-1920x960.jpg" alt="image à trouver" />
    <MapComponent :defaultCoordinates="{ lat: 48.8566, lng: 2.3522 }" :realCoordinates="{ lat: 48.8566, lng: 2.3522 }" />
</template>


<style scoped>

</style>