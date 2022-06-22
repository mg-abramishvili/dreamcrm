<template>
    <div class="stock-page">
        <div class="card card-bordered">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-12 col-lg-6">
                        <h1 class="h3 m-0">
                            <router-link :to="{name: 'Stock'}" class="back-arrow">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left align-middle me-2"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                            </router-link>
                            <strong>
                                Последние поступления
                            </strong>
                        </h1>
                    </div>
                </div>
            </div>
        </div>

        <Loader v-if="views.loading"></Loader>

        <div v-if="!views.loading" class="card card-bordered h-100">
            <div class="card-body p-0 h-100">
                <ag-grid-vue v-if="balances.length"
                    class="ag-theme-alpine stock-table"
                    :defaultColDef="table.defaultColDef"
                    :columnDefs="table.columns"
                    @grid-ready="onGridReady"
                    :rowData="balances"
                >
                </ag-grid-vue>
            </div>
        </div>
    </div>
</template>

<script>
    import Loader from '../../Loader.vue'

    import { AgGridVue } from "ag-grid-vue";
    import "ag-grid-community/dist/styles/ag-grid.css";
    import "ag-grid-community/dist/styles/ag-theme-alpine.css";

    export default {
        data() {
            return {
                balances: [],
                
                table: {
                    columns: [
                        {
                            field: "date",
                            valueGetter: (params) => {
                                return params.data.date
                            },
                            valueFormatter: this.dateFormatter,
                            headerName: 'Дата',
                            width: 70,
                        },
                        {
                            field: "stock_item_name",
                            valueGetter: (params) => {
                                return params.data.stock_item.name
                            },
                            headerName: 'Позиция',
                        },
                        {
                            field: "quantity",
                            headerName: 'Количество',
                            width: 100,
                        },
                        {
                            field: "pre_rub",
                            headerName: 'Рубли',
                            valueFormatter: this.currencyFormatter,
                            width: 100,
                        },
                        {
                            field: "pre_usd",
                            headerName: 'Доллары',
                            valueFormatter: this.currencyFormatter,
                            width: 100,
                        },
                        {
                            field: "usd_kurs",
                            headerName: 'Курс',
                            width: 100,
                        },
                        {
                            field: "price",
                            headerName: 'Итог',
                            valueFormatter: this.currencyFormatter,
                            width: 100,
                        },
                    ],
                    defaultColDef: {
                        suppressMovable: true,
                    },
                },

                views: {
                    loading: true,
                },
            }
        },
        created() {
            this.loadBalances()
        },
        methods: {
            loadBalances() {
                axios
                .get(`/api/stock/balances`)
                .then(response => {
                    this.balances = response.data

                    this.views.loading = false
                })
            },
            onGridReady(params) {
                this.gridApi = params.api
                this.gridColumnApi = params.gridColumnApi
                
                this.gridApi.sizeColumnsToFit()
            },
            dateFormatter(params) {
                return this.$options.filters.date(params.value);
            },
            currencyFormatter(params) {
                return this.$options.filters.currency(params.value);
            },
        },
        components: {
            Loader,
            AgGridVue
        },
    }
</script>