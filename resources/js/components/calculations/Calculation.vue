<template>
    <div class="calculations-page">
        <Loader v-if="views.loading"></Loader>

        <div v-if="!views.loading" class="card card-bordered">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-12 col-lg-6">
                        <h1 class="h3 m-0">
                            <router-link :to="{name: 'Calculations'}" class="back-arrow">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left align-middle me-2"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                            </router-link>
                            <strong>
                                Расчет №{{ calculation.id }} от {{ calculation.created_at | date }}
                            </strong>
                        </h1>    
                    </div>
                    <div class="col-12 col-lg-6 text-end">
                        <template v-if="calculation.project_id">
                            <router-link :to="{name: 'Project', params: {id: calculation.project_id}}" class="btn btn-primary">Перейти к проекту</router-link>
                        </template>
                        <template v-else>
                            <router-link :to="{name: 'ProjectCreate', params: {calculation_id: calculation.id}}" class="btn btn-primary">Создать проект</router-link>
                        </template>
                    </div>
                </div>
            </div>
        </div>

        <div v-if="!views.loading" class="card">
            <div class="card-body">
                <table class="table dataTable">
                    <tbody>
                        <tr>
                            <td>Корпус</td>
                            <td>
                                <strong>{{ calculation.box.name }}</strong>
                                <ul class="calculation-stock-list">
                                    <li v-for="stockItem in calculation.box.stock_items" :key="stockItem.id">
                                        <template v-if="stockItem.quantity > 1">
                                            {{ stockItem.quantity }} &times; 
                                        </template>
                                        {{ stockItem.name }}
                                    </li>
                                </ul>
                            </td>
                            <td class="text-end">{{ calculation.box.price | currency }} ₽</td>
                        </tr>
                        <template v-for="item in calculation.catalog_items">
                            <tr v-if="item.price > 0">
                                <td>{{ item.category }}</td>
                                <td>
                                    <strong>{{ item.name }}</strong>
                                    <ul class="calculation-stock-list">
                                        <li v-for="stockItem in item.stock_items" :key="stockItem.id">
                                            <template v-if="stockItem.quantity > 1">
                                                {{ stockItem.quantity }} &times; 
                                            </template>
                                            {{ stockItem.name }}
                                        </li>
                                    </ul>
                                </td>
                                <td class="text-end">{{ item.price | currency }} ₽</td>
                            </tr>
                        </template>
                        <tr style="border-width:2px;">
                            <td></td>
                            <td class="text-end">
                                Стоимость за ед.
                            </td>
                            <td class="text-end">
                                {{ price | currency }} ₽
                                <span class="text-muted">({{ preRub | currency }}₽ + ${{ preUsd | currency }})</span>
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td class="text-end">
                                Кол-во
                            </td>
                            <td class="text-end">
                                {{ calculation.quantity }} шт.
                            </td>
                        </tr>
                        <tr v-if="calculation.delivery" class="m-0">
                            <td></td>
                            <td class="text-end">
                                Доставка {{ calculation.delivery.name }}
                            </td>
                            <td class="text-end">
                                {{ calculation.delivery.price | currency }} ₽
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td class="text-end">
                                Итого
                            </td>
                            <td class="text-end text-primary" style="font-weight: 700;">
                                {{ priceWithDelivery | currency }} ₽
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
                calculation: {},

                views: {
                    loading: true,
                }
            }
        },
        computed: {
            preUsd() {
                let boxUsdPrice = this.calculation.box.pre_usd
                let catalogItemsUsdPrice = this.calculation.catalog_items.reduce((a, b) => a + parseInt(b.pre_usd), 0)

                return boxUsdPrice + catalogItemsUsdPrice
            },
            preRub() {
                let boxRubPrice = this.calculation.box.pre_rub
                let catalogItemsRubPrice = this.calculation.catalog_items.reduce((a, b) => a + parseInt(b.pre_rub), 0)

                return boxRubPrice + catalogItemsRubPrice
            },
            price() {
                let boxPrice = this.calculation.box.pre_rub
                let catalogItemsPrice = this.calculation.catalog_items.reduce((a, b) => a + parseInt(b.price), 0)

                return boxPrice + catalogItemsPrice
            },
            priceWithQuantity() {
                return this.price * this.calculation.quantity
            },
            priceWithDelivery() {
                if(this.calculation.delivery && this.calculation.delivery.price) {
                    return this.priceWithQuantity + this.calculation.delivery.price
                } else {
                    return this.priceWithQuantity
                }
            },
        },
        created() {
            this.loadCalculation()
        },
        methods: {
            loadCalculation() {
                axios.get(`/api/calculation/${this.$route.params.id}`)
                .then(response => {
                    this.calculation = response.data.data

                    this.views.loading = false
                })
            }
        },
        components: {
            Loader
        }
    }
</script>