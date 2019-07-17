<template>
    <div>
        <v-toolbar :clipped-left="$vuetify.breakpoint.lgAndUp" color="blue darken-3" dark app fixed>
            <v-toolbar-title style="width: 300px" class="ml-0 pl-3">
                <v-icon>apps</v-icon>
                <span class="hidden-sm-and-down">Phone Book</span>
            </v-toolbar-title>
            <v-text-field flat solo-inverted hide-details prepend-inner-icon="search"
                          label="Поиск" v-model="search" class="hidden-sm-and-down"
            ></v-text-field>
            <v-spacer></v-spacer>
            <v-btn icon :to="{name: 'contacts.new'}">
                <v-icon>add_circle</v-icon>
            </v-btn>
        </v-toolbar>
        <v-content>
            <v-container fluid>
                <v-layout justify-center align-center>
                    <v-flex xs12>
                        <v-data-table
                                :headers="headers"
                                :items="contacts"
                                :pagination.sync="pagination"
                                :total-items="totalContacts"
                                :loading="loading"
                                :rows-per-page-items="[25, 50, 100]"
                                :rows-per-page-text="'Записей на странице'"
                                class="elevation-1"
                        >
                            <template slot="items" slot-scope="props">
                                <tr>
                                    <!--<td class="text-xs-left">{{ props.item.id }}</td>-->
                                    <td class="text-xs-left pr-0">{{ props.item.name }}</td>
                                    <td class="text-xs-left pr-0">{{ props.item.phones[0].phone }}</td>
                                    <td class="text-xs-right pl-0" style="min-width: 100px">
                                        <v-btn icon class="mx-0" :to="{name: 'contacts.edit', params: {id: props.item.id}}">
                                            <v-icon color="teal">edit</v-icon>
                                        </v-btn>
                                        <v-btn icon class="mx-0" :to="{name: 'contacts.delete', params: {id: props.item.id}}">
                                            <v-icon color="pink">delete</v-icon>
                                        </v-btn>
                                    </td>
                                </tr>
                            </template>
                            <template slot="pageText" slot-scope="props">
                                {{ props.pageStart || 1 }} - {{ props.pageStop || props.itemsLength }} из {{ props.itemsLength }}
                            </template>
                            <template slot="no-data">
                                <p class="headline text-xs-center my-4">{{ loading ? 'Загрузка...' : 'Нет даных' }}</p>
                            </template>
                        </v-data-table>
                    </v-flex>
                </v-layout>
            </v-container>
        </v-content>
        <router-view></router-view>
    </div>
</template>

<script>
    export default {
        data: () => ({
            loading: true,
            contacts: [],
            search: '',
            totalContacts: 0,
            pagination: {
                descending: false,
                page: 1,
                rowsPerPage: 25,
                sortBy: null,
                totalItems: 0,
            },
            headers: [
                // {text: '№', align: 'left', sortable: false, value: 'id', width: '10px'},
                {text: 'Имя', align: 'left', sortable: false, value: 'name', width: '50%'},
                {text: 'Телефон', align: 'left', sortable: false, value: 'phone'},
                {text: '', align: 'right', sortable: false, value: 'action', width: '100px'},
            ],
            currentQuery: {},
        }),
        created() {
            this.$eventBus.$on('contacts-update-table', this.getDataFromApi);
        },
        mounted () {
            this.getDataFromApi();
        },
        watch: {
            pagination: {
                handler() { this.getDataFromApi() },
                deep: true
            },
            search: {
                handler() { this.getDataFromApi() },
                deep: true
            }
        },
        methods: {
            getDataFromApi(force = false) {
                const { page, rowsPerPage } = this.pagination;

                let query = {
                    page: page,
                    count: rowsPerPage,
                    search: this.search.trim(),
                };

                if (force || JSON.stringify(this.currentQuery) !== JSON.stringify(query)) {
                    this.currentQuery = query;
                    this.loading = true;

                    window.axios
                        .get(`/contacts?page=${query.page}&count=${query.count}&search=${query.search}`)
                        .then((response) => {
                            this.contacts = response.data.data;
                            this.pagination.page = response.data.meta.current_page || 1;
                            this.totalCompanies = this.pagination.totalItems = response.data.meta.total || 0;
                            this.loading = false;
                        })
                        .catch((error) => {
                            this.contacts = [];
                            this.totalCompanies = this.pagination.totalItems = 0;
                            this.$notifications.addCode(error.response.status);
                            this.loading = false;
                        });
                }
            },
        }
    }
</script>