import { defineStore } from 'pinia'

export const useUserStore = defineStore('user', {
    state() {
        return {
            signedIn: false
        }
    },
    actions:{
        async signUp(email, password){
            try{
                this.signedIn = true;
                return true;
            }catch(e){
                return false;
            }
        },
        
        async signIn(email, password){
            try{
                this.signedIn = true;
                return true;
            }catch(e){
                this.signedIn = false;
                return false;
            }
        },

        logout(){
            this.signedIn = false;
        },
    },
    getters: {
        isSignedIn(state){
            return state.signedIn;
        },
    },
    persist: {
        enabled: true,
        strategies: [
            {storage: localStorage, paths: ['signedIn']}
        ]
    }
})