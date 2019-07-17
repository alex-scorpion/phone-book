<template>
    <v-dialog v-model="dialog" persistent max-width="500">
        <v-card>
            <v-card-title>
                <span class="headline">Внимание</span>
            </v-card-title>
            <v-progress-linear class="my-0" :height="3" :indeterminate="loading"></v-progress-linear>
            <v-card-text>
                <v-container grid-list-md>
                    <v-layout wrap>
                        <v-flex xs12>
                            <p>Вы действительно хотите удалить контакт <b>{{ contact.name }}</b>?</p>
                        </v-flex>
                    </v-layout>
                </v-container>
            </v-card-text>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="blue darken-1" flat :to="{name: 'contacts'}">Отмена</v-btn>
                <v-btn color="blue darken-1" v-if="contact.id" flat @click="deleteContact">Удалить</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script>
    export default {
        props: ['id'],
        data: () => ({
            dialog: true,
            loading: true,
            contact: {
                id: 0,
                name: ''
            }
        }),
        mounted() {
            window.axios.get(`/contacts/${this.id}`).then((response) => {
                this.loading = false;
                this.contact = response.data.data;
            }).catch((error) => {
                this.loading = false;
                this.$notifications.addCode(error.response.status);
            });
        },
        methods: {
            deleteContact() {
                if (this.contact.id) {
                    this.loading = true;
                    window.axios.delete(`/contacts/${this.contact.id}`).then((response) => {
                        this.loading = false;
                        this.$eventBus.$emit('contacts-update-table', true);
                        this.$notifications.addSuccess(`Контакт <b>${this.contact.name}</b> удален`);
                        this.$router.push({name: 'contacts'});
                    }).catch((error) => {
                        this.loading = false;
                        this.$notifications.addCode(error.response.status);
                    });
                }
            }
        }
    }
</script>
