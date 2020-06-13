<template>
    <div>
        <v-row class="mt-4">
            <v-col class="py-0">
                <span class="title">Fare Formula</span>
            </v-col>
        </v-row>
        <v-row align="center">
            <v-col cols="auto" class="pr-0"><span class="display-1">+</span></v-col>
            <v-col cols="auto" class="pl-4">
                <span class="title font-weight-bold primary--text">${{field.increment}}</span>
            </v-col>
            <v-col cols="auto" class="pr-0"><span class="title">Every</span></v-col>
            <v-col cols="auto" class="pl-6">
                <span class="title font-weight-bold primary--text">{{field.everyDays}} Days</span>
            </v-col>
            <v-col cols="auto" class="pr-4"><span class="title">Closer to Departure</span></v-col>

        </v-row>
    </div>
</template>

<script>
    export default {
        name: "FareFormula",
        props: {
            field: {
                type: Object,
            },
            item: {
                type: Object,
                default: () => {
                }
            }
        },
        watch: {
            field: {
                deep: true,
                handler() {
                    this.field.value = JSON.stringify({
                        everyDays: this.field.everyDays,
                        increment: this.field.increment,
                    })
                }
            }
        },
        mounted() {
            this.field.everyDays = this.item.fare_every_days;
            this.field.increment = this.item.fare_increment;
        },
    }
</script>

<style scoped>

</style>
