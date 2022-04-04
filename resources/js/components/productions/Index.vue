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
                            <th class="text-center" style="width: 5%">ID</th>
                            <th style="width: 20%">Название</th>
                            <th class="text-center" style="width: 5%">Приоритет</th>
                            <th style="width: 10%">Статус</th>
                            <th style="width: 10%">Номер счета</th>
                            <th style="width: 10%">Оплата</th>
                            <th style="width: 10%">Корпус</th>
                            <th style="width: 10%">Серийный номер</th>
                            <!-- <th>Дата начала</th>
                            <th>Дата завершения</th> -->
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
                                    <span class="badge rounded-pill bg-info"></span>
                                </template>
                                <template v-if="production.priority == 'high'">
                                    <span class="badge rounded-pill bg-warning"></span>
                                </template>
                                <template v-if="production.priority == 'urgent'">
                                    <span class="badge rounded-pill bg-danger"></span>
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
                            <!-- <td class="align-middle">
                                {{ production.start_date }}
                            </td>
                            <td class="align-middle">
                                {{ production.end_date }}
                            </td> -->
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