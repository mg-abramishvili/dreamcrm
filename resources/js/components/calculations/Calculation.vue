<template>
    <div class="calculations-page">
        <div class="card card-bordered">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-12 col-lg-6">
                        <h1 class="h3 m-0">
                            <router-link :to="{name: 'Calculations'}" class="back-arrow">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left align-middle me-2"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                            </router-link>
                            <strong>
                                Расчет №{{ calculation.id }} от {{moment(calculation.created_at).utcOffset(180).format('DD.MM.YYYY HH:mm')}}
                                <!-- <span v-if="calculation.user" class="text-sm">автор: {{ calculation.user.name }}</span> -->
                            </strong>
                        </h1>    
                    </div>
                    <div v-if="calculation && calculation.id > 0" class="col-12 col-lg-6 text-end">
                        <template v-if="calculation.project">
                            <router-link :to="{name: 'Project', params: {id: calculation.project.id}}" class="btn btn-primary">Перейти к проекту</router-link>
                        </template>
                        <template v-else>
                            <router-link :to="{name: 'ProjectCreate', params: {calculation_id: calculation.id}}" class="btn btn-primary">Создать проект</router-link>
                        </template>
                    </div>
                </div>
            </div>
        </div>

        <div v-if="calculation && calculation.id > 0" class="card">
            <div class="card-body">
                <table class="table dataTable">
                    <tbody>
                        <tr v-for="box in calculation.boxes">
                            <td>Корпус</td>
                            <td>{{ box.name }}</td>
                            <td class="text-end">{{ box.pivot.price | currency }} ₽</td>
                        </tr>
                        <template v-for="item in calculation.catalog_items">
                        <tr v-if="item.pivot.price > 0">
                            <td>{{ item.category.name }}</td>
                            <td>{{ item.name }}</td>
                            <td class="text-end">{{ item.pivot.price | currency }} ₽</td>
                        </tr>
                        </template>
                        <tr style="border-width:2px;">
                            <td></td>
                            <td class="text-end">
                                Стоимость за ед.
                            </td>
                            <td class="text-end">
                                {{ price | currency }} ₽
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
                        <tr v-if="calculation.delivery && calculation.delivery.length > 0" class="m-0">
                            <td></td>
                            <td class="text-end">
                                Доставка {{ calculation.delivery[0].name }}
                            </td>
                            <td class="text-end">
                                {{ calculation.delivery[0].pivot.price | currency }} ₽
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td class="text-end">
                                Итого
                            </td>
                            <td class="text-end text-primary" style="font-weight: 700;">
                                {{ priceTotal | currency }} ₽
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <Loader v-else></Loader>
    </div>
</template>

<script>
    import Loader from '../Loader.vue'

    export default {
        data() {
            return {
                calculation: {},
                moment: moment,
            }
        },
        computed: {
            price() {
                if(this.calculation.boxes && this.calculation.boxes[0] && this.calculation.boxes[0].price && this.calculation && this.calculation.catalog_items && this.calculation.catalog_items.length > 0) {
                    return parseInt(this.calculation.boxes[0].pivot.price) + this.calculation.catalog_items.reduce((a, b) => a + parseInt(b.pivot.price), 0)
                }
            },
            priceTotal() {
                if(this.calculation.delivery && this.calculation.delivery.length > 0) {
                    if(this.price) {
                        return (this.price * this.calculation.quantity) + parseInt(this.calculation.delivery[0].pivot.price)
                    }
                } else {
                    if(this.price) {
                        return this.price * this.calculation.quantity
                    }
                }
            },
        },
        created() {
            axios
                .get(`/api/calculation/${this.$route.params.id}`)
                .then(response => (
                    this.calculation = response.data
                ));
        },
        components: {
            Loader
        }
    }
</script>