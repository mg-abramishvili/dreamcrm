<template>
    <div class="calculations-page">
        <div class="card card-bordered">
            <div class="card-body">
                <h1 class="h3 m-0">Новый расчет</h1>
            </div>
        </div>
        
        <div class="row">
            <div class="col-12 col-lg-5">
                <div style="position: sticky; top: 20px;">
                    <div class="card card-bordered">
                        <div class="card-body">
                            <ChooseType v-show="views.step == 'type'"></ChooseType>

                            <ChooseBox v-show="views.step == 'box'" :type_id="selected.type.id"></ChooseBox>

                            <ChooseCatalog v-show="views.step == 'catalog'" :box_id="selected.box.id"></ChooseCatalog>

                            <div v-if="views.step == 'quantity'" class="mb-4">
                                <div class="calculation-left-block-main-label">
                                    <strong>Количество</strong>
                                </div>

                                <input @change="resetDelivery()" v-model="quantity" type="number" class="form-control">

                                <div class="mt-4">
                                    <button @click="viewCategories()" class="btn btn-outline-primary">Назад</button>
                                    <button @click="viewDelivery()" class="btn btn-outline-primary">Далее</button>
                                </div>
                            </div>

                            <div v-if="views.delivery" class="mb-4">
                                <div class="calculation-left-block-main-label">
                                    <strong>Доставка</strong>
                                </div>

                                <select @change="changeDelivery()" v-model="selected.delivery.id" class="form-select">
                                    <option v-for="delivery in deliveries" :key="'delivery_' + delivery.id" :value="delivery.id">{{ delivery.name }} &mdash; {{ delivery.price }}</option>
                                </select>

                                <DeliveryPEK v-if="selected.delivery.id == 3" :box="selected.box" :quantity="quantity" :delivery="selected.delivery"></DeliveryPEK>

                                <div class="mt-4">
                                    <button @click="viewQuantity()" class="btn btn-outline-primary">Назад</button>
                                    <button class="btn btn-outline-primary" disabled>Далее</button>
                                </div>
                            </div>

                            <div class="total">
                                <div v-if="price && price > 0" class="row align-items-center mb-3">
                                    <div class="col-6"><strong>Цена за 1 ед:</strong></div>
                                    <div class="col-6 text-end text-primary" style="font-size: 26px; font-weight: bold;">
                                        {{ price | currency }} ₽
                                    </div>
                                </div>
                                <div v-if="quantity > 1 && priceWithQuantity && priceWithQuantity > 0" class="row align-items-center mb-3">
                                    <div class="col-6">
                                        <strong>Цена за {{ quantity }} ед:</strong>
                                    </div>
                                    <div class="col-6 text-end text-primary" style="font-size: 26px; font-weight: bold;">{{ priceWithQuantity | currency }} ₽</div>
                                </div>
                                <div v-if="selected.delivery.name" class="row align-items-center mb-3">
                                    <div class="col-6">
                                        <strong>Доставка</strong> <br>
                                        <small style="line-height: 1.3; display: block; cursor: pointer;">
                                            {{ selected.delivery.name }} <br>
                                            <template v-if="selected.delivery.directionTo && selected.delivery.directionTo.length > 0">({{ selected.delivery.directionTo }}, {{ selected.delivery.days }} дн.)</template>
                                        </small>
                                    </div>
                                    <div v-if="selected.delivery.price" class="col-6 text-end text-primary" style="font-size: 26px; font-weight: bold;">{{ selected.delivery.price | currency }} ₽</div>
                                </div>
                                <div v-if="selected.delivery.name && selected.delivery.name.length > 0 && priceWithDelivery && priceWithDelivery > 0" class="row align-items-center">
                                    <div class="col-6"><strong>Итого</strong></div>
                                    <div class="col-6 text-end text-primary" style="font-size: 26px; font-weight: bold;">{{ priceWithDelivery | currency }} ₽</div>
                                </div>
                            </div>
                            <button v-if="views.saveButton" @click="saveCalculation()" class="btn btn-lg btn-primary w-100">Сохранить расчет</button>
                        </div>
                    </div>
                    <div v-if="selected.box && selected.box.id" class="card card-bordered mt-3">
                        <div class="card-body">
                            <p class="text-primary fw-bold">Примечание:</p>
                            {{ selected.box.manager_description }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-7">
                <div class="card card-bordered mb-2">
                    <div class="card-body py-2 px-3">
                        <small style="color: rgb(136, 136, 136);">Тип</small>
                        <div v-if="selected.type && selected.type.id > 0" class="row align-items-center">
                            <div class="col-12">
                                <strong class="d-block">{{ selected.type.name }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card card-bordered mb-2">
                    <div class="card-body py-2 px-3">
                        <small style="color: rgb(136, 136, 136);">Корпус</small>
                        <div v-if="selected.box && selected.box.id > 0" class="row align-items-center">
                            <div class="col-8">
                                <strong class="d-block">{{ selected.box.name }}</strong>
                            </div>
                            <div class="col-4 text-end">
                                <strong class="text-primary">{{ selected.box.price | currency }} ₽</strong>
                            </div>
                            <div class="col-12">
                                <ul class="calculation-stock-list">
                                    <li v-for="stockItem in selected.box.stock_items" :key="stockItem.id">
                                        <template v-if="stockItem.pivot.quantity > 1">
                                            {{ stockItem.pivot.quantity }} &times; 
                                        </template>
                                        {{ stockItem.name }}
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <ChosenList :categories="categories" :catalogItems="catalogItems" :selectedCatalogItems="selected.catalogItems"></ChosenList>
            </div>
        </div>

    </div>
</template>

<script>
    import Loader from '../Loader.vue'
    import ChooseType from './comps/ChooseType.vue'
    import ChooseBox from './comps/ChooseBox.vue'
    import ChooseCatalog from './comps/ChooseCatalog.vue'
    import DeliveryPEK from './comps/DeliveryPEK.vue'
    import ChosenList from './comps/ChosenList.vue'

    export default {
        data() {
            return {
                categories: [],
                catalogItems: [],
                deliveries: [],

                selected: {
                    type: {},
                    box: {},
                    catalogItems: {},
                    delivery: {
                        id: '',
                        name: '',
                        price: '',
                        to: '',
                        directionFrom: '',
                        directionTo: '',
                        days: '',
                    },
                },

                quantity: 1,

                views: {
                    step: 'type',
                    saveButton: false,
                },
            }
        },
        created() {
            this.loadDeliveries()
        },
        computed: {
            pricePreRub() {
                if(this.selected.box && this.selected.box.id > 0) {
                    var price = []
                    for (const category of Object.entries(this.selected.catalogItems)) {
                        category[1].forEach((el) => {
                            price.push(el.pre_rub)
                        })
                    }
                    return parseInt(this.selected.box.pre_rub) + parseInt(this.selected.box.sborka) + parseInt(this.selected.box.marzha) + price.reduce((a, b) => a + b, 0)
                }
            },
            pricePreUsd() {
                if(this.selected.box && this.selected.box.id > 0) {
                    var price = []
                    for (const category of Object.entries(this.selected.catalogItems)) {
                        category[1].forEach((el) => {
                            price.push(el.pre_usd)
                        })
                    }
                    return parseInt(this.selected.box.pre_usd) + price.reduce((a, b) => a + b, 0)
                }
            },
            price() {
                if(this.selected.box && this.selected.box.id > 0) {
                    var price = []
                    for (const category of Object.entries(this.selected.catalogItems)) {
                        category[1].forEach((el) => {
                            price.push(el.price)
                        })
                    }
                    return parseInt(this.selected.box.price) + price.reduce((a, b) => a + b, 0)
                }
            },
            priceWithQuantity() {
                return this.price * this.quantity
            },
            priceWithDelivery() {
                if(this.selected.delivery.price) {
                    return this.priceWithQuantity + parseInt(this.selected.delivery.price)
                }
            },
        },
        methods: {
            loadDeliveries() {
                axios
                .get('/api/calculation/deliveries')
                .then((response => {
                    this.deliveries = response.data
                }));
            },
            
            resetCatalogItems() {
                for (const category of Object.entries(this.selected.catalogItems)) {
                    if(category[1] && category[1].length > 0) {
                        category[1].forEach((i) => {
                            i.id = null,
                            i.price = 0,
                            i.pre_rub = 0,
                            i.pre_usd = 0
                        })
                    }
                }
                this.quantity = 1
                this.resetDelivery()
            },
            
            
            
            resetDelivery() {
                this.selected.delivery.id = ''
                this.selected.delivery.name = ''
                this.selected.delivery.price = ''
                this.selected.delivery.to = ''
                this.selected.delivery.directionFrom = ''
                this.selected.delivery.directionTo = ''
                this.selected.delivery.days = ''
            },
            changeDelivery() {
                var delivery = this.deliveries.find(delivery => delivery.id === this.selected.delivery.id)
                
                this.selected.delivery.name = delivery.name
                this.selected.delivery.price = delivery.price
                this.selected.delivery.to = ''
                this.selected.delivery.directionFrom = ''
                this.selected.delivery.directionTo = ''
                this.selected.delivery.days = ''
            },
            goToStep(step) {
               this.views.step = step 
            },
            saveCalculation() {
                if(!this.selected.delivery.id) {
                    alert('Выберите доставку')
                    return
                }

                if(this.selected.delivery.id == 3 && this.selected.delivery.price <= 0) {
                    alert('Укажите город доставки')
                    return
                }

                axios
                .post(`/api/calculations`, {
                    type: this.selected.type.id,
                    box: this.selected.box,
                    catalog_items: this.selected.catalogItems,
                    quantity: this.quantity,
                    price: this.price,
                    delivery: this.selected.delivery,
                    user: this.$parent.user.id,
                })
                .then(response => (
                    this.$router.push({name: 'Calculations'})
                ))
            }
        },
        components: {
            Loader,
            ChooseType,
            ChooseBox,
            ChooseCatalog,
            DeliveryPEK,
            ChosenList
        }
    }
</script>