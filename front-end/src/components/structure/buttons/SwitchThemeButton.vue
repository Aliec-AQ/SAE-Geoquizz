<script setup>
import { ref, onMounted, computed } from 'vue';

const isDark = ref(true);

const toggleDark = () => {
    isDark.value = !isDark.value;
    document.body.classList.toggle('light-theme', !isDark.value);
};

const title = computed(() => isDark.value ? 'Passer en mode clair' : 'Passer en mode sombre');

onMounted(() => {
    const prefersDarkScheme = window.matchMedia('(prefers-color-scheme: dark)');
    isDark.value = prefersDarkScheme.matches;
    document.body.classList.toggle('light-theme', !isDark.value);
});
</script>

<template>
    <button 
        class="toggle-button" 
        :class="{ 'dark-mode': isDark }"
        :title="title"
        @click="toggleDark"
    >
        <transition name="icon-rotate">
            <i :class="isDark ? 'fas fa-moon' : 'fas fa-sun'" :key="isDark"></i>
        </transition>
    </button>
</template>

<style scoped>
.icon-rotate-enter-active {
    transition: transform 0.3s;
}

.icon-rotate-leave-active {
    display: none;
}

.icon-rotate-enter-from, .icon-rotate-leave-to {
    transform: rotate(180deg);
}
</style>