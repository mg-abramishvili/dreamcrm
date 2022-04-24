<template>
    <div class="catalog-page">
        <div class="card card-bordered">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-12 col-lg-6">
                        <h1 class="h3 m-0">
                            <router-link :to="{name: 'Catalog'}" class="back-arrow">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left align-middle me-2"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                            </router-link>
                            <strong>
                                Корпуса
                            </strong>
                        </h1>
                    </div>
                    <div class="col-12 col-lg-6 text-end">
                        <router-link :to="{name: 'CatalogBoxCreate'}" class="btn btn-primary">Добавить</router-link>
                    </div>
                </div>
            </div>
        </div>

        <Loader v-if="views.loading"></Loader>

        <div v-if="!views.loading" class="card card-bordered h-100">
            <div class="card-body p-0 h-100">
                <ag-grid-vue v-if="boxes.length"
                    class="ag-theme-alpine catalog-table"
                    :defaultColDef="table.defaultColDef"
                    :columnDefs="table.columns"
                    @grid-ready="onGridReady"
                    :rowData="boxes"
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
                boxes: [],

                table: {
                    columns: [
                        {
                            field: "name",
                            headerName: 'Название',
                            sortable: true,
                            filter: true,
                        },
                        {
                            field: "price",
                            valueFormatter: this.currencyFormatter,
                            headerName: 'Цена',
                            sortable: true,
                            filter: false,
                            width: 50,
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
            this.loadBoxes()
        },
        methods: {
            loadBoxes() {
                axios
                .get(`/api/catalog/boxes`)
                .then(response => (
                    this.boxes = response.data,
                    this.views.loading = false
                ));
            },
            onGridReady(params) {
                this.gridApi = params.api
                this.gridColumnApi = params.gridColumnApi
                
                this.gridApi.sizeColumnsToFit()
            },
            goTo(event) {
                this.$router.push({name: 'CatalogBoxEdit', params: {id: event.data.id}})
            },
            currencyFormatter(params) {
                return this.$options.filters.currency(params.value) + ' ₽';
            },
        },
        components: {
            Loader,
            AgGridVue
        },
    }
</script>