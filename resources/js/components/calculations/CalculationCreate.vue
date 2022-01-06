<template>
    <div style="position: relative;">
        <h1 class="h3 m-0 mb-4">Новый расчет</h1>
        
        <div v-if="elementsFiltered && elementsFiltered.length > 0" class="row">
            <div class="col-12 col-lg-5">
                <div class="calculation-input bg-white px-4 py-4" style="position: sticky; top: 20px;">
                    <div v-if="views.boxes" class="mb-4">
                        <label><strong>Корпус</strong></label>

                        <select @change="changeBox()" v-model="selected.box" class="form-select form-select-lg mt-2 mb-3">
                            <option v-for="box in boxes" :key="'box_' + box.id" :value="box">{{ box.name }} &mdash; {{ box.price | currency }} ₽</option>
                        </select>

                        <div class="mt-4">
                            <button class="btn btn-outline-primary" disabled>Назад</button>
                            <button @click="activateViewsCategories()" class="btn btn-outline-primary">Далее</button>
                        </div>
                    </div>

                    <div v-if="views.categories" class="mb-4">
                        <div v-for="category in categories" :key="'category_' + category.id">
                            <div v-show="views.category_current == category.id">
                                <label><strong>{{ category.name }}</strong></label>

                                <button @click="addElement(category.slug)" class="btn btn-sm btn-outline-danger">+</button>
                                
                                <div v-for="(element, index) in selected.elements[category.slug]" :key="index" style="position: relative;">
                                    <select v-model="element.id" class="form-select form-select-lg mt-2 mb-3">
                                        <template v-for="element in elementsFiltered">
                                            <option v-if="element.category_id == category.id" :value="element.id">
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
                    
                    <div class="total">
                        <div v-if="price && price > 0" class="row align-items-center mb-3">
                            <div class="col-6"><strong>Цена за 1 ед:</strong></div>
                            <div class="col-6 text-end text-primary" style="font-size: 26px; font-weight: bold;">
                                {{ price | currency }} ₽
                                <small style="display:block; line-height: 1; font-size: 15px; font-weight: 400; color: #777;">{{ price_pre_rub | currency }} ₽ / {{ price_pre_usd | currency }} $</small>
                            </div>
                        </div>
                        <div v-if="views.quantity" class="row align-items-center mb-3">
                            <div class="col-6">
                                <strong>Кол-во</strong>
                                <input @change="resetDelivery()" v-model="quantity" type="number" class="form-control form-control-sm" style="font-size: 12px; display:inline-block; width: 70px;">
                            </div>
                            <div v-if="priceWithQuantity && priceWithQuantity > 0" class="col-6 text-end text-primary" style="font-size: 26px; font-weight: bold;">{{ priceWithQuantity | currency }} ₽</div>
                        </div>
                        <div v-if="views.delivery" class="row align-items-center mb-3">
                            <div class="col-6">
                                <strong>Доставка</strong> <br>
                                <small @click="openDeliveryModal()" style="line-height: 1.3; display: block; cursor: pointer;">
                                    {{ delivery.name }} <br>
                                    <template v-if="delivery.direction && delivery.direction.length > 0">({{ delivery.direction }}, {{ delivery.days }} дн.)</template>
                                </small>
                            </div>
                            <div class="col-6 text-end text-primary" style="font-size: 26px; font-weight: bold;">{{ delivery.price | currency }} ₽</div>
                        </div>
                        <div v-if="delivery.name && delivery.name.length > 0 && priceWithDelivery && priceWithDelivery > 0" class="row align-items-center">
                            <div class="col-6"><strong>Итого</strong></div>
                            <div class="col-6 text-end text-primary" style="font-size: 26px; font-weight: bold;">{{ priceWithDelivery | currency }} ₽</div>
                        </div>
                    </div>                    
                </div>
            </div>
            <div class="col-12 col-lg-7">
                <!-- <ul>
                    <li v-for="(element, categorySlug) in inputElements" :key="categorySlug"><strong>{{ categorySlug }}:</strong> {{ element }}</li>
                </ul> -->
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
                    <template v-for="element in elementsFiltered">
                        <template v-if="element.category_id == category.id">
                            <template v-for="inEl in selected.elements">
                                <template v-for="inElEl in inEl">
                                    <div v-if="element.id == inElEl.id" class="row align-items-center">
                                        <div class="col-8">
                                            <strong class="d-block">{{ element.name }}</strong>
                                        </div>
                                        <div class="col-4 text-end">
                                            <strong class="text-primary">{{ element.price | currency }} ₽</strong>
                                        </div>
                                    </div>
                                </template>
                            </template>
                        </template>
                    </template>
                </div>
            </div>
        </div>
        <div v-else style="position: absolute; left: 0; right: 0; top: 0; bottom: 0; background-color: rgba(255,255,255,0.8); z-index: 10;">
            <div class="spinner-border text-primary me-2" role="status" style="position: absolute; left: 50%; top: 30px;">
                <span class="sr-only">Загрузка...</span>
            </div>
        </div>
        
        <DeliveryModal v-if="views.modals.delivery" v-bind:box="selected.box"></DeliveryModal>
    </div>
