<template>
    <div class="clients-page">
        <div class="card card-bordered">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-12 col-lg-6">
                        <h1 class="h3 m-0">Клиенты</h1>
                    </div>
                    <div class="col-12 col-lg-6 text-end">
                        <router-link :to="{name: 'ClientCreate'}" class="btn btn-primary">Добавить</router-link>
                    </div>
                </div>
            </div>
        </div>

        <Loader v-if="views.loading"></Loader>

        <div v-if="!views.loading" class="card card-bordered h-100">
            <div class="card-body p-0 h-100">
                <ag-grid-vue v-if="clients.length"
                    class="ag-theme-alpine clients-table"
                    :defaultColDef="table.defaultColDef"
                    :columnDefs="table.columns"
                    @grid-ready="onGridReady"
                    :rowData="clients"
                    @row-clicked="goTo"
                >
                </ag-grid-vue>
            </div>
        </div>
    </div>
</template>

<script>
    import Loader from '../Loader.vue'

    import { AgGridVue } from "ag-grid-vue";
    import "ag-grid-community/dist/styles/ag-grid.css";
    import "ag-grid-community/dist/styles/ag-theme-alpine.css";

    export default {
        data() {
            return {
                clients: [],

                table: {
                    columns: [
                        {
                            field: "name",
                            headerName: 'Название',
                            sortable: true,
                            filter: true,
                        },
                        {
                            field: "inn",
                            headerName: 'ИНН',
                            sortable: true,
                            filter: true,
                        },
                        {
                            field: "kont_litso",
                            headerName: 'Контактное лицо',
                            sortable: true,
                            filter: true,
                        },
                        {
                            field: "tel",
                            headerName: 'Телефон',
                            sortable: true,
                            filter: true,
                        },
                        {
                            field: "email",
                            headerName: 'E-mail',
                            sortable: true,
                            filter: true,
                        },
                    ],
                    defaultColDef: {
                        sortingOrder: ['asc', 'desc'],
                        floatingFilter: true,
                        suppressMovable: true,
                        suppressMenu: false,
                    },
                },

                views: {
                    loading: true
                }
            }
        },
        created() {
            this.loadClients()
        },
        methods: {
            loadClients() {
                axios
                .get('/api/clients')
                .then(response => (
                    this.clients = response.data,
                    this.views.loading = false
                ))
            },
            onGridReady(params) {
                this.gridApi = params.api
                this.gridColumnApi = params.gridColumnApi
                
                this.gridApi.sizeColumnsToFit()
            },
            goTo(event) {
                this.$router.push({name: 'Client', params: {id: event.data.id}})
            },
        },
        components: {
            Loader,
            AgGridVue
        },
    }
</script>