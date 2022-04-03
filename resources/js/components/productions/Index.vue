<template>
    <div class="calculations-page">
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

        <div v-if="!views.loading" class="card card-bordered">
            <div class="card-body p-0">
                <table class="table table-hover dataTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Название</th>
                            <th>Приоритет</th>
                            <th>Статус</th>
                            <th>Номер счета</th>
                            <th>Оплата</th>
                            <th>Корпус</th>
                            <th>Серийный номер</th>
                            <th>Дата начала</th>
                            <th>Дата завершения</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="production in productions" :key="production.id" @click="goTo(production.id)">
                            <td class="align-middle">
                                {{ production.id }}
                            </td>
                            <td class="align-middle">
                                {{ production.name }}
                            </td>
                            <td class="align-middle">
                                {{ production.priority }}
                            </td>
                            <td class="align-middle">
                                {{ production.status }}
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
                            <td class="align-middle">
                                {{ production.start_date }}
                            </td>
                            <td class="align-middle">
                                {{ production.end_date }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
    import Loader from '../Loader.vue'

    export default {
        data() {
            return {
                productions: [],

                views: {
                    loading: true,
                }
            }
        },
        created() {
            axios
                .get('/api/productions')
                .then(response => (
                    this.productions = response.data,
                    this.views.loading = false
                ))
        },
        methods: {
            goTo(id) {
                this.$router.push({ name: 'Production', params: { id: id } });
            }
        },
        components: {
            Loader
        }
    }
</script>

<style scoped>
    .table tr:hover {
        cursor: pointer;
    }
</style>