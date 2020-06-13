<template>
    <v-row>
        <v-col class="py-2" cols="4">
            <span class="subtitle-1 font-weight-normal">{{field.text}}</span>

        </v-col>
        <v-col class="py-2" cols="8">
            <span class="subtitle-1 font-weight-normal">
                <template v-if="value">
                    Enabled
                </template>
                <template v-else>
                    Disabled
                </template>
            </span>

        </v-col>
    </v-row>
</template>

<script>
    export default {
        name: "OnlineSeating",
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
