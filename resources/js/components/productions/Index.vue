<template>
    <div class="productions-page">
        <div class="card card-bordered">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-12 col-lg-6">
                        <h1 class="h3 m-0">Производство</h1>
                    </div>
                </div>
            </div>
        </div>

        <Loader v-if="views.loading"></Loader>

        <div v-if="!views.loading" class="card card-bordered h-100">
            <div class="card-body p-0 h-100">
                <ag-grid-vue v-if="productions.length"
                    class="ag-theme-alpine productions-table"
                    :defaultColDef="table.defaultColDef"
                    :columnDefs="table.columns"
                    @grid-ready="onGridReady"
                    :rowData="productions"
                    @row-clicked="goTo"
                >
                </ag-grid-vue>

                <!-- <table class="table table-hover dataTable">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 5%">ID</th>
                            <th style="width: 20%">Название</th>
                            <th class="text-center" style="width: 5%">Приоритет</th>
                            <th style="width: 10%">Статус</th>
                            <th style="width: 10%">Номер счета</th>
                            <th style="width: 10%">Оплата</th>
                            <th style="width: 10%">Корпус</th>
                            <th style="width: 10%">Серийный номер</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="production in productions" :key="production.id" @click="goTo(production.id)">
                            <td class="align-middle text-center">
                                {{ production.id }}
                            </td>
                            <td class="align-middle">
                                {{ production.name }}
                            </td>
                            <td class="align-middle text-center">
                                <template v-if="production.priority == 'normal'">
                                    <span class="badge rounded-pill bg-info" title="нормальный"></span>
                                </template>
                                <template v-if="production.priority == 'high'">
                                    <span class="badge rounded-pill bg-warning" title="высокий"></span>
                                </template>
                                <template v-if="production.priority == 'urgent'">
                                    <span class="badge rounded-pill bg-danger" title="срочный"></span>
                                </template>
                            </td>
                            <td class="align-middle">
                                <template v-if="production.status == 'new'">
                                    Новый
                                </template>
                                <template v-if="production.status == 'svarka'">
                                    Сварка
                                </template>
                                <template v-if="production.status == 'building'">
                                    Сборка
                                </template>
                                <template v-if="production.status == 'warehouse'">
                                    Склад
                                </template>
                                <template v-if="production.status == 'ready_for_supply'">
                                    Готово к отгрузке
                                </template>
                                <template v-if="production.status == 'waiting_for_feedback'">
                                    Ждем отзыв клиента
                                </template>
                            </td>
                            <td class="align-middle">
                                {{ production.invoice_number }}
                            </td>
                            <td class="align-middle">
                                {{ production.payment_type }}
                            </td>
                            <td class="align-middle">
                                -- korpus --
                            </td>
                            <td class="align-middle">
                                {{ production.serial_number }}
                            </td>
                        </tr>
                    </tbody>
                </table> -->
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
                productions: [],

                table: {
                    columns: [
                        {
                            field: "id",
                            headerName: 'ID',
                            sortable: true,
                            filter: true,
                        },
                        {
                            field: "name",
                            headerName: 'Название',
                            sortable: true,
                            filter: true,
                        },
                        {
                            field: "priority",
                            valueGetter: (params) => {
                                if(params.data.priority == 'normal') {
                                    return 'нормальный' 
                                }
                                if(params.data.priority == 'high') {
                                    return 'высокий' 
                                }
                                if(params.data.priority == 'urgent') {
                                    return 'срочный' 
                                }
                            },
                            headerName: 'Приоритет',
                            sortable: true,
                            filter: true,
                        },
                        {
                            field: "status",
                            valueGetter: (params) => {
                                if(params.data.status == 'new') {
                                    return 'новый'
                                }
                                if(params.data.status == 'svarka') {
                                    return 'сварка'
                                }
                                if(params.data.status == 'building') {
                                    return 'сборка'
                                }
                                if(params.data.status == 'warehouse') {
                                    return 'склад'
                                }
                                if(params.data.status == 'ready_for_supply') {
                                    return 'готово к отгрузке'
                                }
                                if(params.data.status == 'waiting_for_feedback') {
                                    return 'ждем отзыв клиента'
                                }
                            },
                            headerName: 'Статус',
                            sortable: true,
                            filter: true,
                        },
                        {
                            field: "invoice_number",
                            headerName: 'Номер счета',
                            sortable: true,
                            filter: true,
                        },
                        {
                            field: "payment_type",
                            headerName: 'Оплата',
                            sortable: true,
                            filter: true,
                        },
                        {
                            field: "box",
                            valueGetter: (params) => {
                                if(params.data.project && params.data.project.calculations && params.data.project.calculations.length) {
                                    return params.data.project.calculations[0].boxes[0].name
                                }
                            },
                            headerName: 'Корпус',
                            sortable: true,
                            filter: true,
                        },
                        {
                            field: "serial_number",
                            headerName: 'Серийный номер',
                            sortable: true,
                            filter: true,
                        },
                    ],
                    defaultColDef: {
                        sortingOrder: ['asc', 'desc'],
                        floatingFilter: true,
                        suppressMovable: true,
                        suppressMenu: true,
                    },
                },

                views: {
                    loading: true
                }
            }
        },
        created() {
            this.loadProductions()
        },
        methods: {
            loadProductions() {
                axios.get('/api/productions')
                .then(response => (
                    this.productions = response.data,
                    this.views.loading = false
                ))
            },
            onGridReady(params) {
                this.gridApi = params.api
                this.gridColumnApi = params.gridColumnApi
                
                this.gridApi.sizeColumnsToFit()
            },
            goTo(event) {
                this.$router.push({ name: 'Production', params: { id: event.data.id } });
            }
        },
        components: {
            AgGridVue,
            Loader
        }
    }
</script>

<style scoped>
    .table tr:hover {
        cursor: pointer;
    }
</style>