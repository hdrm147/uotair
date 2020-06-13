<template>
    <v-container>
        <v-row class="pa-12" justify="center">
            <v-col md="7">
                <v-row class="mb-4">
                    <v-col>
                        <v-skeleton-loader
                            type="image"
                            max-width="200px"
                            height="40px"
                            :loading="loading">
                            <h3 class="display-1 grey--text text--darken-3 ">
                                <template v-if="update">
                                    Update
                                </template>
                                <template v-else>
                                    Create
                                </template>
                                {{singularLabel}}
                            </h3>
                        </v-skeleton-loader>
                    </v-col>
                </v-row>
                <v-card :loading="loading">
                    <v-card-text>
                        <transition name="fade" mode="out-in">
                            <v-col v-if="loading" class="text-center pa-0" key="loading">
                                <v-skeleton-loader v-for="s in 4"
                                                   :loading="loading"
                                                   :key="`skeleton-${s}`"
                                                   ref="skeleton"
                                                   type="image"
                                                   height="41px"
                                                   class="my-3"
                                                   transition="fade-transition"
                                ></v-skeleton-loader>
                            </v-col>
                            <div v-else key="fields">
                                <v-form autocomplete="new">
                                    <template v-for="(field,index) in fields">

                                        <component :is="field.component" :field="field" :item="item"
                                                   :key="field.attribute"/>

                                    </template>
                                </v-form>

                            </div>
                        </transition>

                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn @click="submit" color="primary" text>{{update ? "Update" : "Create"}}</v-btn>

                        </v-card-actions>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
    import BelongsTo from "./Form/BelongsTo";
    import TextField from "./Form/TextField";
    import Boolean from "./Form/Boolean";
    import Avatar from "./Form/Avatar";
    import FareFormula from "./Form/FareFormula";
    import ColorPicker from "./Form/ColorPicker";
    import OnlineSeating from "./Form/OnlineSeating";

    export default {
        name: "Form",
        components: {
            BelongsTo, TextField, Boolean, Avatar, FareFormula, ColorPicker, OnlineSeating
        },
        data() {
            return {
                label: "",
                singularLabel: "",
                loading: true,
                item: null,
                fields: [],
                update: false,
                loadingSubmit: false,
                message: null,
            }
        },
        methods: {
            edit(item) {

            },
            submit() {
                let formData = new FormData;
                this.fields.forEach(field => {
                    field.errorMessages = [];
                    if (field.value != null)
                        formData.append(field.attribute, field.value);
                })

                if (this.update) {
                    formData.append('_method', 'PATCH');
                    axios.post(`/api/admin/${this.$route.params.resourceName}/${this.update}`, formData, {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    }).then(response => {
                        this.handleSuccess(response)
                    }).catch(error => {
                        this.handleErrors(error.response);
                    })
                } else {
                    axios.post(`/api/admin/${this.$route.params.resourceName}`, formData, {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    }).then(response => {
                        this.handleSuccess(response)
                    }).catch(error => {
                        this.handleErrors(error.response);
                    })
                }
            },
            handleSuccess(response) {
                this.$root.snackbar.message = response.data.message;
                this.$root.snackbar.color = "green";
                this.$root.snackbar.show = true

                this.$router.push({
                    path: `/admin/${this.$route.params.resourceName}/${response.data.item.id}`
                })

            },
            handleErrors(response) {
                this.$root.snackbar.message = response.data.message;
                this.$root.snackbar.color = "red";
                this.$root.snackbar.show = true

                for (let attribute in response.data.errors) {
                    let message = response.data.errors[attribute];
                    this.fields.find(field => field.attribute === attribute).errorMessages = message;
                }
            }
        },
        mounted() {
            let url;
            let id = this.$route.params.resourceId
            if (id) {
                url = `/api/admin/${this.$route.params.resourceName}/${id}/edit`;
            } else {
                url = `/api/admin/${this.$route.params.resourceName}/new`;
            }
            this.update = id;

            axios.get(url).then(response => {
                this.item = response.data.item;

                this.fields = response.data.fields;
                this.label = response.data.label;
                this.singularLabel = response.data.singularLabel;
                this.loading = false;
                if (this.$route.query.flight) {
                    let field = this.fields.find(field => field.attribute === "flight");
                    if (field) {
                        field.value = Number(this.$route.query.flight);
                        field.disabled = true;

                    }

                }
            })

        }
    }
</script>

<style scoped>

</style>
