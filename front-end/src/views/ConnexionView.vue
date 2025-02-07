<script setup>
    import { ref } from 'vue';
    import PasswordInput from '@/components/forms/PasswordInput.vue';
    import { useToast } from 'vue-toastification';
    import { useRouter } from 'vue-router';
    import { useUser } from '@/services/user';

    const {signIn, signUp} = useUser();
    const router = useRouter();
    const toast = useToast();

    const page = ref(1);
    const email = ref('');
    const password = ref('');
    const password2 = ref('');

    const submitSignIn = async () => {
        if (!email.value || !password.value) {
            toast.warning('Veuillez remplir tous les champs');
            return;
        }

        let achieved = await signIn(email.value, password.value);
        if (!achieved) {
            return;
        }
        toast.success('Connexion réussie');
        router.push('/');
    };

    const submitSignUp = async () => {
        if (!email.value || !password.value || !password2.value) {
            toast.warning('Veuillez remplir tous les champs');
            return;
        }

        if (password.value !== password2.value) {
            toast.warning('Les mots de passe ne correspondent pas');
            return;
        }

        let achieved = await signUp(email.value, password.value);

        if (!achieved) {
            return;
        }
        toast.success('Compte créé avec succès');
        router.push('/');
    };
    
</script>

<template>
    <main class="main-container">
        <div class="form-container">
            <div v-if="page == 1" class="connexion">
                <h1>Se connecter</h1>
                <form @submit.prevent="submitSignIn">
                    <input type="text" id="email" name="email" v-model="email" placeholder="Email">
                    <PasswordInput v-model:password="password" />
                    <button type="submit">Se connecter</button>
                </form>
            </div>
            <div v-if="page == 2" class="creation">
                <h1>Créer un compte</h1>
                <form @submit.prevent="submitSignUp">
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
            <img src="/favicon.ico" alt="logo" width="200" height="200">
        </div>
    </main>
</template>

<style scoped>
.main-container {
    display: flex;
    flex: 1;
    background-color: var(--background-color);
    color: var(--text-color);
}

@media (max-width: 768px) {
    .main-container {
        flex-direction: column;
    }
}

.form-container {
    flex: 1;
    display: flex;
    flex-direction: column;
    justify-content: center;
    padding: 2rem;
    background-color: var(--secondary-color);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.title-container {
    flex: 2;
    display: flex;
    align-items: center;
    justify-content: center;
}

h1 {
    margin-bottom: 1rem;
    color: var(--text-color);
}

form {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

input {
    padding: 1rem;
    border: 1px solid var(--primary-color);
    border-radius: 4px;
    background-color: var(--dark-color);
    color: var(--text-color);
    font-size: 1.2rem;
    font-family: 'Lexend';
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
    border: none;
    padding: 0.75rem;
    border-radius: 4px;
    cursor: pointer;
    font-size: 1.5rem;
    font-family: 'Lexend';
    background-color: var(--primary-color);
    color: var(--text-color);
}

button:hover {
    background-color: var(--accent-color);
}

.choix {
    display: flex;
    justify-content: space-between;
    margin-top: 1rem;
}

.choix button {
    background-color: transparent;
    padding: 0.5rem;
    border-radius: 4px;
    cursor: pointer;
    font-size: 1.2rem;
    text-decoration: underline;
    font-family: 'Lexend';
    color: var(--text-color);
}

.choix button:hover {
    color: var(--accent-color);
}
</style>