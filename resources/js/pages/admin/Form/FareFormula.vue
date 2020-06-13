<template>
    <div>
        <v-row>
            <v-col class="py-0">
                <span class="title">Fare Formula</span>
            </v-col>
        </v-row>
        <v-row align="center">
            <v-col cols="auto" class="pr-0"><span class="display-1">+</span></v-col>
            <v-col cols="auto" class="pl-4">
                <v-text-field  autocomplete="new":error="field.errorMessages.length > 0" v-model="field.increment" hide-details outlined prefix="$"></v-text-field>
            </v-col>
            <v-col cols="auto" class="pr-0"><span class="title">Every</span></v-col>
            <v-col cols="auto" class="pl-6">
                <v-text-field  autocomplete="new":error="field.errorMessages.length > 0" v-model="field.everyDays" hide-details outlined suffix="Days"></v-text-field>
            </v-col>
            <v-col cols="auto" class="pr-0"><span class="title">Closer to Departure</span></v-col>

        </v-row>
        <v-row>
            <v-col class="py-0">
                <span class="subtitle-2 font-weight-normal red--text">{{field.errorMessages.join("")}}</span>

            </v-col>
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
