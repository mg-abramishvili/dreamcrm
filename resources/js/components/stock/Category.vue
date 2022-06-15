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
                                {{ category.name }}
                            </strong>
                            <router-link :to="{name: 'StockCategoryEdit', params: {category_id: $route.params.category_id}}" class="btn btn-sm ms-1" style="opacity: 0.5">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit align-middle"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                            </router-link>
                        </h1>
                    </div>
                    <div class="col-12 col-lg-6 text-end">
                        <router-link :to="{name: 'StockItemCreate', params: {category_id: category.id}}" class="btn btn-primary">Добавить</router-link>
                    </div>
                </div>
            </div>
        </div>

        <div v-if="!views.loading" class="card card-bordered h-100">
            <div class="card-body p-0 h-100">
                <ag-grid-vue v-if="category.items.length"
                    class="ag-theme-alpine stock-table"
                    :defaultColDef="table.defaultColDef"
                    :columnDefs="table.columns"
                    @grid-ready="onGridReady"
                    :rowData="category.items"
                    @row-clicked="goTo"
                >
                </ag-grid-vue>
            </div>
        </div>

        <!-- <div v-if="!views.loading" class="card">
            <table class="table dataTable">
                <thead>
                    <tr>
                        <th>Наименование</th>
                        <th class="text-center">Общий остаток</th>
                        <th>Остатки</th>
                    </tr>
                </thead>
                <tbody>
                    <tr @click="goTo(item.id)" v-for="item in category.items" :key="'item_' + item.id">
                        <td class="align-middle">
                            <a>{{ item.name }}</a>
                        </td>
                        <td class="align-middle text-center">
                            <span class="badge rounded-pill" :class="{ 'bg-danger': item.balance < 0, 'bg-secondary': item.balance == 0, 'bg-success': item.balance > 0 }">
                                {{ item.balance }}
                            </span>
                        </td>
                        <td class="align-middle">
                            <ul v-if="item.balances.length" class="m-0 p-0" style="list-style: none;">
                                <li v-for="balance in item.balances" :key="'balance_' + balance.id">
                                    {{ balance.quantity }} шт. | {{ balance.price | currency }} ₽ | {{ balance.created_at | date }}
                                </li>
                            </ul>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div> -->
        
        <Loader v-else></Loader>
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
                category: {},

                table: {
                    columns: [
                        {
                            field: "name",
                            headerName: 'Наименование',
                            sortable: true,
                            filter: true,
                            suppressMenu: true,
                        },
                        {
                            field: "balance",
                            headerName: 'Общий остаток',
                            sortable: true,
                            filter: false,
                            suppressMenu: true,
                        },
                        {
                            field: "balances",
                            headerName: 'Остатки',
                            sortable: false,
                            filter: false,
                            suppressMenu: true,
                            valueGetter: (params) => {
                                let balances = []

                                params.forEach(b => {
                                    balances.push(b.quantity + ' | ' + b.price + ' | ' + b.date)
                                })

                                return balances
                            },
                        },
                    ],
                    defaultColDef: {
                        sortingOrder: ['asc', 'desc'],
                        floatingFilter: true,
                        suppressMovable: true,
                    },
                },

                views: {
                    loading: true,
                }
            }
        },
        created() {
            this.loadCategory()
        },
        methods: {
            loadCategory() {
                axios
                .get(`/api/stock/category/${this.$route.params.category_id}`)
                .then(response => {
                    this.category = response.data.data

                    this.views.loading = false
                })
            },
            onGridReady(params) {
                this.gridApi = params.api
                this.gridColumnApi = params.gridColumnApi
                
                this.gridApi.sizeColumnsToFit()
            },
            goTo(event) {
                this.$router.push({ name: 'StockItem', params: { id: event.data.id } });
            },
        },
        components: {
            Loader,
            AgGridVue
        }
    }
</script>