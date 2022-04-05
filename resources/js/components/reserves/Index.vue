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
                                Зарезервировано
                            </strong>
                        </h1>
                    </div>
                </div>
            </div>
        </div>

        <Loader v-if="views.loading"></Loader>

        <div v-if="!views.loading" class="card">
            <table class="table dataTable">
                <thead>
                    <tr>
                        <th>Наименование</th>
                        <th>Производство</th>
                        <th>Кол-во</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="reserve in reserves" :key="reserve.id">
                        <td class="align-middle">
                            <a>{{ reserve.stock_balance.stock_item.name }}</a>
                        </td>
                        <td class="align-middle">
                            {{ reserve.production_item.production.name }}
                        </td>
                        <td class="align-middle text-center">
                            {{ reserve.quantity }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
    import Loader from '../Loader.vue'

    export default {
        data() {
            return {
                reserves: [],

                views: {
                    loading: true,
                },
            }
        },
        created() {
            this.loadReserves()
        },
        methods: {
            loadReserves() {
                axios
                .get(`/api/reserves`)
                .then(response => {
                    this.reserves = response.data

                    this.views.loading = false
                })
            },
        },
        components: {
            Loader
        },
    }
</script>