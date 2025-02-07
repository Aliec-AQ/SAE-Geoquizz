<script setup>
import { ref, onMounted } from 'vue';
import { useUser } from '@/services/user';
import  { useGame } from '@/services/game';
import { useRouter } from 'vue-router';

const { getGames, getHighScores } = useUser();
const { makeGamePublic, replayGame } = useGame();
const router = useRouter();
const games = ref([]);
const highScores = ref([]);

const gamePublic = async (idSequence) => {
    await makeGamePublic(idSequence);
    games.value = await getGames();
};

const replayPublicGame = async (gameId) => {
    await replayGame(gameId);
    router.push({ name: 'game-play' });
};

onMounted(async () => {
    games.value = await getGames();
    highScores.value = await getHighScores();
});
</script>

<template>
  <div class="profile-view">
      <div class="columns">
          <div class="column">
              <h1>Parties du Joueur</h1>
              <ul v-if="games.length">
                  <li v-for="game in games" :key="game.id" class="game-card">
                      <div class="game-info">
                          <div>{{ game.serieName }} ({{ game.id }})</div>
                          <div>Score: {{ game.score }}</div>
                      </div>
                        <div class="game-actions">
                          <button v-if="!game.public" @click="gamePublic(game.idSequence)" title="Rendre publique" class="game-button">
                            <i class="fa fa-globe"></i>
                          </button>
                          <button @click="replayPublicGame(game.idSequence)" title="Rejouer la séquence" class="game-button">
                            <i class="fa fa-redo"></i>
                          </button>
                        </div>
                  </li>
              </ul>
              <p v-else>Aucunes parties trouvées</p>
          </div>
          <div class="column">
              <h1>High Scores</h1>
              <div class="high-scores-grid" v-if="highScores.length">
                  <div v-for="score in highScores" :key="score.seriesId" class="high-score-item">
                      <div class="high-score-name">{{ score.name }}</div>
                      <div class="high-score-value">{{ score.highscore }}</div>
                  </div>
              </div>
              <p v-else>Loading...</p>
          </div>
      </div>
  </div>
</template>

<style scoped>
.profile-view {
  background-color: var(--background-color);
  color: var(--text-color);
  padding: 20px;
  border-radius: 8px;
}

.game-button{
  background-color: var(--primary-color);
  color: var(--text-color);
  font-size: 1.2em;
  border: none;
  padding: 5px;
  border-radius: 4px;
  cursor: pointer;
}

.columns {
  display: flex;
  gap: 20px;
}

.column {
  flex: 1;
}

h1 {
  color: var(--primary-color);
  margin-bottom: 20px;
}

ul {
  list-style-type: none;
  padding: 0;
}

li {
  background-color: var(--secondary-color);
  margin-bottom: 10px;
  padding: 10px;
  border-radius: 4px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  transition: background-color 0.3s;
}

li:hover {
  background-color: var(--dark-color);
}

.game-info {
  flex: 1;
}

.game-actions {
  display: flex;
  gap: 10px;
}

.high-scores-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
  gap: 10px;
}

.high-score-item {
  background-color: var(--secondary-color);
  padding: 10px;
  border-radius: 4px;
  text-align: center;
}

.high-score-name {
  font-weight: bold;
  margin-bottom: 5px;
}

.high-score-value {
  font-size: 1.2em;
}

/* Media Queries */
@media (max-width: 768px) {
  .columns {
    flex-direction: column-reverse;
  }

  .high-scores-grid {
    grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
  }
}

@media (max-width: 480px) {
  .high-scores-grid {
    grid-template-columns: 1fr;
  }

  .high-score-item {
    padding: 5px;
  }

  .high-score-value {
    font-size: 1em;
  }
}
</style>