<template>
    <v-autocomplete autocomplete="off"
                    v-model="field.value"
                    :items="items"
                    :loading="isLoading"
                    hide-no-data
                    :item-text="field.title"
                    :error-messages="field.errorMessages"
                    item-value="id"
                    :label="field.text"
                    placeholder="Start typing to Search"
                    outlined
                    :disabled="field.disabled"
                    :search-input.sync="search"

    ></v-autocomplete>
</template>

<script>
    export default {
        name: "BelongsTo",
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
        data() {
            return {
                items: [],
                isLoading: false,
                value: null,
                search: null,
            }
        },
        methods: {},
        watch: {
            search() {
                if (!this.field.searchable || this.loading || this.items.filter(item => item[this.field.title] == this.search).length > 0) {
                    return
                }
                this.isLoading = true;
                axios.get(`/api/admin/${this.field.resourceName}`, {
                        params: {
                            search: this.search
                        }
                    }
                ).then(response => {
                    this.isLoading = false;
                    this.items = response.data.items;
                })
            }
        },
        mounted() {
            this.isLoading = true;

            axios.get(`/api/admin/${this.field.resourceName}`).then(response => {
                this.isLoading = false;

                this.items = response.data.items;
            })
            if (this.item && this.item[this.field.attribute]) {
                this.field.value = this.item[this.field.attribute].id;
                this.value = this.field.value;
            }

        }
    }
</script>

<style scoped>

</style>
