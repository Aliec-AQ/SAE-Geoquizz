import { defineStore } from 'pinia'

export const useUserStore = defineStore('user', {
    state() {
        return {
            accessToken: '',
            refreshToken: '',
            role: '',
            signedIn: false
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
                this.role = res.data.role;
                this.signedIn = true;
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
                this.role = res.data.role;
                this.signedIn = true;
                return true;
            } catch (e) {
                this.signedIn = false;
                return false;
            }
        },

        logout() {
            this.signedIn = false;
            this.accessToken = '';
            this.refreshToken = '';
            this.role = '';
        },
    },
    getters: {
        isSignedIn(state) {
            return state.signedIn;
        },
    },
    persist: {
        enabled: true,
        strategies: [
            { storage: localStorage, paths: ['signedIn', 'accessToken', 'refreshToken', 'role'] }
        ]
    }
})