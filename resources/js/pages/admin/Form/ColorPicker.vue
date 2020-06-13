<template>
    <v-row justify="center">
        <v-col>
            <v-row justify="center">
                <span class="title">{{field.text}}</span>
            </v-row>
            <v-row justify="center">
                <v-color-picker hide-canvas hide-inputs show-swatches class="elevation-0" v-model="field.value"></v-color-picker>

            </v-row>
        </v-col>
    </v-row>
</template>

<script>
    export default {
        name: "ColorPicker",
        props: ["field", "item"],
        mounted() {
            if (this.item && this.item[this.field.attribute]) {
                this.field.value = this.item[this.field.attribute];
            } else {
                this.field.value = "#fff"
            }
        }

    }
</script>

<style scoped>

</style>
