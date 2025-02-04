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
                    url: this.$imageApiUrl + 'f25cc352-60d3-4457-9ffb-5cc806537050',
                    lat: 48.670187,
                    long: 6.1463581,
                },
                {
                    id: 2,
                    url: this.$imageApiUrl + 'ebc75392-2911-4caf-8712-c270c7534d78',
                    lat: 48.6650565,
                    long: 6.1615529,
                },
                {
                    id: 3,
                    url: this.$imageApiUrl + 'bb7588e4-f0d8-4a2f-a487-3d4167447cff',
                    lat: 48.6892936,
                    long: 6.1821537,
                },
                {
                    id: 4,
                    url: this.$imageApiUrl + 'fae48f1d-72a8-497f-9438-9e6c718a552f',
                    lat: 48.6979622,
                    long: 6.1741624,
                },
                {
                    id: 5,
                    url: this.$imageApiUrl + '7387f3ba-fc88-4268-89a9-887bbde6f034',
                    lat: 48.680431,
                    long: 6.166484,
                },
                {
                    id: 6,
                    url: this.$imageApiUrl + 'a78b70af-ab87-4376-9385-3302f8361263',
                    lat: 48.6939622,
                    long: 6.1981006,
                },
                {
                    id: 7,
                    url: this.$imageApiUrl + '9dd25d3f-6afa-4624-b06e-6785949e19af',
                    lat: 48.6883448,
                    long: 6.1662374,
                },
                {
                    id: 8,
                    url: this.$imageApiUrl + '8d2700e9-58cd-445e-add4-ee59f6d80a42',
                    lat: 48.6950418,
                    long: 6.188216,
                },
                {
                    id: 9,
                    url: this.$imageApiUrl + '4b69f905-1433-4878-9119-b12df7c05d59',
                    lat: 48.6943483,
                    long: 6.1767979,
                },
                {
                    id: 10,
                    url: this.$imageApiUrl + '8f07bc2f-b061-42a1-94c3-c16d236e453d',
                    lat: 48.6926711,
                    long: 6.183916,
                },
            ];
            this.currentPhoto = 0;
            this.distance = 0.3;
            this.time = 6000;
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