<template>
    <div class="my-2">
        <v-avatar color="primary" size="30">
            <img v-if="src" :src="src">
            <span v-else class="white--text subtitle-2">{{initials}}</span>
        </v-avatar>
    </div>
</template>

<script>

    export default {
        name: "Avatar",
        props: ["item", "field"],
        data() {
            return {
                src: null
            }
        },
        methods: {
            pickFile() {
                this.$refs.file.click();
            },
            filePicked() {
                let file = this.$refs.file.files[0];
                if (file) {
                    this.src = URL.createObjectURL(file);
                    this.field.value = file;
                }
            }

        },
        computed: {
            initials() {
                if (this.item && this.item.name) {
                    let str = this.item.name;
                    const FirstName = str.split(' ')[0];
                    const LastName = str.split(' ').reduceRight(a => a);

                    // Extract the first two characters of a string
                    if (FirstName === LastName) {
                        return FirstName.trim()
                            .substring(0, 2)
                            .toUpperCase();
                    }

                    // Abbreviate FirstName and LastName
                    return [FirstName, LastName]
                        .map(name => name[0])
                        .join('').toUpperCase();
                }
            }
        },
        mounted() {
            if (this.item && this.item.avatar) {
                this.src = `/${this.item.avatar}`
            }
        }
    }
</script>

<style scoped>

</style>
