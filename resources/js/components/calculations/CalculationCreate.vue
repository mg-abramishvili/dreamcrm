<template>
    <div style="position: relative;">
        <h1 class="h3 m-0 mb-4">Новый расчет</h1>
        
        <div v-if="elementsFiltered && elementsFiltered.length > 0" class="row">
            <div class="col-12 col-lg-5">
                <div class="calculation-left-block bg-white px-4 py-4" style="position: sticky; top: 20px;">
                    
                    <div v-if="views.boxes" class="mb-4">
                        <div class="calculation-left-block-main-label">
                            <strong>Корпус</strong>
                        </div>

                        <select @change="changeBox()" v-model="selected.box" class="form-select form-select-lg mt-2 mb-3">
                            <option v-for="box in boxes" :key="'box_' + box.id" :value="box">{{ box.name }} &mdash; {{ box.price | currency }} ₽</option>
                        </select>

                        <div class="mt-4">
                            <button class="btn btn-outline-primary" disabled>Назад</button>
                            <button @click="viewCategories()" class="btn btn-outline-primary">Далее</button>
                        </div>
                    </div>

                    <div v-if="views.categories" class="mb-4">
                        <div v-for="category in categories" :key="'category_' + category.id">
                            <div v-show="views.categoryCurrent == category.id">
                                <div class="calculation-left-block-main-label">
                                    <strong>{{ category.name }}</strong>
                                    <button @click="addElement(category.slug)" class="btn btn-sm btn-outline-danger">+</button>
                                </div>
                                
                                <div v-for="(element, index) in selected.elements[category.slug]" :key="index" style="position: relative;">
                                    <select v-model="element.id" class="form-select form-select-lg mt-2 mb-3">
                                        <template v-for="element in elementsFiltered">
                                            <option v-if="element.category_id == category.id" :key="'element_' + element.id" :value="element.id">
                                                {{ element.name }} <template v-if="element.price > 0">&mdash; {{ element.price | currency }} ₽</template>
                                            </option>
                                        </template>
                                    </select>
                                    <button v-if="index > 0 && selected.elements[category.slug].length > 1" @click="deleteElement(element.id, category.slug)" class="btn btn-sm btn-outline-danger" style="position: absolute; right: 0; top: 50%; transform: translateY(-50%); padding: 0; width: 20px; margin-right: -21px;">&ndash;</button>
                                </div>

                                <div class="mt-4">
                                    <button @click="prevCategory(category)" class="btn btn-outline-primary">Назад</button>
                                    <button @click="nextCategory(category)" class="btn btn-outline-primary">Далее</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div v-if="views.quantity" class="mb-4">
                        <div class="calculation-left-block-main-label">
                            <strong>Количество</strong>
                        </div>

                        <input @change="resetDelivery()" v-model="quantity" type="number" class="form-control">

                        <div class="mt-4">
                            <button @click="viewCategories()" class="btn btn-outline-primary">Назад</button>
                            <button @click="viewDelivery()" class="btn btn-outline-primary">Далее</button>
                        </div>
                    </div>

                    <div v-show="views.delivery == true" class="mb-4">
                        <div class="calculation-left-block-main-label">
                            <strong>Доставка</strong>
                        </div>

                        <select @change="changeDelivery()" v-model="selected.delivery.id" class="form-select">
                            <option v-for="delivery in deliveries" :key="'delivery_' + delivery.id" :value="delivery.id">{{ delivery.name }} &mdash; {{ delivery.price }}</option>
                        </select>

                        <DeliveryPEK v-if="selected.delivery.id == 3" :box="selected.box" :quantity="quantity"></DeliveryPEK>

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
                                <small style="display:block; line-height: 1; font-size: 15px; font-weight: 400; color: #777;">{{ pricePreRub | currency }} ₽ / {{ pricePreUsd | currency }} $</small>
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
                                    <template v-if="selected.delivery.direction && selected.delivery.direction.length > 0">({{ selected.delivery.direction }}, {{ selected.delivery.days }} дн.)</template>
                                </small>
                            </div>
                            <div v-if="selected.delivery.price" class="col-6 text-end text-primary" style="font-size: 26px; font-weight: bold;">{{ selected.delivery.price | currency }} ₽</div>
                        </div>
                        <div v-if="selected.delivery.name && selected.delivery.name.length > 0 && priceWithDelivery && priceWithDelivery > 0" class="row align-items-center">
                            <div class="col-6"><strong>Итого</strong></div>
                            <div class="col-6 text-end text-primary" style="font-size: 26px; font-weight: bold;">{{ priceWithDelivery | currency }} ₽</div>
                        </div>
                    </div>
                    <button @click="saveCalculation()" class="btn btn-primary">Сохранить расчет</button>
                </div>
            </div>
            <div class="col-12 col-lg-7">
                <div class="mb-3 bg-white px-3 py-3">
                    <small style="color: rgb(136, 136, 136);">Корпус</small>
                    <div v-if="selected.box && selected.box.id > 0" class="row align-items-center">
                        <div class="col-8">
                            <strong class="d-block">{{ selected.box.name }}</strong>
                        </div>
                        <div class="col-4 text-end">
                            <strong class="text-primary">{{ selected.box.price | currency }} ₽</strong>
                        </div>
                    </div>
                </div>

                <div v-for="category in categories" :key="'category_' + category.id" class="mb-3 bg-white px-3 py-3">
                    <small style="color: rgb(136, 136, 136);">{{ category.name }}</small>
                    <div v-for="element in elementsFilteredForCategory(category)" class="row align-items-center">
                        <div class="col-8">
                            <strong class="d-block">{{ element.name }}</strong>
                        </div>
                        <div class="col-4 text-end">
                            <strong class="text-primary">{{ element.price | currency }} ₽</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div v-else style="position: absolute; left: 0; right: 0; top: 0; bottom: 0; background-color: rgba(255,255,255,0.8); z-index: 10;">
            <div class="spinner-border text-primary me-2" role="status" style="position: absolute; left: 50%; top: 30px;">
                <span class="sr-only">Загрузка...</span>
            </div>
        </div>

    </div>
