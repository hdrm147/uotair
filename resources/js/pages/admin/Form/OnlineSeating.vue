<template>

    <v-col v-if="item.id" class="py-0">
        <v-row>
            <v-checkbox
                v-model="enabled"
                :label="field.text"
            ></v-checkbox>
        </v-row>
        <v-row v-if="enabled">
            <flight-seats :field="field" @input="setValue" v-model="grid" :classes="classes"></flight-seats>
        </v-row>
        <input type="text">
    </v-col>


</template>

<script>
    import FlightSeats from "../Others/FlightSeats";

    export default {
        name: "OnlineSeating",
        props: ["field", "item"],
        components: {
            FlightSeats
        },
        data() {
            return {
                enabled: false,
                grid: null,
            }
        },
        methods: {
            setValue() {
                this.field.value = JSON.stringify(this.grid);
            }
        },
        computed: {
            value() {
                return this.item[this.field.attribute];
            },
            classes() {
                return this.item.classes.map(flightClass => {
                    return {
                        key: `class-${flightClass.id}`,
                        ...flightClass
                    }
                })
            }
        },
        mounted() {

            this.grid = this.item[this.field.attribute];
            if (this.grid) {
                this.enabled = true;
            }
        }
    }
</script>

<style scoped>

</style>
