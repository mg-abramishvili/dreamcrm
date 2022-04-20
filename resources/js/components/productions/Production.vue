<template>
    <div class="projects-page">
        <div class="card card-bordered">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-12">
                        <h1 class="h3 m-0">
                            <router-link :to="{name: 'Productions'}" class="back-arrow">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left align-middle me-2"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                            </router-link>
                            <strong>
                                {{ production.name }}
                            </strong>
                        </h1>    
                    </div>
                </div>
            </div>
        </div>

        <Loader v-if="views.loading"></Loader>

        <div v-if="!views.loading" class="tab">
            <ul class="nav nav-tabs" role="tablist">
                <li class="nav-item">
                    <a @click="selectTab('general')" class="nav-link" :class="{'active': selected.tab == 'general'}" role="tab">Общая информация</a>
                </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane" :class="{'active': selected.tab == 'general'}" role="tabpanel">
                    <div class="row">
                        <div class="col-12 col-lg-6">
                            <p>
                                <strong>Статус:</strong>
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
                            </p>

                            <p>
                                <strong>Приоритет:</strong>
                                <template v-if="production.priority == 'normal'">
                                    <span class="badge rounded-pill bg-info"></span>
                                    Нормальный
                                </template>
                                <template v-if="production.priority == 'high'">
                                    <span class="badge rounded-pill bg-warning"></span>
                                    Высокий
                                </template>
                                <template v-if="production.priority == 'urgent'">
                                    <span class="badge rounded-pill bg-danger"></span>
                                    Срочный
                                </template>
                            </p>

                            <p v-if="production.user">
                                <strong>Пользователь:</strong>
                                {{ production.user.name }}
                            </p>

                            <p v-if="production.ral">
                                <strong>Цвет (RAL):</strong>
                                {{ production.ral }}
                            </p>

                            <p v-if="production.payment_type">
                                <strong>Оплата:</strong>
                                {{ production.payment_type }}
                            </p>

                            <p v-if="production.supply_info">
                                <strong>Данные для доставки:</strong>
                                {{ production.supply_info }}
                            </p>

                            <p v-if="production.invoice_number">
                                <strong>Номер счета:</strong>
                                {{ production.invoice_number }}
                            </p>

                            <p v-if="production.additional_hardware">
                                <strong>Доп. оборудование:</strong>
                                {{ production.additional_hardware }}
                            </p>

                            <p v-if="production.additional_for_tribune">
                                <strong>Доп. для трибун:</strong>
                                {{ production.additional_for_tribune }}
                            </p>
                        </div>
                        <div class="col-12 col-lg-6">
                            <p v-if="production.start_date">
                                <strong>Дата начала:</strong>
                                {{ production.start_date | date }}
                            </p>
                            <p v-if="production.end_date">
                                <strong>Срок завершения:</strong>
                                {{ production.end_date | date }}
                            </p>
                            <p v-if="production.contacts">
                                <strong>Контакты:</strong>
                                {{ production.contacts }}
                            </p>
                            <p v-if="production.email">
                                <strong>E-mail:</strong>
                                {{ production.email }}
                            </p>
                            <p v-if="production.serial_number">
                                <strong>Серийный номер:</strong>
                                {{ production.serial_number }}
                            </p>
                            <p v-if="production.activation_key">
                                <strong>Ключ активации:</strong>
                                {{ production.activation_key }}
                            </p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <hr>
                            <div v-if="production.description" v-html="production.description"></div>

                            <table class="table dataTable">
                                <tbody>
                                    <tr v-for="item in production.items">
                                        <td style="width: 50%;">
                                            {{ item.stock_item.name }}
                                        </td>
                                        <td style="width: 50%;">
                                            <ul class="m-0">
                                                <li v-for="reserve in item.reserves" class="my-2" style="line-height: 1;">
                                                    {{ reserve.quantity }} шт из склада <span class="text-muted">{{ reserve.price_total | currency }}₽</span><br>
                                                    <small class="text-muted">Поступление от {{ reserve.stock_balance.date | date }} / {{ reserve.price | currency }}₽ ({{ reserve.pre_rub | currency }}₽ + ${{ reserve.pre_usd | currency }})</small>
                                                </li>
                                                <li v-for="need in item.stock_needs" class="text-danger my-2" style="line-height: 1;">
                                                    не хватает {{ need.quantity }} шт
                                                </li>
                                            </ul>
                                            <button @click="delItem(item.id)">удалить</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Loader from '../Loader.vue'

    export default {
        data() {
            return {
                production: {},

                selected: {
                    tab: 'general',
                },

                views: {
                    loading: true,
                }
            }
        },
        created() {
            this.loadProduction()
        },
        methods: {
            loadProduction() {
                axios
                .get(`/api/production/${this.$route.params.id}`)
                .then(response => {
                    this.production = response.data

                    this.views.loading = false
                })
            },
            selectTab(tab) {
                this.selected.tab = tab
            },
            delItem(id) {
                axios.delete(`/api/production-item/${id}`)
                .then(response => {
                    this.loadProduction()
                })
            },
        },
        components: {
            Loader,
        }
    }
</script>