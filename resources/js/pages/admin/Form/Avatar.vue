<template>
    <div class="mb-4">
        <v-row justify="center">
            <v-col cols="auto">
                <v-avatar color="primary" size="250">
                    <img v-if="src" :src="src">
                    <span v-else class="white--text display-4">{{initials}}</span>
                </v-avatar>
            </v-col>
        </v-row>
        <input @input="filePicked" hidden type="file" ref="file">
        <v-row justify="center">
            <v-btn @click="pickFile" color="primary" text>{{src ? "Change" : "Upload"}}</v-btn>
        </v-row>
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
