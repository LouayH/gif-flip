<template>
    <form class="auth" @submit.prevent="submit">
        <h2>Create a new user</h2>
        <input type="text" v-model="username" placeholder="username" />
        <span class="error" v-if="errors.username" v-text="errors.username"></span>
        <input type="email" v-model="email" placeholder="email" autocomplete="username" />
        <span class="error" v-if="errors.email" v-text="errors.email"></span>
        <input type="password" v-model="password" placeholder="password" autocomplete="new-password" />
        <span class="error" v-if="errors.password" v-text="errors.password"></span>
        <input type="password" v-model="passwordConfirmation" placeholder="confirm password" autocomplete="new-password" />
        <p class="error" v-if="errors.message" v-text="errors.message"></p>
        <button type="submit" v-text="waiting ? 'please wait' : 'submit'" :disabled="waiting"></button>
    </form>
</template>

<script>
import Vue from "vue";
import { VueReCaptcha } from "vue-recaptcha-v3";
Vue.use(VueReCaptcha, { siteKey: process.env.MIX_RECPATCHA_SITE_KEY });

export default {
    data: () => ({
        waiting: false,
        username: null,
        email: null,
        password: null,
        passwordConfirmation: null,
        errors: {
            username: null,
            email: null,
            password: null,
            message: null
        }
    }),
    methods: {
        resetErrors() {
            const errors = Object.keys(this.errors);
            errors.forEach(error => {
                this.errors[error] = null;
            })
        },
        submit() {
            this.waiting = true;
            this.resetErrors();

            // get recaptcha token for register action
            this.$recaptcha("register")
            .then(token => {
                const payload = {
                    'username': this.username,
                    'email': this.email,
                    'password': this.password,
                    'password_confirmation': this.passwordConfirmation,
                    'recaptcha_token': token
                };

                // send request to register a new user
                fetch('/auth/register', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(payload)
                })
                .then(response => {
                    // redirect to login view if register completed successfully
                    if(response.ok) {
                        this.$router.push({
                            name: 'login',
                            params: {
                                registeredEmail: this.email
                            }
                        });
                    }

                    return response.json();
                })
                .then(data => {
                    this.waiting = false

                    // display errors based on their properties
                    const errors = Object.keys(data);
                    errors.forEach(error => {
                        this.errors[error] = data[error] ? data[error][0] : null;
                    })
                });
            })
            .catch(() => {
                this.waiting = false;
                this.errors.message = 'Recaptcha Failed, Please try again';
            });
        }
    }
}
</script>
