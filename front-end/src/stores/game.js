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
        async createGame(id) {
            this.finishGame();

            this.defaultCoordinates = { lat: 48.6921, long: 6.1844 };
            this.GameJWT = "JWT";
            this.photos = [
                {
                    id: 1,
                    url: 'https://www.francebleu.fr/s3/cruiser-production/2021/09/ef5a918d-d43a-4025-8460-a3000ac2cfc2/1200x680_place_stan_une_artucle_factuel_escudero_patrick_024_3766077.jpg',
                    lat: 48.6921,
                    long: 6.1844,
                },
                {
                    id: 2,
                    url: 'https://i-de.unimedias.fr/2023/12/07/dt179nancyplacestanislasbr-6571e8fca31b1.jpg?auto=format%2Ccompress&crop=faces&cs=tinysrgb&fit=max&w=1050',
                    lat: 48.6921,
                    long: 6.1844,
                },
                {
                    id: 3,
                    url: 'https://cdn-s-www.leprogres.fr/images/9293DCF2-288D-40E5-9EAC-A1E567E8F7DC/NW_raw/nancy-capitale-des-ducs-de-lorraine-affiche-un-impressionnant-patrimoine-historique-photo-helix-solutions-1616173288.jpg',
                    lat: 48.6921,
                    long: 6.1844,
                },
            ];
            this.currentPhoto = 0;
            this.distance = 0.3;
            this.time = 20;
        },

        calculateScore(distance, time) {
            let points = 0;

            if (distance < this.distance) {
                points = 5;
            } else if (distance < this.distance * 2) {
                points = 3;
            } else if (distance < this.distance * 4) {
                points = 1;
            }

            if (time < 5) {
                points *= 4;
            } else if (time < 10) {
                points *= 2;
            } else if (time > 20) {
                points = 0;
            }

            return points;
        },

        addGuess(lat, long, time) {
            const currentPhoto = this.photos[this.currentPhoto];
            if(lat === null || long === null) {
                this.guesses.push({ lat, long, time, distance: 0, score: 0, photoLat: currentPhoto.lat, photoLong: currentPhoto.long });
            }else{
                const distance = this.calculateDistance(currentPhoto.lat, currentPhoto.long, lat, long);
                const score = this.calculateScore(distance, time);
                this.score += score;
                this.guesses.push({ lat, long, time, distance, score, photoLat: currentPhoto.lat, photoLong: currentPhoto.long });
            }
            
            if(this.currentPhoto < this.photos.length - 1) {
                this.currentPhoto++;
            }else {
                this.currentPhoto = -1;
            }
        },

        calculateDistance(lat1Deg, lon1Deg, lat2Deg, lon2Deg) {
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
        },

        saveGame() {
            console.log('Game saved');
            console.log("envoi de la partie au serveur");
        },

        finishGame() {
            this.GameJWT = '';
            this.photos = [];
            this.currentPhoto = 0;
            this.guesses = [];
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
        maxTime(state) {
            return state.time;
        },
        getGuesses(state) {
            return state.guesses;
        }
    },
    persist: {
        enabled: true,
        strategies: [
            { storage: localStorage, paths: ['GameJWT', 'photos', 'currentPhoto', 'defaultCoordinates', 'guesses', 'score', 'time'] }
        ]
    }
})