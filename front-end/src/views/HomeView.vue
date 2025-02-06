<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import { useRouter } from 'vue-router';

const images = [
  { src: '/img/image1.jpg', alt: 'Image 1' },
  { src: '/img/image2.jpg', alt: 'Image 2' },
  { src: '/img/image3.jpg', alt: 'Image 3' },
  { src: '/img/image4.jpg', alt: 'Image 4' },
  { src: '/img/image5.jpg', alt: 'Image 5' },
  { src: '/img/image6.jpg', alt: 'Image 6' },
];

const router = useRouter();
const currentImageIndex = ref(0);
let timer;

const switchImage = () => {
  currentImageIndex.value = (currentImageIndex.value + 1) % images.length;
};

onMounted(() => {
  timer = setInterval(switchImage, 10000);
});

onUnmounted(() => {
  clearInterval(timer);
});
</script>

<template>
  <main class="home">
    <section class="content">
      <h1> GeoQuizz </h1>
      <p> Bienvenue sur GeoQuizz, le jeu qui vous permet de voyager tout en vous amusant ! </p>
      <p> Vous devez deviner l'emplacement d'une photo sur une carte du monde. Plus vous êtes rapide et proche, plus vous gagnez de points. </p>
      <p> Bonne chance ! </p>
      <button @click="router.push({ name: 'game-create' })">
        Jouer
        <i class="fas fa-play"></i>
      </button>
      <p class="ascii">
        ⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⣀⡤⣤⣒⣒⡾⢭⡩⠉⢰⢖⣖⠤⢤⣀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀
⠀⠀⠀⠀⠀⠀⠀⢀⣠⣶⣿⢷⣫⠤⢲⠄⠀⠀⠧⡵⠀⡛⠉⢂⢄⣀⢻⣶⣄⡀⠀⠀⠀⠀⠀⠀⠀
⠀⠀⠀⠀⠀⢀⡴⣏⡽⢿⣿⣜⢲⡀⡼⠃⠀⡠⢻⣓⣄⢹⣼⢪⠇⢠⠉⠞⠋⠉⠢⡀⠀⠀⠀⠀⠀
⠀⠀⠀⢀⣴⠏⠀⢠⠴⢾⡽⣥⡟⡃⢙⡤⢤⡱⣈⠤⡍⣄⣞⠛⣒⣼⣲⠀⠀⠀⠀⠈⢢⡀⠀⠀⠀
⠀⠀⢠⡞⠁⠀⠀⠳⡤⡼⠀⠋⠱⣔⢄⡎⠭⠕⠁⠸⢹⠛⢯⣦⠊⠉⠁⠀⠀⠀⠀⠀⠀⠱⡄⠀⠀
⠀⢠⡟⠀⠀⠀⠀⠀⠛⠀⠀⢤⣶⡸⡼⠸⡀⠀⠀⠸⢸⢠⠈⠃⡠⠤⣲⣄⢀⣗⣷⡄⢷⡀⠘⡄⠀
⠀⣮⠃⠀⠀⠀⠀⠀⢀⡠⠞⠛⠁⠈⠑⡣⠃⠀⠀⢰⢈⠈⡢⠶⠕⠒⣜⡋⣻⣟⢦⠀⠘⠃⠀⢱⡀
⢸⢻⠀⠀⠀⠀⠀⢀⡼⣘⡕⠀⠀⠀⠈⠀⠀⠀⢀⢇⢎⡜⠁⠀⠀⠀⢀⠈⠈⠉⠉⣄⠀⠀⠀⡀⡇
⡟⡎⡄⠀⡖⠒⢲⢣⠌⡎⠀⠀⠀⠀⠀⠀⠀⠀⡜⡜⡎⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠸⣆⠀⢠⠟⣼
⡿⡔⢝⣄⢇⢶⠀⠽⢲⠁⠀⠀⠀⠀⠀⠀⠀⠀⢇⢇⢇⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠙⣶⡇⠀⣿
⣷⠈⠢⣈⠉⡪⣧⡂⠌⠒⠄⠀⠀⠀⠀⠀⠀⠀⠘⢎⠪⡓⠤⠠⠤⠲⡄⠀⠀⠀⠀⠀⠀⣰⢱⢡⢻
⢸⡄⠀⠀⠙⢎⡎⡎⠑⠒⠲⣄⡀⠀⢦⡀⠀⠀⠀⠀⠑⠢⠄⠀⡄⡇⢧⠀⠀⠀⠀⠀⠞⢡⠃⡠⡇
⠈⣷⠀⠀⠀⠘⣇⠇⠀⠀⠀⠀⠹⢂⣂⡀⠉⠲⡄⠀⠀⠀⠀⠀⠁⠠⢸⠀⠀⠀⠀⢸⢀⡹⡀⢱⠁
⠀⠘⣧⠀⠀⢰⢇⢆⠀⠀⠀⠀⠀⠀⠀⠈⡵⡀⡅⠀⠀⠀⠀⠀⠀⠀⡎⠀⠀⠀⠰⢳⢫⢣⢣⠇⠀
⠀⠀⠘⣧⠀⠀⠳⣗⢳⢤⠀⠀⠀⠀⠀⠸⣰⢱⠁⠀⠀⠀⠀⠀⠀⠀⢹⠀⢀⠜⠁⠓⡣⣣⠏⠀⠀
⠀⠀⠀⠈⢷⣄⠀⠈⢣⢏⡇⠀⠀⠀⡔⣊⢜⡎⠀⠀⠀⠀⠀⠀⢆⠰⣘⣺⠕⣀⠤⢀⡴⠁⠀⠀⠀
⠀⠀⠀⠀⠀⠙⢷⣄⠀⢫⠘⡄⢀⡞⡝⡰⠋⠀⠀⠀⠀⠀⠀⠀⠀⠁⠒⠒⠊⢁⡴⠋⠀⠀⠀⠀⠀
⠀⠀⠀⠀⠀⠀⠀⠉⠻⢦⣧⡘⢾⣜⠰⡅⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⢀⡠⠖⠉⠀⠀⠀⠀⠀⠀⠀
⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠈⠙⠳⠿⢤⣌⣀⣀⣀⣀⣀⣠⡤⠤⠖⠊⠁⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀
      </p>
    </section>
    <section class="gallery">
      <div class="image-container">
        <transition name="slide-fade" mode="out-in">
          <img :src="images[currentImageIndex].src" :alt="images[currentImageIndex].alt" :key="currentImageIndex" />
        </transition>
      </div>
    </section>
  </main>
