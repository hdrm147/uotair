<template>
    <v-container class="screen-height">
        <v-row justify="center" align="center" class="screen-height">
            <v-col cols="4">
                <v-card>
                    <v-toolbar
                        color="primary"
                        dark
                        flat
                    >
                        <v-toolbar-title>Login</v-toolbar-title>
                    </v-toolbar>
                    <v-card-text>
                        <v-form>
                            <v-text-field
                                outlined
                                v-model="email"
                                label="Login"
                                name="login"
                                ref="email"
                                :error-messages="errors.email"

                                prepend-icon="mdi-account"
                                type="text"
                            ></v-text-field>

                            <v-text-field
                                outlined
                                id="password"
                                v-model="password"
                                label="Password"
                                name="password"
                                :error-messages="errors.password"
                                ref="password"
                                prepend-icon="mdi-lock"
                                type="password"
                            ></v-text-field>
                        </v-form>
                    </v-card-text>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn @click="login" color="primary" text>Login</v-btn>
                    </v-card-actions>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
    export default {
        name: "Login",
        data() {
            return {
                email: "",
                password: "",
                errors: {
                    email: [],
                    password: []
                }
            }
        },
        mounted() {
            this.$nextTick(() => {
                this.$root.drawer = false;
                this.$root.bar = false;
            })
        },
        methods: {
            login() {
                axios.post('/api/admin/login', {email: this.email, password: this.password}).then(response => {
                    localStorage.setItem('uot-airlines.api-token', response.data.token);
                    localStorage.setItem('uot-airlines.user', JSON.stringify(response.data.user));
                    this.$root.user = response.data.user;
                    this.$router.push({
                        path: '/admin/flights'
                    });
                    this.$root.drawer = true;
                    this.$root.bar = true;

                }).catch(error => {
                    for (let field in error.response.data.errors) {
                        this.errors[field] = error.response.data.errors[field]
                    }
                })
            }
        }
    }
</script>

<style scoped>

</style>
