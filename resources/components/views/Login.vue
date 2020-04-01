<template>
    <form class="auth" @submit.prevent="submit">
        <h2>Login</h2>
        <input type="email" v-model="email" placeholder="email" :readonly="registeredEmail" autocomplete="username" />
        <input type="password" v-model="password" placeholder="password" autocomplete="current-password" ref="password" />
        <p class="error" v-if="message" v-text="message"></p>
        <button type="submit" v-text="waiting ? 'please wait' : 'submit'"></button>
    </form>
</template>

<script>
export default {
    props: {
        registeredEmail: {
            type: String,
            default: null,
            required: false
        }
    },
    data: () => ({
        waiting: false,
        email: null,
        password: null,
        message: null
    }),
    mounted() {
        if(this.registeredEmail) {
            this.email = this.registeredEmail;
            this.$refs.password.focus();
        }
    },
    methods: {
        submit() {
            this.waiting = true;
            this.message = null;

            const payload = {
                'email': this.email,
                'password': this.password
            };

            fetch('/auth/login', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(payload)
            })
            .then(response => response.json())
            .then(data => {
                if(data.access_token) {
                    this.$store.dispatch('fetchUser');
                    this.$router.push('/');
                }

                this.waiting = false;
                this.message = data.message;
            });
        }
    }
}
</script>
