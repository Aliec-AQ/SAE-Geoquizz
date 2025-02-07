import { defineStore } from 'pinia'
import { useToast } from 'vue-toastification';

const toast = useToast();

export const useThemeStore = defineStore('theme', {
    state() {
        return {
            series: [],
        }
    },
    actions: {
        async loadSeries() {
            try { 
                let res = await this.$api.get('/items/themes');
                this.series = res.data.data;  
            }catch (e) {
                toast.error('Impossible de charger les thèmes');
            }
        },
    },
    getters: {
        isInit() {
            return this.series.length > 0;
        },
        getSeries() {
            return this.series;
        }
    }
})