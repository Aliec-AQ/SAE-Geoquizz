<script setup>
import EndRound from '@/components/game/EndRound.vue';
import MapComponent from '@/components/game/MapComponent.vue';
import router from '@/router';
import { useGame } from '@/services/game';
import { useGameStore } from '@/stores/game';
import { onBeforeUnmount, onMounted, ref } from 'vue';

// Gestion du jeu
const { validate, gameConfig} = useGame();
const currentPhoto = ref(null);
const gameIsOver = ref(false);
const gameStore = useGameStore();

// timer
const time = ref(0);
const timer = ref(null);

onMounted(() => {
    nextPhoto();
});

onBeforeUnmount(() => {
    resetGame();
});

function submitGuess(data) {
    validate(data.lat, data.long, gameConfig.time - time.value);
    resetGame();
}

function initGame(){
    gameIsOver.value = false;
    currentPhoto.value = gameStore.getCurrentPhoto;

    time.value = gameConfig.time;
    timer.value = setInterval(() => {
        if(time.value<=1) {
            submitGuess({lat: null, long: null});
        }
        time.value--;
    }, 1000);
}

function resetGame(){
    clearInterval(timer.value);
    gameIsOver.value = true
    time.value = 0;
}

function nextPhoto(){
    console.log('next');
    if(gameStore.isEnded){
        console.log('end');
        router.push({name: 'game-end'});
    }else{
        initGame();
    }
}

</script>

<template>
    <main>
        <template v-if="currentPhoto">
            <span :key="time" id="timer" :style="[time <= 15 ? 'color:#cc0000;':'']" v-if="!gameIsOver">{{ time }}</span>
            <img :src="currentPhoto.url" alt="image à trouver" />
            <MapComponent :defaultCoordinates="gameConfig.defaultCoordinates" @validate="submitGuess" v-if="!gameIsOver"/>

            <transition-group name="fade" appear>
                <EndRound :guess="gameStore.getLastGuess" :score="gameStore.getScore" @next="nextPhoto" v-if="gameIsOver"/>
            </transition-group>
        </template>
    </main>
</template>

<style scoped>
main{
    flex: 1;
    overflow: hidden;
    position: relative;
}

#timer{
    position: absolute;
    top: 10px;
    right: 50%;
    transform: translateX(50%);
    background-color: rgba(0, 0, 0, 0.562);
    border: 2px solid var(--dark-color);
    border-radius: 2rem;
    font-size: 2rem;
    padding: 0.5rem 2rem; 
    color: var(--accent-color);
}

img{
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.fade-enter-active, .fade-leave-active {
    transition: opacity 0.5s;
}
.fade-enter-from, .fade-leave-to {
    opacity: 0;
}
.fade-enter-to, .fade-leave-from {
    opacity: 1;
}
</style>