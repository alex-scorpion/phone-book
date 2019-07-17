<template>
    <v-dialog v-model="model" persistent width="600px">
        <v-card>
            <v-card-title class="grey lighten-4 py-4 title">
                {{ this.id ? 'Изменить' : 'Новый' }} контакт
            </v-card-title>
            <v-container grid-list-sm class="pa-4">
                <v-form ref="form" v-model="valid">
                    <v-layout row wrap>
                        <v-flex xs12>
                            <v-text-field prepend-icon="account_circle"
                                          v-model="contact.name"
                                          placeholder="Полное имя"
                                          required
                                          counter
                                          :rules="[rules.required, rules.counter100]"
                                          maxlength="100"
                            ></v-text-field>
                        </v-flex>
                        <v-flex xs12>
                            <v-text-field prepend-icon="notes"
                                          v-model="contact.description"
                                          placeholder="Заметка"
                                          required
                                          counter
                                          :rules="[rules.required, rules.counter200]"
                                          maxlength="200"
                            ></v-text-field>
                        </v-flex>
                    </v-layout>

                    <v-layout row wrap v-for="(phone, index) in phones" :key="index">
                        <v-flex xs10>
                            <v-text-field type="tel" prepend-icon="phone"
                                          placeholder="0 (000) 000 - 0000" mask="8 (###) ### - ####"
                                          v-model="phone.phone" required
                                          :rules="[rules.required, rules.phone]"
                            ></v-text-field>
                        </v-flex>
                        <v-flex xs1>
                            <v-btn flat icon :disabled="phones.length === 1" @click="removePhone(phone.hash)">
                                <v-icon>clear</v-icon>
                            </v-btn>
                        </v-flex>
                        <v-flex xs1 v-if="index === phones.length - 1">
                            <v-btn flat icon @click="addEmptyPhone">
                                <v-icon>add</v-icon>
                            </v-btn>
                        </v-flex>
                    </v-layout>
                </v-form>
            </v-container>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn flat color="primary" :to="{name: 'contacts'}">Отмена</v-btn>
                <v-btn flat color="primary" :disabled="!valid" @click="saveContact">Сохранить</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script>
    export default {
        props: {
            id: {default: 0}
        },
        data: function() {
            return {
                model: true,
                valid: false,
                contact: {
                    id: this.id,
                    name: '',
                    description: ''
                },
                phones: [
                    {
                        hash: window.helpers.newHash(),
                        id: 0,
                        phone: ''
                    }
                ],
                rules: {
                    required: value => !!value || 'Заполните поле',
                    counter100: value => value.length <= 100 || 'Максимальная длина 100 символов',
                    counter200: value => value.length <= 200 || 'Максимальная длина 200 символов',
                    phone: value => value.length === 11 || 'Не верный формат',
                }
            };
        },
        mounted () {
            if (this.id) {
                window.axios.get(`/contacts/${this.id}`).then((response) => {
                    this.loading = false;
                    this.contact = {
                        id: response.data.data.id,
                        name: response.data.data.name,
                        description: response.data.data.description,
                    };
                    if (response.data.data.phones.length) {
                        this.phones = response.data.data.phones.map(function (phone) {
                            return {
                                hash: window.helpers.newHash(),
                                id: phone.id,
                                phone: phone.phone
                            };
                        });
                    }
                    this.validate();
                }).catch((error) => {
                    this.loading = false;
                    this.$notifications.addCode(error.response.status);
                    this.validate();
                });
            }
        },
        methods: {
            validate() {
                if (this.$refs.form.validate()) {
                    this.snackbar = true
                }
            },
            addEmptyPhone() {
                this.phones.push({
                    id: 0,
                    hash: window.helpers.newHash(),
                    phone: ''
                });
            },
            removePhone(hash) {
                if (this.phones.length > 1) {
                    this.phones = this.phones.filter(item => item.hash !== hash);
                }
            },
            saveContact() {
                this.loading = true;
                let urlApi = this.id ? `/contacts/${this.id}` : `/contacts`;
                let postData = {
                    contact: this.contact,
                    phones: this.phones.map(function (phone) {
                        return {
                            id: phone.id,
                            phone: phone.phone
                        };
                    })
                };
                window.axios.post(urlApi, postData).then((response) => {
                    this.loading = false;
                    this.$eventBus.$emit('contacts-update-table', true);
                    this.$notifications.addSuccess(`Контакт <b>${this.contact.name}</b> сохранен`);
                    this.$router.push({name: 'contacts'});
                }).catch((error) => {
                    this.loading = false;
                    this.$notifications.addCode(error.response.status);
                });

            }
        }
    }
</script>