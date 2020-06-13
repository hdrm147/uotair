<template>
    <v-checkbox
        v-model="field.value"
        :label="field.text"
    ></v-checkbox>
</template>

<script>
    export default {
        name: "Boolean",
        props:["field","item"],
        mounted() {
            this.field.value = this.item[this.field.attribute];
        }

    }
</script>

<style scoped>

</style>
