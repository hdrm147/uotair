<template>
    <v-row>
        <v-col class="py-2" cols="4">
            <span class="subtitle-1 font-weight-normal">{{field.text}}</span>

        </v-col>
        <v-col class="py-2" cols="8">
            <router-link style="text-decoration: none;" class="font-weight-bold" :to="`/admin/${field.resourceName}/${value.id}`">
                {{item[field.attribute][field.title]}}
            </router-link>

        </v-col>
    </v-row>
</template>

<script>
    export default {
        name: "BelongsTo",
        props: ["item", "field"],
        computed: {
            value() {
                return this.item[this.field.attribute];
            }
        },

    }
</script>

<style scoped>

</style>
