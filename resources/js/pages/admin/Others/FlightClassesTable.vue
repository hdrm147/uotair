<template>
    <div>
        <v-card class="overflow-hidden">
            <v-data-table
                :headers="headers"
                :items="classes"
                :items-per-page="5"
                class="elevation-1"
            >
                <template
                    v-slot:item.fare_formula="{ item }">
                    <span class="condensed font-weight-bold ">  + ${{item.pivot.fare_increment}} Every {{item.pivot.fare_every_days}} Days</span>
                </template>
                <template
                    v-slot:item.actions="{ item }">
                    <v-icon small @click="edit(item)" class="mr-1" color="grey">mdi-pencil
                    </v-icon>
                    <v-icon small @click="drop(item)" class="mr-1" color="grey">
                        mdi-trash-can-outline
                    </v-icon>
                </template>
            </v-data-table>
        </v-card>

    </div>
</template>

<script>
    export default {
        name: "FlightClassesTable",
        props: ["flight"],
        data() {
            return {
                classes: [],
                headers: [
                    {
                        text: 'Class',
                        align: 'start',
                        sortable: false,
                        value: 'name',
                    },
                    {text: 'Adult Fare', value: 'pivot.minimum_adult_fare'},
                    {text: 'Maximum Adult Fare', value: 'pivot.maximum_adult_fare'},
                    {text: 'Children Fare %', value: 'pivot.children_fare_percentage'},
                    {text: 'Capacity', value: 'pivot.capacity'},
                    {text: 'Fare Formula', value: 'fare_formula'},
                    {text: "Actions", value: "actions",align: "end"}
                ],
            }
        },
        methods: {
            edit(item) {
                this.$router.push({
                    path: `/admin/flight-class-pivot/${item.pivot.id}/edit`

                });
            },
            view(item) {
                this.$router.push({
                    path: `/admin/flight-class-pivot/${item.pivot.id}`

                });
            },
            drop(item) {
                this.$root.deleteDialog.show = true
                this.$root.deleteDialog.resourceId = item.pivot.id
                this.$root.deleteDialog.resourceName = "flight-class-pivot";
            },
        },
        mounted() {
            axios.get(`/api/admin/flights/${this.flight.id}/classes`).then(response => {
                this.classes = response.data.classes;
                this.$emit('load-finish',this.classes);
            })
        }
    }
</script>

<style scoped>

</style>
