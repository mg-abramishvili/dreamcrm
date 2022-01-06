<template>
    <div>
        <h1 class="h3 m-0 mb-4">Новый расчет</h1>
        
        <div class="row">
            <div class="col-12 col-lg-5">
                <div class="calculation-input bg-white px-4 py-4" style="position: sticky; top: 20px;">
                    <div v-if="windowBoxes" class="mb-4">
                        <label><strong>Корпус</strong></label>

                        <select @change="changeInputBox()" v-model="inputBox" class="form-select form-select-lg mt-2 mb-3">
                            <option v-for="box in boxes" :key="'box_' + box.id" :value="box">{{ box.name }} &mdash; {{ box.price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, " ") }} ₽</option>
                        </select>

                        <div class="mt-4">
                            <button class="btn btn-outline-primary" disabled>Назад</button>
                            <button @click="activateWindowsCategories()" class="btn btn-outline-primary">Далее</button>
                        </div>
                    </div>

                    <div v-if="windowCategories" class="mb-4">
                        <div v-for="category in categories" :key="'category_' + category.id">
                            <div v-show="currentCategory == category.id">
                                <label><strong>{{ category.name }}</strong></label>

                                <button @click="addElement(category.slug)" class="btn btn-sm btn-outline-danger">+</button>
                                
                                <div v-for="(element, index) in inputElements[category.slug]" :key="index" style="position: relative;">
                                    <select v-model="element.id" class="form-select form-select-lg mt-2 mb-3">
                                        <template v-for="element in elementsFiltered">
                                            <option v-if="element.category_id == category.id" :value="element.id">
                                                {{ element.name }} <template v-if="element.price > 0">&mdash; {{ element.price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, " ") }} ₽</template>
                                            </option>
                                        </template>
                                    </select>
                                    <button v-if="index > 0 && inputElements[category.slug].length > 1" @click="deleteElement(element.id, category.slug)" class="btn btn-sm btn-outline-danger" style="position: absolute; right: 0; top: 50%; transform: translateY(-50%); padding: 0; width: 20px; margin-right: -21px;">&ndash;</button>
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
                            <div class="col-6 text-end text-primary" style="font-size: 26px; font-weight: bold;">{{ price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, " ") }} ₽</div>
                        </div>
                        <div v-if="windowQty" class="row align-items-center mb-3">
                            <div class="col-6">
                                <strong>Кол-во</strong>
                                <input v-model="quantity" type="number" class="form-control form-control-sm" style="font-size: 12px; display:inline-block; width: 70px;">
                            </div>
                            <div v-if="priceWithQuantity && priceWithQuantity > 0" class="col-6 text-end text-primary" style="font-size: 26px; font-weight: bold;">{{ priceWithQuantity.toString().replace(/\B(?=(\d{3})+(?!\d))/g, " ") }} ₽</div>
                        </div>
                        <div v-if="deliveryID && deliveryID > 0" class="row align-items-center mb-3">
                            <div class="col-6">
                                <strong>Доставка</strong> <br>
                                <small @click="openDeliveryModal()" style="line-height: 1.3; display: block; cursor: pointer;">
                                    {{ deliveryName }} <br>
                                    <template v-if="deliveryDirection && deliveryDirection.length > 0">({{ deliveryDirection }}, {{ deliveryDays }} дн.)</template>
                                </small>
                            </div>
                            <div class="col-6 text-end text-primary" style="font-size: 26px; font-weight: bold;">{{ deliveryPrice.toString().replace(/\B(?=(\d{3})+(?!\d))/g, " ") }} ₽</div>
                        </div>
                        <div v-if="deliveryName && deliveryName.length > 0 && priceWithDelivery && priceWithDelivery > 0" class="row align-items-center">
                            <div class="col-6"><strong>Итого</strong></div>
                            <div class="col-6 text-end text-primary" style="font-size: 26px; font-weight: bold;">{{ priceWithDelivery.toString().replace(/\B(?=(\d{3})+(?!\d))/g, " ") }} ₽</div>
                        </div>
                    </div>

                    <!-- <button @click="openDeliveryModal()">Delivery</button> -->
                    
                </div>
            </div>
            <div class="col-12 col-lg-7">
                <!-- <ul>
                    <li v-for="(element, categorySlug) in inputElements" :key="categorySlug"><strong>{{ categorySlug }}:</strong> {{ element }}</li>
                </ul> -->
                <div class="mb-3 bg-white px-3 py-3">
                    <small style="color: rgb(136, 136, 136);">Корпус</small>
                    <div v-if="inputBox && inputBox.id > 0" class="row align-items-center">
                        <div class="col-8">
                            <strong class="d-block">{{ inputBox.name }}</strong>
                        </div>
                        <div class="col-4 text-end">
                            <strong class="text-primary">{{ inputBox.price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, " ") }} ₽</strong>
                        </div>
                    </div>
                </div>

                <div v-for="category in categories" :key="'category_' + category.id" class="mb-3 bg-white px-3 py-3">
                    <small style="color: rgb(136, 136, 136);">{{ category.name }}</small>
                    <template v-for="element in elementsFiltered">
                        <template v-if="element.category_id == category.id">
                            <template v-for="inEl in inputElements">
                                <template v-for="inElEl in inEl">
                                    <div v-if="element.id == inElEl.id" class="row align-items-center">
                                        <div class="col-8">
                                            <strong class="d-block">{{ element.name }}</strong>
                                        </div>
                                        <div class="col-4 text-end">
                                            <strong class="text-primary">{{ element.price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, " ") }} ₽</strong>
                                        </div>
                                    </div>
                                </template>
                            </template>
                        </template>
                    </template>
                </div>
            </div>
        </div>
        
        <DeliveryModal v-if="deliveryModal" v-bind:box="inputBox"></DeliveryModal>
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

                inputBox: {},
                inputElements: {},

                windowBoxes: true,
                windowCategories: false,

                currentCategory: '',

                quantity: 1,
                windowQty: false,

                deliveryModal: false,

                deliveryID: 0,
                deliveryName: '',
                deliveryPrice: 0,
                deliveryDirection: '',
                deliveryDays: 0,
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
            inputElements: {
                deep: true,
                handler() {
                    for (const category of Object.entries(this.inputElements)) {
                        if(category[1] && category[1].length > 0) {
                            category[1].forEach((el) => {
                                if(el.id != null) {
                                    el.price = parseInt(this.elements.filter(element => element.id == el.id)[0].price)
                                }
                            })
                        }
                    }
                }
            }
        },
        computed: {
            elementsFiltered() {
                if(this.inputBox && this.inputBox.id > 0) {
                    var array = []
                    this.elements.forEach((element) => {
                        if(element.boxes && element.boxes.length > 0) {
                            element.boxes.forEach((box) => {
                                if(box.id == this.inputBox.id) {
                                    array.push(element)
                                }
                            })
                        }
                    })
                    return array
                } else {
                    return this.elements
                }
            },
            price() {
                var price = []
                for (const category of Object.entries(this.inputElements)) {
                    if(category[1] && category[1].length > 0) {
                        category[1].forEach((el) => {
                            price.push(el.price)
                        })
                    }
                }
                return parseInt(this.inputBox.price) + price.reduce((a, b) => a + b, 0)
            },
            priceWithQuantity() {
                return this.price * this.quantity
            },
            priceWithDelivery() {
                return this.priceWithQuantity + parseInt(this.deliveryPrice)
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
                        this.$set(this.inputElements, category.slug, [])
                        this.addElement(category.slug)
                    })

                    this.currentCategory = response.data[0].id
                }))
            },
            loadElements() {
                axios
                .get('/api/elements')
                .then((response => {
                    this.elements = response.data
                }))
            },
            activateWindowsCategories() {
                if(this.inputBox && this.inputBox.id > 0) {
                    this.windowBoxes = false
                    this.windowCategories = true

                    this.currentCategory = this.categories[0].id

                    if(this.categories[0].elements && this.categories[0].elements.length > 0) {
                        this.inputElements[this.categories[0].slug][0].id = this.elementsFiltered.filter(element => element.category_id == this.categories[0].id)[0].id
                    }
                } else {
                    alert('Выберите корпус!')
                }
            },
            addElement(categorySlug) {
                let checkEmpty = this.inputElements[categorySlug].filter(element => element.id === null)
                if (checkEmpty.length >= 1 && this.inputElements[categorySlug].length > 0) {
                    return
                }
                this.inputElements[categorySlug].push({id: null, price: 0})
            },
            deleteElement(ElementID, categorySlug) {
                var index = this.inputElements[categorySlug].map(element => {
                    return element.id;
                }).indexOf(ElementID);
                this.inputElements[categorySlug].splice(index, 1)
            },
            prevCategory(category) {
                var index = this.categories.indexOf(category)
                if(index > 0 && index < this.categories.length + 1) {
                    this.currentCategory = this.categories[index - 1].id
                } else {
                    this.windowBoxes = true
                    this.windowCategories = false
                }
            },
            nextCategory(category) {
                var index = this.categories.indexOf(category)
                if(index >= 0 && index < this.categories.length - 1) {
                    this.currentCategory = this.categories[index + 1].id

                    if(this.categories[index + 1].elements && this.categories[index + 1].elements.length > 0) {
                        this.inputElements[this.categories[index + 1].slug][0].id = this.elementsFiltered.filter(element => element.category_id == this.categories[index + 1].id)[0].id
                    }
                } else {
                    this.windowQty = true

                    if(this.deliveryID && this.deliveryID > 0) {

                    } else {
                        this.deliveryID = 1
                        this.deliveryName = 'Самовывоз'
                        this.deliveryPrice = 0
                    }
                }
            },
            changeInputBox() {
                for (const category of Object.entries(this.inputElements)) {
                    if(category[1] && category[1].length > 0) {
                        category[1].forEach((el) => {
                            el.id = null,
                            el.price = 0
                        })
                    }
                }
            },
            openDeliveryModal() {
                this.deliveryModal = true
            },
            closeDeliveryModal() {
                this.deliveryModal = false
                document.body.classList.remove('modal-open')
            }
        },
        components: {
            DeliveryModal
        }
    }
</script>