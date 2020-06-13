<template>
   <v-row>
       <v-col class="py-2" cols="4">
           <span class="subtitle-1 font-weight-normal">{{field.text}}</span>

       </v-col>
       <v-col class="py-2" cols="8">
           <span class="subtitle-1 font-weight-normal">{{value}}</span>

       </v-col>
   </v-row>
</template>

<script>
    export default {
        name: "TextField",
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
