<template>
    <div class="projects-page">
        <div class="card card-bordered">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-12 col-lg-6">
                        <h1 class="h3 m-0">Проекты</h1>
                    </div>
                </div>
            </div>
        </div>

        <div v-if="!views.loading" class="card card-bordered h-100">
            <div class="card-body p-0 h-100">
                <ag-grid-vue v-if="projects.length"
                    class="ag-theme-alpine projects-table"
                    :defaultColDef="table.defaultColDef"
                    :columnDefs="table.columns"
                    @grid-ready="onGridReady"
                    :rowData="projects"
                    @row-clicked="goTo"
                >
                </ag-grid-vue>
            </div>
        </div>
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
                projects: [],

                table: {
                    columns: [
                        {
                            field: "created_at",
                            valueFormatter: this.dateFormatter,
                            headerName: 'Дата',
                            sortable: true,
                            filter: false,
                            suppressMenu: true,
                            width: 100,
                        },
                        {
                            field: "name",
                            headerName: 'Название',
                            sortable: true,
                            filter: true,
                            suppressMenu: true,
                        },
                        {
                            field: "box",
                            headerName: 'Корпус',
                            sortable: true,
                            filter: true,
                            suppressMenu: true,
                        },
                        {
                            field: "endclient",
                            headerName: 'Конечник',
                            sortable: true,
                            filter: true,
                            suppressMenu: true,
                        },
                        {
                            field: "endclient_inn",
                            headerName: 'ИНН конечника',
                            sortable: true,
                            filter: true,
                            suppressMenu: true,
                        },
                        {
                            field: "author",
                            headerName: 'Автор',
                            sortable: true,
                            filter: true,
                            suppressMenu: true,
                        },
                    ],
                    defaultColDef: {
                        sortingOrder: ['asc', 'desc'],
                        floatingFilter: true,
                        suppressMovable: true,
                    },
                },

                views: {
                    loading: true
                }
            }
        },
        created() {
            this.loadProjects()
        },
        methods: {
            loadProjects() {
                axios.get('/api/projects')
                .then(response => {
                    this.projects = response.data.data
                    
                    this.views.loading = false
                })
            },
            onGridReady(params) {
                this.gridApi = params.api
                this.gridColumnApi = params.gridColumnApi
                
                // this.gridApi.sizeColumnsToFit()
            },
            goTo(event) {
                this.$router.push({ name: 'Project', params: { id: event.data.id } })
            },
            dateFormatter(params) {
                return this.$options.filters.date(params.value);
            },
        },
        components: {
            Loader,
            AgGridVue
        }
    }
</script>

<style scoped>
    .table tr:hover {
        cursor: pointer;
    }
</style>