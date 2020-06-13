<template>
    <v-container>
        <v-row class="pa-12 pb-0" justify="center">
            <v-col md="7">
                <v-row class="mb-4">
                    <v-col>
                        <v-skeleton-loader
                            type="image"
                            max-width="200px"
                            height="40px"
                            :loading="loading">
                            <h3 class="display-1 grey--text text--darken-3 ">
                                {{singularLabel}} Details
                            </h3>
                        </v-skeleton-loader>
                    </v-col>
                    <v-col md="auto">
                        <v-skeleton-loader
                            type="image,image"
                            min-width="130px"
                            height="40px"
                            :loading="loading">
                            <v-btn @click="drop" color="white">
                                <v-icon color="grey">mdi-trash-can-outline</v-icon>
                            </v-btn>
                            <v-btn :to="{path:`/admin/${$route.params.resourceName}/${$route.params.resourceId}/edit`}"
                                   color="primary">
                                <v-icon>mdi-pencil</v-icon>
                            </v-btn>

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
                                <template v-for="(field,index) in fields">

                                    <component :is="field.component" :field="field" :item="item"
                                               :key="field.attribute"/>

                                    <v-col v-if="index + 1 != fields.length" cols="12"
                                           :key="`divider-${field.attribute}`"
                                           class="pa-0">
                                        <v-divider></v-divider>
                                    </v-col>
                                </template>
                            </div>
                        </transition>


                    </v-card-text>
                </v-card>

            </v-col>
        </v-row>
        <v-row v-if="isFlightDetail" class="pa-12 pt-2 pb-2" justify="center">
            <v-col md="7">
                <v-row class="mb-4">
                    <v-col>
                        <v-skeleton-loader
                            type="image"
                            max-width="200px"
                            height="40px"
                            :loading="loading">
                            <h3 class="display-1 grey--text text--darken-3 ">
                                {{singularLabel}} Classes
                            </h3>
                        </v-skeleton-loader>
                    </v-col>
                    <v-col md="auto">
                        <v-skeleton-loader
                            type="image,image"
                            min-width="130px"
                            height="40px"
                            :loading="loading">
                            <v-btn :to="{path:`/admin/flight-class-pivot/new`,query:{flight: item ? item.id : null}}"
                                   color="primary">
                                <v-icon>mdi-plus</v-icon>
                                Attach Class
                            </v-btn>

                        </v-skeleton-loader>

                    </v-col>
                </v-row>
                <transition name="fade" mode="out-in">
                    <v-col v-if="loading" class="text-center pa-0" key="loading">
                        <v-card>
                            <v-skeleton-loader
                                :loading="loading"
                                :key="`skeleton-`"
                                ref="skeleton"
                                type="table-tbody"
                                height="180px"
                                class="my-3"
                                transition="fade-transition"
                            ></v-skeleton-loader>
                        </v-card>
                    </v-col>
                    <v-col v-else class="pa-0">
                        <flight-classes-table ref="classes"
                                              :flight="item"></flight-classes-table>
                    </v-col>
                </transition>


            </v-col>
        </v-row>
    </v-container>
</template>

<script>
    import BelongsTo from "./Detail/BelongsTo";
    import TextField from "./Detail/TextField";
    import Boolean from "./Detail/Boolean";
    import Avatar from "./Detail/Avatar";
    import FlightClassesTable from "./Others/FlightClassesTable";
    import FareFormula from "./Detail/FareFormula";
    import FlightSeats from "./Others/FlightSeats";
    import ColorPicker from "./Detail/ColorPicker";
    import OnlineSeating from "./Detail/OnlineSeating";

    export default {
        name: "Detail",
        components: {
            BelongsTo, TextField, Boolean, Avatar, FlightClassesTable, FareFormula,FlightSeats,ColorPicker,OnlineSeating
        },
        data() {
            return {
                label: "",
                singularLabel: "",
                loading: true,
                item: null,
                fields: []
            }
        },
        methods: {
            drop() {
                this.$root.deleteDialog.show = true;
                this.$root.deleteDialog.resourceId = this.item.id
                this.$root.deleteDialog.resourceName = this.$route.params.resourceName
            },

        },
        computed: {
            isFlightDetail() {
                return this.$route.params.resourceName === "flights";
            }
        },
        mounted() {
            axios.get(`/api/admin/${this.$route.params.resourceName}/${this.$route.params.resourceId}`).then(response => {
                this.item = response.data.item;

                this.fields = response.data.fields;
                this.label = response.data.label;
                this.singularLabel = response.data.singularLabel;
                this.loading = false;
            })
            this.$root.$on('resource-deleted', () => {
                this.$router.push({
                    path: `/admin/${this.$route.params.resourceName}/`
                })
            });
        },

    }
</script>

<style scoped>

</style>
