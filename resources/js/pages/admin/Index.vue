<template>
    <v-container>
        <v-row class="pa-12">
            <v-col>
                <v-skeleton-loader
                    type="image"
                    max-width="200px"
                    height="40px"
                    :loading="!firstLoaded">
                    <h3 class="display-1 grey--text text--darken-3">
                        {{label}}
                    </h3>
                </v-skeleton-loader>
                <v-row justify="space-between">
                    <v-col cols="12" sm="6" md="3">
                        <v-skeleton-loader
                            type="image"
                            min-width="600px"
                            height="40px"
                            :loading="!firstLoaded">
                            <v-text-field
                                label="Search"
                                solo
                                prepend-inner-icon="mdi-magnify"
                                v-model="search"
                                hide-details
                            ></v-text-field>
                        </v-skeleton-loader>


                    </v-col>
                    <v-col md="auto">
                        <v-skeleton-loader
                            type="image"
                            min-width="200px"
                            height="40px"
                            :loading="!firstLoaded">
                            <v-btn :to="{path:`/admin/${$route.params.resourceName}/new`}"
                                   color="primary">Create {{singularLabel}}
                            </v-btn>

                        </v-skeleton-loader>

                    </v-col>

                </v-row>
                <v-row>
                    <v-col>
                        <v-card>
                            <v-skeleton-loader
                                :loading="!firstLoaded"
                                ref="skeleton"
                                type="table-tbody"
                                transition="fade-transition"
                                class="mx-auto"

                            >
                                <v-data-table
                                    :headers="headers"
                                    :items="items"
                                    :loading="loading"
                                    :server-items-length="total"
                                    :options.sync="options"
                                    :page.sync="page"
                                    class="overflow-hidden"
                                >
                                    <template v-slot:body="{ items }">
                                        <tbody>
                                        <tr v-for="(item,index) in items" :key="item.id">

                                            <td v-for="header in headers"
                                                :class="{'text-right':header.attribute == 'actions'}">
                                                <template v-if="header.attribute == 'actions'">
                                                    <v-icon @click="edit(item)" class="mr-1" color="grey">mdi-pencil
                                                    </v-icon>
                                                    <v-icon @click="view(item)" class="mr-1" color="grey">mdi-eye
                                                    </v-icon>
                                                    <v-icon @click="drop(item)" class="mr-1" color="grey">
                                                        mdi-trash-can-outline
                                                    </v-icon>
                                                </template>
                                                <template v-else>
                                                    <component :field="header" :value="item[header.attribute]" :item="item" :is="header.component"></component>

                                                </template>
                                            </td>


                                        </tr>
                                        </tbody>
                                    </template>

                                </v-data-table>

                            </v-skeleton-loader>


                        </v-card>
                    </v-col>
                </v-row>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
    import Boolean from "./Index/Boolean";
    import TextField from "./Index/TextField";
    import BelongsTo from "./Index/BelongsTo";
    import _ from 'lodash'
    import Avatar from "./Index/Avatar";
    import ColorPicker from "./Index/ColorPicker";

    export default {
        name: "Index",
        components: {
            Boolean,
            TextField,
            BelongsTo,
            Avatar,
            ColorPicker
        },
        data() {
            return {
                label: "",
                singularLabel: "",
                headers: [],
                items: [],
                action: "",
                loading: true,
                firstLoaded: false,
                total: 0,
                search: null,
                page: 1,
                options: {
                    sortBy: [],
                    sortDesc: [],
                    itemsPerPage: 10,
                }
            }
        },
        methods: {
            edit(item) {
                this.$router.push({
                    path: `/admin/${this.$route.params.resourceName}/${item.id}/edit`

                });
            },
            view(item) {
                this.$router.push({
                    path: `/admin/${this.$route.params.resourceName}/${item.id}`

                });
            },
            drop(item) {
                this.$root.deleteDialog.show = true
                this.$root.deleteDialog.resourceId = item.id
                this.$root.deleteDialog.resourceName = this.$route.params.resourceName
            },

            fetchItems: _.debounce(function () {
                this.loading = true;
                axios.get(`/api/admin/${this.$route.params.resourceName}`, {
                    params: {
                        search: this.search,
                        page: this.page,
                        options: this.options,
                    }
                }).then(response => {
                    this.headers = response.data.headers;
                    this.items = response.data.items;
                    this.label = response.data.label;
                    this.singularLabel = response.data.singularLabel;
                    this.loading = false;
                    this.total = response.data.total;
                    this.firstLoaded = true
                })
            }, 500)
        },
        watch: {
            page() {
                this.fetchItems();
            },
            options() {
                this.fetchItems();
            },
            search() {
                this.fetchItems();
            }
        },
        mounted() {
            this.fetchItems();
            this.$root.$on('resource-deleted', () => {
                this.fetchItems();
            });
        }
    }
</script>

<style scoped>

</style>
