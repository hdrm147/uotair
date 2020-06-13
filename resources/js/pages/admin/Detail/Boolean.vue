<template>
    <v-row>
        <v-col class="py-2" cols="4">
            <span class="subtitle-1 font-weight-normal">{{field.text}}</span>

        </v-col>
        <v-col class="py-2" cols="8">
           <v-icon color="green darken-1" v-if="value">mdi-check</v-icon>
           <v-icon color="red darken-1" v-else>mdi-close</v-icon>

        </v-col>
    </v-row>
</template>

<script>
    export default {
        name: "Boolean",
        props: ["field", "item"],

        computed: {
            value() {
                return this.item[this.field.attribute];
            }
        }
    }
</script>

<style scoped>

</style>
