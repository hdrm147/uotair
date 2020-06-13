<template>
    <v-checkbox
        class="m-0"
        hide-details
        readonly
        :ripple="false"
        v-model="value"
    ></v-checkbox>
</template>

<script>
    export default {
        name: "Boolean",
        props: ["label","value"]
    }
</script>

<style scoped>
    .v-input--selection-controls {
        margin: 0 !important;
    }
</style>
