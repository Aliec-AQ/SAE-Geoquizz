<script setup>
import { ref, watch } from 'vue';

const props = defineProps({
    password: String,
    placeholder: {
        type: String,
        default: 'Mot de passe'
    }
});
const emit = defineEmits(['update:password']);
const password = ref(props.password);
const showPassword = ref(false);

watch(() => props.password, (newValue) => {
    password.value = newValue;
});

watch(() => password.value, (newValue) => {
    emit('update:password', newValue);
});

</script>

<template>
    <div class="password-input">
        <input :type="showPassword ? 'text' : 'password'" name="mdp" v-model="password" :placeholder="placeholder">
        <button type="button" @click="showPassword = !showPassword" :key="showPassword">
            <i :class="showPassword ? 'fa fa-eye-slash' : 'fa fa-eye'"></i>
        </button>
    </div>
</template>

<style scoped>
.password-input {
    width: 100%;
    position: relative;
}

input {
    padding: 1rem;
    border: 1px solid var(--primary-color);
    border-radius: 4px;
    background-color: var(--dark-color);
    color: var(--text-color);
    font-size: 1.2rem;
    font-family: 'Lexend';
    width: 100%;
    box-sizing: border-box;
}

input::placeholder {
    color: var(--text-color);
}

input:focus {
    outline: none;
    border-color: var(--accent-color);
}

input:is(:-webkit-autofill, :autofill) {
    background-color: var(--dark-color);
}

button {
    position: absolute;
    right: 0;
    top: 0;
    background-color: transparent;
    color: var(--primary-color);
    border: none;
    padding: 1rem;
    cursor: pointer;
    font-size: 1rem;
    display: flex;
    align-items: center;
    justify-content: center;
    height: 100%;
}
</style>