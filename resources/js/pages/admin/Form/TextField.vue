<template>
    <v-text-field  autocomplete="off" :error-messages="field.errorMessages || []" v-model="field.value" outlined :label="field.text"
                  :type="field.isSecure ? 'password' :`text`"

    ></v-text-field>
</template>

<script>
    export default {
        name: "TextField",
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
        mounted() {
            if (this.item && this.item[this.field.attribute]) {
                this.field.value = this.item[this.field.attribute];
            } else {
                this.field.value = null;
            }
        },
        watch: {
            field: {
                deep: true,
                handler() {
                    if (this.item && this.item.hasOwnProperty(this.field.attribute)) {
                        this.item[this.field.attribute] = this.field.value;
                    }
                }
            }
        }
    }
</script>

<style scoped>

</style>
