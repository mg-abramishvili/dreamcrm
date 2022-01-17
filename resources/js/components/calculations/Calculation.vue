<template>
    <div>
        <div class="row align-items-center mb-4">
            <div class="col-12 col-lg-6">
                <h1 class="h3 m-0">
                    Расчет №{{ calculation.id }} от {{moment(calculation.created_at).format('D MMMM YYYY')}}
                </h1>    
                <span v-if="calculation.user" class="text-sm">автор: {{ calculation.user.name }}</span>
            </div>
            <div class="col-12 col-lg-6 text-end">
                <!--<router-link :to="{name: 'CalculationEdit', params: {id: calculation.id}}" class="btn btn-warning">Изменить</router-link>-->
                <!-- <template v-if="calculation.offers && calculation.offers.length">
                    <div class="text-sm">У расчета есть КП: </div>
                    <template v-for="offer in calculation.offers">
                        <router-link :to="{name: 'OfferItem', params: {id: offer.id}}" class="btn btn-outline-primary">КП №{{offer.id}} от {{moment(offer.created_at).format('D MMMM YYYY')}}</router-link>
                    </template>
                </template>
                <template v-else>
                    <router-link :to="{name: 'OfferCreate', params: {calculation_id: calculation.id}}" class="btn btn-primary">Создать КП из расчета</router-link>
                </template> -->
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                
                
                <table class="table table-striped table-hover dataTable no-footer dtr-inline">
                    <tbody>
                        <tr v-for="box in calculation.boxes">
                            <td>Корпус</td>
                            <td>{{ box.name }}</td>
                            <td class="text-end">{{ box.price | currency }} ₽</td>
                        </tr>
                        <template v-for="element in calculation.elements">
                        <tr v-if="element.price > 0">
                            <td>{{ element.category.name }}</td>
                            <td>{{ element.name }}</td>
                            <td class="text-end">{{ element.price | currency }} ₽</td>
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
                                {{ priceWithQuantity | currency }} ₽
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                calculation: {},
                moment: moment,
            }
        },
        computed: {
            price() {
                if(this.calculation.boxes && this.calculation.boxes[0] && this.calculation.boxes[0].price && this.calculation && this.calculation.elements && this.calculation.elements.length > 0) {
                    if(this.calculation.delivery && this.calculation.delivery.length > 0) {
                        return parseInt(this.calculation.boxes[0].price) + this.calculation.elements.reduce((a, b) => a + parseInt(b.price), 0) + parseInt(this.calculation.delivery[0].pivot.price)
                    } else {
                        return parseInt(this.calculation.boxes[0].price) + this.calculation.elements.reduce((a, b) => a + parseInt(b.price), 0)
                    }
                }
            },
            priceWithQuantity() {
                if(this.price) {
                    return this.price * this.calculation.quantity
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
        }
    }
</script>