import { useThemeStore } from "@/stores/theme";
import { useUserStore } from "@/stores/user";
import { inject } from "vue";

export function useUser(){
    const userStore = useUserStore();
    const themeStore = useThemeStore();
    const api = inject('api');

    async function signUp(email, password) {
        return await userStore.signUp(email, password);
    }
    
    async function signIn(email, password) {
        return await userStore.signIn(email, password);
    }

    async function getGames() {
        try {
        let res = await api.get('/users/games', {
            headers: {
                "Authorization": `Bearer ${userStore.getAccessToken}`
            }
        });
        const games = res.data.games.map(game => {
            const serie = themeStore.getSeries.find(serie => serie.id === game.serie_id);
            return {
                id: game.id,
                score: game.score,
                serieName: serie ? serie.nom : 'Inconnue',
                idSequence: game.sequence.id,
                public: game.sequence.public,
            };
        });
            return games;
        } catch (e) {
            return [];
        }
    }

    async function makeGamePublic(idSequence) {
        try {
        await api.put(`sequences/${idSequence}/status`, {}, {
            headers: {
                "Authorization": `Bearer ${userStore.getAccessToken}`
            }
        });
            return true;
        } catch (e) {
            return false;
        }
    }

    async function getHighScores() {
        try {
            const series = themeStore.getSeries;
            const highScores = await Promise.all(series.map(async (serie) => {
                let res = await api.get(`series/${serie.id}/highscore`, {
                    headers: {
                        "Authorization": `Bearer ${userStore.getAccessToken}`
                    }
                });
                return { seriesId: serie.id, highscore: res.data.highscore, name: serie.nom };
            }));
            return highScores;
        } catch (e) {
            return [];
        }
    }
    
    return { signUp, signIn, getGames, makeGamePublic, getHighScores };
}