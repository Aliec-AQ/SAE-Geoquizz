<script setup>
import EndRound from '@/components/game/EndRound.vue';
import MapComponent from '@/components/game/MapComponent.vue';
import router from '@/router';
import { useGameStore } from '@/stores/game';
import { onBeforeUnmount, onMounted, ref } from 'vue';

// Gestion du jeu
const gameStore = useGameStore();
const defaultCoordinates = ref({});
const currentPhoto = ref(null);
const gameIsOver = ref(false);

// timer
const time = ref(0);
const timer = ref(null);

onMounted(() => {
    nextPhoto();
});

onBeforeUnmount(() => {
    resetGame();
});


function validate(data) {
    gameStore.addGuess(data.lat, data.long, gameStore.maxTime - time.value);
    resetGame();
}

function initGame(){
    defaultCoordinates.value = gameStore.defaultCoordinates;
    currentPhoto.value = gameStore.getCurrentPhoto;

    time.value = gameStore.maxTime;
    timer.value = setInterval(() => {
        if(time.value<=1) {
            validate({lat: null, long: null});
        }
        time.value--;
    }, 1000);
}

function resetGame(){
    clearInterval(timer.value);
    gameIsOver.value = true
    currentPhoto.value = null;
    time.value = 0;
}

function nextPhoto(){
    if(gameStore.isEnded){
        router.push({name: 'game-end'});
    }else{
        initGame();
    }
}

</script>

<template>
    <main>
        <template v-if="currentPhoto">
            <span :key="time" id="timer" :style="[time <= 15 ? 'color:#cc0000;':'']">{{ time }}</span>
            <img :src="currentPhoto.url" alt="image à trouver" />
            <MapComponent :defaultCoordinates="defaultCoordinates" @validate="validate"/>
        </template>
        <template v-else-if="gameIsOver">
            <transition name="slide-up">
                <EndRound :guess="gameStore.getLastGuess" :score="gameStore.getScore" @next="nextPhoto"/>
            </transition>
        </template>
    </main>
</template>


<style scoped>
main{
    flex:1;
    position: relative;
}


#timer{
    position: absolute;
    top: 10px;
    right: 50%;
    transform: translateX(50%);
    background-color: rgba(0,0,0,0.3);
    border: 2px solid var(--primary-color-dark);
    border-radius: 2rem;
    font-size: 2rem;
    padding: 0.5rem 2rem; 
    color: var(--secondary-color-light);

    /*color : #cc0000*/
}

img{
    width: 100%;
    height: 100%;
    object-fit: cover;
}
</style>