</template>

<script>
    import DeliveryModal from './CalculationDelivery.vue'

    export default {
        data() {
            return {
                boxes: [],
                categories: [],
                elements: [],

                selected: {
                    box: {},
                    elements: {},
                },

                quantity: 1,

                delivery: {
                    id: 0,
                    name: '',
                    price: 0,
                    direction: '',
                    days: 0,
                },

                views: {
                    boxes: true,
                    categories: false,
                    category_current: '',
                    quantity: false,
                    delivery: false,

                    modals: {
                        delivery: false,
                    },
                },
            }
        },
        created() {
            this.loadBoxes()
            this.loadCategories()
            this.loadElements()
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
            price_pre_rub() {
                var price = []
                for (const category of Object.entries(this.selected.elements)) {
                    if(category[1] && category[1].length > 0) {
                        category[1].forEach((el) => {
                            price.push(el.pre_rub)
                        })
                    }
                }
                return parseInt(this.selected.box.pre_rub) + parseInt(this.selected.box.sborka) + parseInt(this.selected.box.marzha) + price.reduce((a, b) => a + b, 0)
            },
            price_pre_usd() {
                var price = []
                for (const category of Object.entries(this.selected.elements)) {
                    if(category[1] && category[1].length > 0) {
                        category[1].forEach((el) => {
                            price.push(el.pre_usd)
                        })
                    }
                }
                return parseInt(this.selected.box.pre_usd) + price.reduce((a, b) => a + b, 0)
            },
            price() {
                var price = []
                for (const category of Object.entries(this.selected.elements)) {
                    if(category[1] && category[1].length > 0) {
                        category[1].forEach((el) => {
                            price.push(el.price)
                        })
                    }
                }
                return parseInt(this.selected.box.price) + price.reduce((a, b) => a + b, 0)
            },
            priceWithQuantity() {
                return this.price * this.quantity
            },
            priceWithDelivery() {
                return this.priceWithQuantity + parseInt(this.delivery.price)
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

                    this.views.category_current = response.data[0].id
                }))
            },
            loadElements() {
                axios
                .get('/api/elements')
                .then((response => {
                    this.elements = response.data
                }))
            },
            activateViewsCategories() {
                if(this.selected.box && this.selected.box.id > 0 && this.elementsFiltered.length > 0) {
                    this.views.boxes = false
                    this.views.categories = true

                    this.selected.elements[this.categories[0].slug][0].id = this.elementsFiltered.filter(element => element.category_id == this.categories[0].id)[0].id
                } else {
                    alert('Выберите корпус!')
                }
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
                    this.views.category_current = this.categories[index - 1].id
                } else {
                    this.views.boxes = true
                    this.views.categories = false
                }
            },
            nextCategory(category) {
                var index = this.categories.indexOf(category)
                var nextCategory = this.categories[index + 1]

                if(index >= 0 && index < this.categories.length - 1 && nextCategory.elements && nextCategory.elements.length > 0) {
                    this.views.category_current = nextCategory.id
                    this.selected.elements[nextCategory.slug][0].id = this.elementsFiltered.filter(element => element.category_id == nextCategory.id)[0].id
                } else {
                    this.views.quantity = true
                    this.views.delivery = true
                    if(!this.delivery.id > 0) {
                        this.resetDelivery()
                    }
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
                this.resetDelivery()
            },
            openDeliveryModal() {
                this.views.modals.delivery = true
            },
            closeDeliveryModal() {
                this.views.modals.delivery = false
                document.body.classList.remove('modal-open')
            },
            resetDelivery() {
                this.delivery.id = 0
                this.delivery.name = 'Самовывоз'
                this.delivery.price = 0
                this.delivery.direction = ''
                this.delivery.days = 0
            },
        },
        filters: {
            currency(value) {
                return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, " ") 
            }
        },
        components: {
            DeliveryModal
        }
    }
</script>