</template>

<script>
    import DeliveryPEK from './CalculationDeliveryPEK.vue'

    export default {
        data() {
            return {
                boxes: [],
                categories: [],
                elements: [],
                deliveries: [],

                selected: {
                    box: {},
                    elements: {},
                    delivery: {
                        id: '',
                        name: '',
                        price: '',
                        direction: '',
                        days: '',
                    },
                },

                quantity: 1,

                views: {
                    boxes: true,
                    categories: false,
                    categoryCurrent: '',
                    quantity: false,
                    delivery: false,
                },
            }
        },
        created() {
            this.loadBoxes()
            this.loadCategories()
            this.loadElements()
            this.loadDeliveries()
        },
        mounted () {
        },
        watch: {
            selected: {
                deep: true,
                handler() {
                    for (const category of Object.entries(this.selected.elements)) {
                        if(category[1] && category[1].length > 0) {
                            category[1].forEach((el) => {
                                if(el.id != null) {
                                    el.price = parseInt(this.elements.filter(element => element.id == el.id)[0].price)
                                    el.pre_rub = parseInt(this.elements.filter(element => element.id == el.id)[0].pre_rub)
                                    el.pre_usd = parseInt(this.elements.filter(element => element.id == el.id)[0].pre_usd)
                                }
                            })
                        }
                    }
                }
            }
        },
        computed: {
            elementsFiltered() {
                if(this.selected.box && this.selected.box.id > 0) {
                    return this.elements.filter(element => element.boxes.some(box => box.id === this.selected.box.id))
                } else {
                    return this.elements
                }
            },
            pricePreRub() {
                var price = []
                for (const category of Object.entries(this.selected.elements)) {
                    category[1].forEach((el) => {
                        price.push(el.pre_rub)
                    })
                }
                return parseInt(this.selected.box.pre_rub) + parseInt(this.selected.box.sborka) + parseInt(this.selected.box.marzha) + price.reduce((a, b) => a + b, 0)
            },
            pricePreUsd() {
                var price = []
                for (const category of Object.entries(this.selected.elements)) {
                    category[1].forEach((el) => {
                        price.push(el.pre_usd)
                    })
                }
                return parseInt(this.selected.box.pre_usd) + price.reduce((a, b) => a + b, 0)
            },
            price() {
                var price = []
                for (const category of Object.entries(this.selected.elements)) {
                    category[1].forEach((el) => {
                        price.push(el.price)
                    })
                }
                return parseInt(this.selected.box.price) + price.reduce((a, b) => a + b, 0)
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
            loadBoxes() {
                axios
                .get('/api/boxes')
                .then((response => {
                    this.boxes = response.data
                }))
            },
            loadCategories() {
                axios
                .get('/api/categories')
                .then((response => {
                    this.categories = response.data

                    response.data.forEach(category => {
                        this.$set(this.selected.elements, category.slug, [])
                        this.addElement(category.slug)
                    })

                    this.views.categoryCurrent = response.data[0].id
                }))
            },
            loadElements() {
                axios
                .get('/api/elements')
                .then((response => {
                    this.elements = response.data
                }))
            },
            loadDeliveries() {
                axios
                .get('/api/deliveries')
                .then((response => {
                    this.deliveries = response.data
                }));
            },
            elementsFilteredForCategory(category) {
                var categoryElements = this.elements.filter(element => element.category_id == category.id)
                var categoryElementsSelected = this.selected.elements[category.slug]
                var categoryElementsFiltered = []

                categoryElementsSelected.forEach((elementSelected) => {
                    if(categoryElements.find(element => element.id === elementSelected.id)) {
                        categoryElementsFiltered.push(categoryElements.find(element => element.id === elementSelected.id))
                    }
                })

                return categoryElementsFiltered
            },
            viewBoxes() {
                this.views.boxes = true
                this.views.categories = false
                this.views.quantity = false
                this.views.delivery = false
            },
            viewCategories() {
                if(this.selected.box && this.selected.box.id > 0 && this.elementsFiltered.length > 0) {
                    this.views.boxes = false
                    this.views.categories = true
                    this.views.quantity = false
                    this.views.delivery = false

                    this.selected.elements[this.categories[0].slug][0].id = this.elementsFiltered.filter(element => element.category_id == this.categories[0].id)[0].id
                } else {
                    alert('Выберите корпус!')
                }
            },
            viewQuantity() {
                this.views.boxes = false
                this.views.categories = false
                this.views.quantity = true
                this.views.delivery = false
            },
            viewDelivery() {
                this.views.boxes = false
                this.views.categories = false
                this.views.quantity = false
                this.views.delivery = true
            },
            addElement(categorySlug) {
                let checkEmpty = this.selected.elements[categorySlug].filter(element => element.id === null)
                if (checkEmpty.length >= 1 && this.selected.elements[categorySlug].length > 0) {
                    return
                }
                this.selected.elements[categorySlug].push({
                    id: null,
                    price: 0,
                    pre_rub: 0,
                    pre_usd: 0
                })
            },
            deleteElement(ElementID, categorySlug) {
                var index = this.selected.elements[categorySlug].map(element => { return element.id }).indexOf(ElementID)
                this.selected.elements[categorySlug].splice(index, 1)
            },
            prevCategory(category) {
                var index = this.categories.indexOf(category)
                if(index > 0 && index < this.categories.length + 1) {
                    this.views.categoryCurrent = this.categories[index - 1].id
                } else {
                    this.viewBoxes()
                }
            },
            nextCategory(category) {
                var index = this.categories.indexOf(category)
                var nextCategory = this.categories[index + 1]

                if(index >= 0 && index < this.categories.length - 1 && nextCategory.elements && nextCategory.elements.length > 0) {
                    this.views.categoryCurrent = nextCategory.id
                    this.selected.elements[nextCategory.slug][0].id = this.elementsFiltered.filter(element => element.category_id == nextCategory.id)[0].id
                } else {
                    this.viewQuantity()
                }
            },
            changeBox() {
                for (const category of Object.entries(this.selected.elements)) {
                    if(category[1] && category[1].length > 0) {
                        category[1].forEach((el) => {
                            el.id = null,
                            el.price = 0,
                            el.pre_rub = 0,
                            el.pre_usd = 0
                        })
                    }
                }
                this.quantity = 1
                this.resetDelivery()
            },
            changeDelivery() {
                var delivery = this.deliveries.find(delivery => delivery.id === this.selected.delivery.id)
                
                this.selected.delivery.name = delivery.name
                this.selected.delivery.price = delivery.price
                this.selected.delivery.direction = ''
                this.selected.delivery.days = ''
            },
            resetDelivery() {
                this.selected.delivery.id = ''
                this.selected.delivery.name = ''
                this.selected.delivery.price = ''
                this.selected.delivery.direction = ''
                this.selected.delivery.days = ''
            },
            saveCalculation() {
                 axios
                .post(`/api/calculations`, {
                    price: this.price,
                    box: this.selected.box,
                    elements: this.selected.elements,
                    quantity: this.quantity
                })
                .then(response => (
                    console.log(response)
                ))
            }
        },
        filters: {
            currency(value) {
                return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, " ") 
            }
        },
        components: {
            DeliveryPEK
        }
    }
</script>