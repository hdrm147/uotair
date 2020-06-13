<template>
    <router-link style="text-decoration: none;" class="font-weight-bold" :to="`/admin/${field.resourceName}/${value.id}`">{{value[field.title]}}
    </router-link>
</template>

<script>
    export default {
        name: "BelongsTo",
        props: ["value", "title", "field"]
    }
</script>

<style scoped>

</style>
