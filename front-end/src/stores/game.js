import { defineStore } from 'pinia'
import { useToast } from 'vue-toastification';

const toast = useToast();

export const useGameStore = defineStore('game', {
    state() {
        return {
            GameJWT: '',
            sequenceId: '',
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
                let res = await this.$api.post('/game?idserie=' + id, {}, {
                    headers: {
                        "Authorization": `Bearer ${token}`
                    }
                });
                res = res.data;
                
                await this.setData(res);
            } catch (e) {
                console.log(e);
                toast.error('Failed to create game.');
            }
        },

        async replayGame(id, token) {
            this.resetData();
            let headers = {};

            if (token) {
                headers = {
                    "Authorization": `Bearer ${token}`
                };
            }
        
            try {
                let res = await this.$api.post('/sequences/replay?idSequence='+id, {}, {
                    headers: headers
                });
                console.log(res);
                await this.setData(res.data);
            } catch (e) {
                console.log(e);
                toast.error('Failed to replay game.');
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
            try{
                this.$api.put('/game?score=' + this.score, {}, {
                    headers: {
                        "Authorization": `Bearer ${this.GameJWT}`
                    }
                });
            }catch(e){
                console.log(e);
                toast.error('Failed to save game.');
            }
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
            this.sequenceId = '';
        },

        async setData(data) {
            const res = data;

            this.GameJWT = res.token;
            this.photos = res.game.sequence.photo.map(photo => {
                return {
                    lat: photo.lat,
                    long: photo.long,
                    nom: photo.nom,
                    url: this.$imageApiUrl + photo.image
                }
            });
            this.score = res.game.score;
            this.sequenceId = res.game.sequence.id;
        
            let seriesInfo = await this.$api.get(`/items/themes?filter[id][_eq]=${res.game.serie_id}`);
            seriesInfo = seriesInfo.data.data[0];

            this.time = parseInt(seriesInfo.temps);
            this.distance = parseFloat(seriesInfo.distance);
            this.defaultCoordinates = {
                lat: parseFloat(seriesInfo.lat),
                long: parseFloat(seriesInfo.long)
            };
        },

        async getCurrentPhoto() {
            if(this.photos.length <= 0) {
                try {
                    let res = await this.$api.get('/game', {
                        headers: {
                            "Authorization": `Bearer ${this.GameJWT}`
                        }
                    });
                    this.photos = res.data.game.sequence.photo.map(photo => {
                        return {
                            lat: photo.lat,
                            long: photo.long,
                            nom: photo.nom,
                            url: this.$imageApiUrl + photo.image
                        }
                    });
                }catch(e) {
                    console.log(e);
                    this.resetData();
                    toast.error('Failed to load photos.');
                }
            }

            return this.photos[this.currentPhoto];
        }
    },
    getters: {
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
        },
        zoomLevel(state) {
            if (state.distance <= 0.25) {
                return 13;
            } else if (state.distance >= 50) {
                return 2;
            } else {
                const zoomLevel = 18 - (state.distance - 0.25) * (17 / (50 - 0.25));
                return Math.min(zoomLevel, 13); 
            }
        }
    },
    persist: {
        enabled: true,
        strategies: [
            { storage: localStorage, paths: ['GameJWT', 'currentPhoto', 'defaultCoordinates', 'guesses', 'score', 'time', 'distance', 'sequenceId'] }
        ]
    }
})