</template>

<style scoped>
.home {
  flex: 1;
  display: flex;
  flex-direction: row;
  align-items: flex-start;
  background-color: var(--background-color);
  color: var(--text-color);
  border-radius: 8px;
  overflow: hidden;
}

.ascii{
  font-size: 1rem;
}

.content {
  flex: 1;
  padding: 20px;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
}

h1 {
  font-size: 4rem;
  font-weight: bold;
  color: var(--primary-color);
}

p {
  font-size: 1.3rem;
  font-weight: bold;
  text-align: center;
  color: var(--text-color);
}

.gallery {
  flex: 2;
  display: flex;
  justify-content: center;
  align-items: center;
  overflow: hidden;
  height: 100%;
  background-color: var(--secondary-color);
  clip-path: polygon(10% 0, 100% 0, 100% 100%, 0 100%);
}

.image-container {
  overflow: hidden;
  height: 100%; 
}

.image-container img {
  width: auto;
  height: 100%;
  object-fit: cover;
}

.slide-fade-enter-active, .slide-fade-leave-active {
  transition: all 0.3s ease-in-out;
  opacity: 1;
}

.slide-fade-enter-from {
  transform: translateY(100%);
  opacity: 0;
}

.slide-fade-leave-to {
  transform: translateY(-100%);
  opacity: 0;
}

button {
  background-color: transparent;
  color: var(--text-color);
  border: none;
  font-family: "Lexend", sans-serif;
  font-size: 2rem;
  padding: 10px 20px;
  cursor: pointer;
  transition: background-color 0.3s;

  text-align: center;
}

button:hover {
  background-color: var(--accent-color);
}


@media (max-width: 768px) {
  .home {
    flex-direction: column;
  }

  .content {
    gap: 20px;
  }
  
  .gallery{
    display: none;
  }

  p{
    font-size: 1rem;
    text-wrap: wrap;
  }
}

</style>