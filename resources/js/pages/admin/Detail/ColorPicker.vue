<template>
    <v-row>
        <v-col class="py-2" cols="4">
            <span class="subtitle-1 font-weight-normal">{{field.text}}</span>

        </v-col>
        <v-col class="py-2" cols="auto">
            <v-sheet :color="value" class="pt-8 px-10"></v-sheet>

        </v-col>
    </v-row>
</template>

<script>
    export default {
        name: "ColorPicker",
        props:["field","item"],
        computed: {
            value() {
                return this.item[this.field.attribute];
            }
        }
    }
</script>

<style scoped>

</style>
