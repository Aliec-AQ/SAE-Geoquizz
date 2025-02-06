import { defineStore } from 'pinia'

export const useUserStore = defineStore('user', {
    state() {
        return {
            accessToken: '',
            refreshToken: '',
        }
    },
    actions: {
        async signUp(email, password) {
            try {
                let res = await this.$api.post('register', {
                    "email": email,
                    "mdp": password
                });
                this.accessToken = res.data.atoken;
                this.refreshToken = res.data.rtoken;
                return true;
            } catch (e) {
                return false;
            }
        },
        async signIn(email, password) {
            try {
                let credentials = btoa(`${email}:${password}`);
                let res = await this.$api.post('signin', {}, {
                    headers: {
                        'Authorization': `Basic ${credentials}`
                    }
                });
                this.accessToken = res.data.atoken;
                this.refreshToken = res.data.rtoken;
                return true;
            } catch (e) {
                return false;
            }
        },
        logout() {
            this.accessToken = '';
            this.refreshToken = '';
            this.role = '';
        },
    },
    getters: {
        isSignedIn(state) {
            return state.accessToken !== '';
        },
        getAccessToken(state) {
            return state.accessToken;
        },
    },
    persist: {
        enabled: true,
        strategies: [
            { storage: localStorage, paths: ['accessToken', 'refreshToken'] }
        ]
    }
})