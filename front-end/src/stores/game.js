import { defineStore } from 'pinia'

export const useGameStore = defineStore('game', {
    state() {
        return {
            GameJWT: '',
            photos: [],
            currentPhoto: 0,
            defaultCoordinates: {},
            guesses: [],
            distance: 0, // en km
            score: 0,
            time: 0, // en secondes
        }
    },
    actions: {
        async createGame(id, token) {
            this.resetData();
        
            try {
                let res = await this.$api.post('/games?idserie=' + id, {}, {
                    headers: {
                        "Authorization": `Bearer ${token}`
                    }
                });
                res = res.data;
        
                this.GameJWT = res.token;
                this.photos = res.game.sequence.photo;
                this.defaultCoordinates = {
                    lat: res.game.sequence.photo[0].lat,
                    long: res.game.sequence.photo[0].long
                };
                this.score = res.game.score;
            
                let seriesInfo = await this.$api.get(`/items/themes?filter[id][_eq]=${id}`);
                console.log(seriesInfo);
                
            } catch (e) {
                console.log(e);
            }
        },

        storeNewGuess(data) {
            this.guesses.push(data);
        },

        updateScore(addingScore) {
            this.score += addingScore;
        },

        nextPhoto() {
            if (this.currentPhoto < this.photos.length - 1) {
                this.currentPhoto++;
            } else {
                this.currentPhoto = -1;
            }
            return this.currentPhoto;
        },

        saveGame() {
            console.log('Game saved');
            console.log("envoi de la partie au serveur");
        },

        resetData() {
            this.GameJWT = '';
            this.photos = [];
            this.currentPhoto = 0;
            this.defaultCoordinates = {};
            this.guesses = [];
            this.distance = 0;
            this.score = 0;
            this.time = 0;
        }
    },
    getters: {
        getCurrentPhoto(state) {
            return state.photos[state.currentPhoto];
        },
        getScore(state) {
            return state.score;
        },
        getPhotos(state) {
            return state.photos;
        },
        getLastGuess(state) {
            return state.guesses[state.guesses.length - 1];
        },
        isEnded(state) {
            return state.currentPhoto === -1;
        },
        isInit(state) {
            return state.GameJWT != '';
        },
        getGuesses(state) {
            return state.guesses;
        },
        getGameConfig(state) {
            return { defaultCoordinates: state.defaultCoordinates, time: state.time, distance: state.distance };
        }
    },
    persist: {
        enabled: true,
        strategies: [
            { storage: localStorage, paths: ['GameJWT', 'photos', 'currentPhoto', 'defaultCoordinates', 'guesses', 'score', 'time'] }
        ]
    }
})