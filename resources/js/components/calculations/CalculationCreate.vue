<template>
    <div>
        <h1 class="h3 m-0 mb-4">Новый расчет</h1>
        
        <div v-if="types.length" class="row">
            <div class="col-12 col-lg-5">
                <div class="calculation-left-block bg-white px-4 py-4" style="position: sticky; top: 20px;">
                    <div v-if="views.types" class="mb-4">
                        <div class="calculation-left-block-main-label">
                            <strong>Тип</strong>
                        </div>
                        <select v-model="selected.type" @change="loadBoxes(); resetCatalogItems()" class="form-select form-select-lg mt-2 mb-3">
                            <option v-for="type in types" :key="'type_' + type.id" :value="type">{{ type.name }}</option>
                        </select>
                        <div class="mt-4">
                            <button class="btn btn-outline-primary" disabled>Назад</button>
                            <button @click="viewBoxes()" class="btn btn-outline-primary">Далее</button>
                        </div>
                    </div>

                    <div v-if="views.boxes" class="mb-4">
                        <div class="calculation-left-block-main-label">
                            <strong>Корпус</strong>
                        </div>
                        <select v-model="selected.box" @change="loadCatalogItems(); resetCatalogItems()" class="form-select form-select-lg mt-2 mb-3">
                            <template v-for="box in boxes">
                                <option v-if="box.width > 0 && box.length > 0 && box.height > 0 && box.weight > 0" :key="'box_' + box.id" :value="box">{{ box.name }} &mdash; {{ box.price | currency }} ₽</option>
                            </template>
                        </select>
                        <div class="mt-4">
                            <button @click="viewTypes()" class="btn btn-outline-primary">Назад</button>
                            <button @click="viewCategories()" class="btn btn-outline-primary">Далее</button>
                        </div>
                    </div>

                    <div v-if="views.categories" class="mb-4">
                        <div v-for="category in categories" :key="'category_' + category.id">
                            <div v-show="views.categoryCurrent == category.id">
                                <div class="calculation-left-block-main-label">
                                    <strong>{{ category.name }}</strong>
                                    <button @click="addCatalogItem(category.slug)" class="btn btn-sm btn-outline-danger">+</button>
                                </div>
                                
                                <div v-for="(catalogItem, index) in selected.catalogItems[category.slug]" :key="index" style="position: relative;">
                                    <select v-model="catalogItem.id" class="form-select form-select-lg mt-2 mb-3">
                                        <template v-for="catalogItem in catalogItems">
                                            <option v-if="catalogItem.category_id == category.id" :key="'catalogItem_' + catalogItem.id" :value="catalogItem.id">
                                                {{ catalogItem.name }} <template v-if="catalogItem.price > 0">&mdash; {{ catalogItem.price | currency }} ₽</template>
                                            </option>
                                        </template>
                                    </select>
                                    <button v-if="index > 0 && selected.catalogItems[category.slug].length > 1" @click="deleteCatalogItem(catalogItem.id, category.slug)" class="btn btn-sm btn-outline-danger" style="position: absolute; right: 0; top: 50%; transform: translateY(-50%); padding: 0; width: 20px; margin-right: -21px;">&ndash;</button>
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
            <div class="col-12 col-lg-7">
                <div class="mb-3 bg-white px-3 py-3">
                    <small style="color: rgb(136, 136, 136);">Тип</small>
                    <div v-if="selected.type && selected.type.id > 0" class="row align-items-center">
                        <div class="col-12">
                            <strong class="d-block">{{ selected.type.name }}</strong>
                        </div>
                    </div>
                </div>
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
                    <div v-for="item in catalogItemsByCategory(category)" class="row align-items-center">
                        <div class="col-8">
                            <strong class="d-block">{{ item.name }}</strong>
                        </div>
                        <div class="col-4 text-end">
                            <strong class="text-primary">{{ item.price | currency }} ₽</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div v-else>
            <Loader></Loader>
        </div>

    </div>
</template>

