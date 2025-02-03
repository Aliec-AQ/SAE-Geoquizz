<script setup>
    import { ref } from 'vue';
    import PasswordInput from '@/components/forms/PasswordInput.vue';
    import { useToast } from 'vue-toastification';
    import { useUserStore } from '@/stores/user'
    import { useRouter } from 'vue-router';

    const userStore = useUserStore();
    const router = useRouter();

    const page = ref(1);
    const email = ref('');
    const password = ref('');
    const password2 = ref('');

    const signIn = () => {
        if (!email.value || !password.value) {
            const toast = useToast();
            toast.warning('Veuillez remplir tous les champs');
            return;
        }

        userStore.signIn(email.value, password.value);
        router.push('/');
    };

    const signUp = () => {
        if (!email.value || !password.value || !password2.value) {
            const toast = useToast();
            toast.warning('Veuillez remplir tous les champs');
            return;
        }

        if (password.value !== password2.value) {
            const toast = useToast();
            toast.warning('Les mots de passe ne correspondent pas');
            return;
        }

        userStore.signUp(email.value, password.value);
        router.push('/');
    };
    
</script>

<template>
    <main class="main-container">
        <div class="form-container">
            <div v-if="page == 1" class="connexion">
                <h1>Se connecter</h1>
                <form @submit.prevent="signIn">
                    <input type="text" id="email" name="email" v-model="email" placeholder="Email">
                    <PasswordInput v-model:password="password" />
                    <button type="submit">Se connecter</button>
                </form>
            </div>
            <div v-if="page == 2" class="creation">
                <h1>Créer un compte</h1>
                <form @submit.prevent="signUp">
                    <input type="email" id="email" name="email" v-model="email" placeholder="Email">
                    <PasswordInput v-model:password="password" />
                    <PasswordInput v-model:password="password2" placeholder="Confirmer le mot de passe" />
                    <button type="submit">Créer compte</button>
                </form>
            </div>

            <div class="choix">
                <button @click="page = 1" v-if="page==2">Se connecter</button>
                <button @click="page = 2" v-if="page==1">Créer un compte</button>
            </div>
        </div>
        <div class="title-container">
            <h1 class="app-title">
                truc ici
            </h1>
        </div>
    </main>
</template>

<style scoped>
.main-container {
    display: flex;
    flex: 1;
    background-color: var(--primary-color-dark);
    color: var(--secondary-color-light);
}

.form-container {
    flex: 1;
    display: flex;
    flex-direction: column;
    justify-content: center;
    padding: 2rem;
    background-color: var(--primary-color-dark);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.title-container {
    flex: 2;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: var(--primary-color-medium);
}

.app-title {
    display: flex;
    align-items: center;
    gap: 4rem;
    font-size: 8rem;
    color: var(--primary-color-light);
}

h1 {
    color: var(--primary-color-light);
    margin-bottom: 1rem;
}

form {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

input {
    padding: 1rem;
    border: 1px solid var(--primary-color-medium);
    border-radius: 4px;
    background-color: transparent;
    color: var(--secondary-color-light);
    font-size: 1.2rem;
    font-family: 'Geo';
}

input::placeholder {
    color: var(--secondary-color-light);
}

input:focus {
    outline: none;
    border-color: var(--primary-color-light);
}

input:is(:-webkit-autofill, :autofill) {
    background-color: transparent;
}

button {
    background-color: var(--primary-color-medium);
    color: var(--secondary-color-light);
    border: none;
    padding: 0.75rem;
    border-radius: 4px;
    cursor: pointer;
    font-size: 1.5rem;
    font-family: 'Geo';
}

button:hover {
    background-color: var(--primary-color-light);
    color: var(--secondary-color-dark);
}

.choix {
    display: flex;
    justify-content: space-between;
    margin-top: 1rem;
}

.choix button {
    background-color: transparent;
    color: var(--primary-color-light);
    padding: 0.5rem;
    border-radius: 4px;
    cursor: pointer;
    font-size: 1rem;
    text-decoration: underline;
    font-family: 'Geo';
}

.choix button:hover {
    color: var(--primary-color-light);
}
</style>
