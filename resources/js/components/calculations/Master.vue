<template>
    <Loader v-if="views.loading" />

    <div v-else class="calculations-page">
        <div class="card card-bordered">
            <div class="card-body">
                <h1 v-if="calculation.id" class="h3 m-0">Расчет №{{ calculation.id }}</h1>
                <h1 v-else class="h3 m-0">Новый расчет</h1>
            </div>
        </div>
        {{selected.catalogItems}}
        <div class="row">
            <div class="col-12 col-lg-5">
                <div style="position: sticky; top: 20px;">
                    <div class="card card-bordered">
                        <div class="card-body">
                            <ChooseType v-show="views.step == 'type'" :calculation="calculation"></ChooseType>

                            <ChooseBox v-show="views.step == 'box'" :type_id="selected.type.id" :calculation="calculation"></ChooseBox>

                            <ChooseCatalog v-show="views.step == 'catalog'" :box_id="selected.box.id"></ChooseCatalog>
                            
                            <ChooseQuantity v-show="views.step == 'quantity'"></ChooseQuantity>

                            <ChooseDelivery v-show="views.step == 'delivery'" :box="selected.box" :quantity="quantity" :selectedCatalogItems="selected.catalogItems"></ChooseDelivery>

                            <div class="total">
                                <div v-if="price && price > 0" class="row align-items-center mb-3">
                                    <div class="col-6"><strong>Цена за 1 ед:</strong></div>
                                    <div class="col-6 text-end text-primary" style="font-size: 26px; font-weight: bold;">
                                        {{ price | currency }} ₽ <small class="d-block fw-normal text-muted" style="font-size: 12px;">({{ pricePreRub | currency }}₽ + ${{ pricePreUsd | currency }})</small>
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
                
                <ChosenList :categories="categories" :selectedBox="selected.box" :selectedCatalogItems="selected.catalogItems"></ChosenList>
            </div>
        </div>

    </div>
</template>

<script>
    import Loader from '../Loader.vue'
    import ChooseType from './comps/ChooseType.vue'
    import ChooseBox from './comps/ChooseBox.vue'
    import ChooseCatalog from './comps/ChooseCatalog.vue'
    import ChooseQuantity from './comps/ChooseQuantity.vue'
    import ChooseDelivery from './comps/ChooseDelivery.vue'
    import ChosenList from './comps/ChosenList.vue'

    export default {
        data() {
            return {
                calculation: {},

                categories: [],
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
                    loading: true,
                    step: 'type',
                    saveButton: false,
                },
            }
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
                    return this.selected.box.pre_rub + this.selected.box.sborka + this.selected.box.marzha + price.reduce((a, b) => a + b, 0)
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
                    return this.selected.box.pre_usd + price.reduce((a, b) => a + b, 0)
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
                    return this.selected.box.price + price.reduce((a, b) => a + b, 0)
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
        created() {
            if(this.$route.params.id) {
                this.loadCalculation()
            } else {
                this.views.loading = false
            }
        },
        methods: {
            loadCalculation() {
                axios.get(`/api/calculation/${this.$route.params.id}`)
                .then(response => {
                    this.calculation = response.data.data
                    
                    response.data.data.catalog_items.forEach((item) => {
                        this.selected.catalogItems[item.category_slug] = []
                        this.selected.catalogItems[item.category_slug].push({
                            id: item.id,
                            price: item.price,
                            pre_rub: item.pre_rub,
                            pre_usd: item.pre_usd,
                        })
                    })

                    this.selected.delivery.name = response.data.data.delivery.name
                    this.selected.delivery.price = response.data.data.delivery.price

                    this.quantity = response.data.data.quantity

                    this.views.loading = false
                })
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
            ChooseQuantity,
            ChooseDelivery,
            ChosenList
        }
    }
</script>