<script>
    import Loader from '../Loader.vue'
    import DeliveryPEK from './CalculationDeliveryPEK.vue'

    export default {
        data() {
            return {
                types: [],
                boxes: [],
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
                    types: true,
                    boxes: false,
                    categories: false,
                    categoryCurrent: '',
                    quantity: false,
                    delivery: false,
                    saveButton: false,
                },
            }
        },
        created() {
            this.loadTypes()
            this.loadCategories()
            this.loadDeliveries()
        },
        watch: {
            selected: {
                deep: true,
                handler() {
                    for (const category of Object.entries(this.selected.catalogItems)) {
                        if(category[1] && category[1].length > 0) {
                            category[1].forEach((i) => {
                                if(i.id != null) {
                                    i.price = parseInt(this.catalogItems.filter(item => item.id == i.id)[0].price)
                                }
                            })
                        }
                    }
                }
            }
        },
        computed: {
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
            loadTypes() {
                axios
                .get('/api/catalog/types')
                .then((response => {
                    this.types = response.data
                }))
            },
            loadBoxes() {
                axios
                .get(`/api/catalog/boxes/type/${this.selected.type.id}`)
                .then((response => {
                    this.boxes = response.data
                }))
            },
            loadDeliveries() {
                axios
                .get('/api/calculation/deliveries')
                .then((response => {
                    this.deliveries = response.data
                }));
            },
            loadCategories() {
                axios
                .get('/api/catalog/categories')
                .then((response => {
                    this.categories = response.data

                    response.data.forEach(category => {
                        this.$set(this.selected.catalogItems, category.slug, [])
                        this.addCatalogItem(category.slug)
                    })

                    this.views.categoryCurrent = response.data[0].id
                }))
            },
            loadCatalogItems() {
                if(this.selected.box && this.selected.box.id > 0) {
                    axios
                    .get(`/api/catalog/items/box/${this.selected.box.id}`)
                    .then((response => {
                        this.catalogItems = response.data
                    }))
                }
                
            },
            resetCatalogItems() {

            },
            addCatalogItem(categorySlug) {
                let checkEmpty = this.selected.catalogItems[categorySlug].filter(item => item.id === null)
                if (checkEmpty.length >= 1 && this.selected.catalogItems[categorySlug].length > 0) {
                    return
                }
                this.selected.catalogItems[categorySlug].push({
                    id: null,
                    price: 0
                })
            },
            deleteCatalogItem(itemID, categorySlug) {
                var index = this.selected.catalogItems[categorySlug].map(item => { return item.id }).indexOf(itemID)
                this.selected.catalogItems[categorySlug].splice(index, 1)
            },
            catalogItemsByCategory(category) {
                var categoryItems = this.catalogItems.filter(item => item.category_id == category.id)
                var categoryItemsSelected = this.selected.catalogItems[category.slug]
                var categoryItemsFiltered = []

                categoryItemsSelected.forEach((itemSelected) => {
                    if(categoryItems.find(item => item.id === itemSelected.id)) {
                        categoryItemsFiltered.push(categoryItems.find(item => item.id === itemSelected.id))
                    }
                })

                return categoryItemsFiltered
            },
            nextCategory(category) {
                let index = this.categories.indexOf(category)
                let nextCategory = this.categories[index + 1]

                if(index >= 0 && index < this.categories.length - 1) {
                    this.views.categoryCurrent = nextCategory.id
                    this.selected.catalogItems[nextCategory.slug][0].id = this.catalogItems.filter(item => item.category_id == nextCategory.id)[0].id
                } else {
                    this.viewQuantity()
                }
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
            viewTypes() {
                this.views.types = true
                this.views.boxes = false
                this.views.categories = false
                this.views.quantity = false
                this.views.delivery = false
                this.views.saveButton = false
            },
            viewBoxes() {
                this.views.types = false
                this.views.boxes = true
                this.views.categories = false
                this.views.quantity = false
                this.views.delivery = false
                this.views.saveButton = false
            },
            viewCategories() {
                if(this.selected.box && this.selected.box.id && this.catalogItems.length) {
                    this.views.types = false
                    this.views.boxes = false
                    this.views.categories = true
                    this.views.quantity = false
                    this.views.delivery = false
                    this.views.saveButton = false

                    this.selected.catalogItems[this.categories[0].slug][0].id = this.catalogItems.filter(catalogItem => catalogItem.category_id == this.categories[0].id)[0].id
                } else {
                    alert('Выберите корпус!')
                }
            },
            viewQuantity() {
                this.views.types = false
                this.views.boxes = false
                this.views.categories = false
                this.views.quantity = true
                this.views.delivery = false
                this.views.saveButton = false
            },
            viewDelivery() {
                this.views.types = false
                this.views.boxes = false
                this.views.categories = false
                this.views.quantity = false
                this.views.delivery = true
                this.views.saveButton = true
            },
        },
        components: {
            Loader,
            DeliveryPEK
        }
    }
</script>