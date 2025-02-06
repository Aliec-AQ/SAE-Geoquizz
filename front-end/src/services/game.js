import { useGameStore } from "@/stores/game";
import { inject } from "vue";

export function useGame(){

    /* Fonctions en rapport avec le déroulement d'une partie */

    const gameStore = useGameStore();
    const gameConfig = gameStore.getGameConfig;

    function calculateDistance(lat1Deg, lon1Deg, lat2Deg, lon2Deg) {
        function toRad(degree) {
            return degree * Math.PI / 180;
        }

        const lat1 = toRad(lat1Deg);
        const lon1 = toRad(lon1Deg);
        const lat2 = toRad(lat2Deg);
        const lon2 = toRad(lon2Deg);

        const { sin, cos, sqrt, atan2 } = Math;

        const R = 6371;
        const dLat = lat2 - lat1;
        const dLon = lon2 - lon1;
        const a = sin(dLat / 2) * sin(dLat / 2)
            + cos(lat1) * cos(lat2)
            * sin(dLon / 2) * sin(dLon / 2);
        const c = 2 * atan2(sqrt(a), sqrt(1 - a));
        const d = R * c;
        return d.toFixed(2);
    }

    function calculateScore(distance, time) {
        let points = 0;

        if (distance < gameConfig.distance) {
            points = 5;
        } else if (distance < gameConfig.distance * 2) {
            points = 3;
        } else if (distance < gameConfig.distance * 6) {
            points = 1;
        }

        if (time < gameConfig.time / 6) {
            points *= 4;
        } else if (time < gameConfig.time / 3) {
            points *= 2;
        } else if (time > gameConfig.time / 2 + gameConfig.time / 4) {
            points = 0;
        }

        return points;
    }

    function validate(lat, long, time) {
        const currentPhoto = gameStore.getCurrentPhoto;
        const guess = { lat, long, time, distance: 0, score: 0, photoLat: currentPhoto.lat, photoLong: currentPhoto.long };
        
        if (lat !== null && long !== null) {
            guess.distance = calculateDistance(lat, long, currentPhoto.lat, currentPhoto.long);
            guess.score = calculateScore(guess.distance, time);
            gameStore.updateScore(guess.score);
        }

        gameStore.storeNewGuess(guess);
        const i = gameStore.nextPhoto();
        if (i === -1) {
            console.log('Game ended');
        }
    }

    async function startGame(idSerie){
        await gameStore.createGame(idSerie);
    }

    function resetGame() {
        gameStore.resetData();
    }

    function getCurrentPhoto(){
        return gameStore.getCurrentPhoto;
    }

    function getLastGuess(){
        return gameStore.getLastGuess;
    }

    function getScore(){
        return gameStore.getScore;
    }

    function isEnded(){
        return gameStore.isEnded;
    }

    /* Autres fonctions en rapport avec le jeu */

    const api = inject('api');

    async function getPublicGames(){
        return [
            { id: 1, name: 'Game 1' },
            { id: 2, name: 'Game 2' },
            { id: 3, name: 'Game 3' },
            { id: 4, name: 'Game 4' },
        ];
    }

    async function getSeries(){
        return [];
    }

    return {startGame, validate, resetGame, getCurrentPhoto, getLastGuess, getScore, gameConfig, isEnded, getPublicGames};
}