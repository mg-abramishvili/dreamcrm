<template>
    <div v-if="categories.length > 0 && boxes.length > 0">
        <div class="row align-items-center mb-4">
            <div class="col-12 col-lg-6">
                <h1 class="h3 m-0">
                    <router-link v-if="$route.params.category_id" :to="{name: 'CatalogCategory', params: {id: $route.params.category_id}}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left align-middle me-2"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                    </router-link>
                    <router-link v-else :to="{name: 'Catalog'}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left align-middle me-2"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                    </router-link>
                    <strong>
                        Новый элемент каталога
                    </strong>
                </h1>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div v-if="errors && errors.length > 0" class="alert alert-danger">
                    <div class="alert-message">
                        <strong v-for="(error, index) in errors" :key="'error_' + index" class="d-block">
                            {{ error }}
                        </strong>
                    </div>
                </div>

                <label>Название</label>
                <input v-model="name" type="text" class="form-control mb-3">

                <label>Категория</label>
                <select v-model="selected.category" class="form-select mb-3">
                    <option v-for="category in categories" :key="'key_' + category.id" :value="category.id">{{ category.name }}</option>
                </select>

                <div class="row">
                    <div class="col-12 col-lg-6">
                        <div class="d-flex justify-content-between">
                            <label>Совместимость</label>
                            <div>
                                <button @click="selectAllBoxes()" class="btn btn-sm">выбрать все</button>
                                <button @click="uncheckAllBoxes()" class="btn btn-sm">снять все</button>
                            </div>
                        </div>
                        <input v-model="boxSearchInput" type="text" class="form-control mb-1" placeholder="Поиск по корпусам...">
                        <div class="form-control" style="height: 180px; overflow-y: auto;">
                            <div v-for="box in boxesFiltered" :key="'box_' + box.id" class="form-check">
                                <input v-model="selected.boxes" :id="'box_' + box.id" :value="box.id" class="form-check-input" type="checkbox">
                                <label class="form-check-label" :for="'box_' + box.id">
                                    {{ box.name }}
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-lg-6">
                        <div class="d-flex justify-content-between">
                            <label>Склад</label>
                        </div>
                        <input v-model="stockSearchInput" type="text" class="form-control mb-1" placeholder="Поиск по складу...">
                        <div class="form-control" style="height: 180px; overflow-y: auto;">
                            <div v-for="stockItem in stockItemsFiltered" :key="'stock_item_' + stockItem.id" class="form-check form-check-flex">
                                <div>
                                    <input v-model="selected.stockItems" :id="'stock_item_' + stockItem.id" :value="stockItem.id" class="form-check-input" type="checkbox">
                                    <label class="form-check-label" :for="'stock_item_' + stockItem.id">
                                        {{ stockItem.name }} - {{ LatestBalancePrice(stockItem) | currency }} ₽
                                    </label>
                                </div>
                                <div>
                                    <input v-if="selected.stockItems.includes(stockItem.id)" type="number" class="form-control form-control-mini-number">
                                </div>
                            </div>
                        </div>
                        <label>Цена (финальная)</label>
                        <input v-model="price" disabled type="number" class="form-control">
                    </div>
                </div>

                <button @click="save()" class="btn btn-primary">Сохранить</button>
            </div>
        </div>
    </div>
    <div v-else>
        <Loader></Loader>
    </div>
</template>

<script>
    import Loader from '../Loader.vue'

    export default {
        data() {
            return {
                name: '',

                selected: {
                    category: '',
                    boxes: [],
                    stockItems: [],
                },
                
                categories: [],
                boxes: [],
                stockItems: [],

                stockSearchInput: '',
                boxSearchInput: '',

                usd: {
                    kurs: '',
                    date: '',
                },

                errors: [],
            }
        },
        computed: {
            boxesFiltered() {
                return this.boxes.filter(box => {
                    return box.name.toLowerCase().includes(this.boxSearchInput.toLowerCase())
                })
            },
            stockItemsFiltered() {
                return this.stockItems.filter(item => this.LatestBalancePrice(item)).filter(stockItem => {
                    return stockItem.name.toLowerCase().includes(this.stockSearchInput.toLowerCase())
                })
            },
            price() {
                let selectedStockItems = this.stockItems.filter(stockItem => this.selected.stockItems.includes(stockItem.id))

                return selectedStockItems.map(stockItem => stockItem.balances[stockItem.balances.length - 1].price).reduce((a, b) => parseInt(a) + parseInt(b), 0)
            }
        },
        created() {
            this.loadUsd()
            this.loadCategories()
            this.loadBoxes()
            this.loadStockItems()
        },
        methods: {
            loadUsd() {
                axios
                .get('/api/usd')
                .then((response => {
                    this.usd.kurs = response.data.kurs,
                    this.usd.date = response.data.date
                }))
            },
            loadCategories() {
                axios.get('/api/catalog/categories')
                    .then((response => {
                        this.categories = response.data
                        if(this.$route.params.category_id) {
                            this.selected.category = this.$route.params.category_id
                        }
                    }))
            },
            loadBoxes() {
                axios.get(`/api/catalog/boxes`)
                    .then(response => (
                        this.boxes = response.data
                    ))
            },
            loadStockItems() {
                axios.get(`/api/stock/items`)
                    .then(response => (
                        this.stockItems = response.data
                    ))
            },
            selectAllBoxes() {
                this.selected.boxes = this.boxesFiltered.map(box => box.id)
            },
            uncheckAllBoxes() {
                this.selected.boxes = []
            },
            LatestBalancePrice(stockItem) {
                if(stockItem.balances.length > 0) {
                    let rub = stockItem.balances[stockItem.balances.length - 1].pre_rub
                    let usd = stockItem.balances[stockItem.balances.length - 1].pre_usd * this.usd.kurs
                    return Math.ceil((rub + usd) / 50) * 50
                }
            },
            middleBalancePrice(stockItem) {
                if(stockItem.balances.length) {
                    return stockItem.balances.map(a => a.price).reduce((a, b) => (parseInt(a) + parseInt(b))) / stockItem.balances.map(a => a.price).length
                }
            },
            save() {
                this.errors = []

                if (!this.name) {
                    this.errors.push('Укажите название');
                }
                if (!this.price) {
                    this.errors.push('Укажите цену');
                }
                if (!this.selected.category) {
                    this.errors.push('Укажите категорию');
                }
                if (!this.selected.boxes.length) {
                    this.errors.push('Укажите совместимость');
                }

                if(this.errors && this.errors.length > 0) {
                    return
                }

                axios.post('/api/catalog/items',
                {
                    name: this.name,
                    price: this.price,
                    category_id: this.selected.category,
                    stock_items: this.selected.stockItems,
                    boxes: this.selected.boxes,
                })
                .then(response => (
                    this.$router.push({name: 'CatalogCategory', params: {id: this.$route.params.category_id}}) 
                ))
                .catch((error) => {
                    if(error.response) {
                        for(var key in error.response.data.errors){
                            console.log(key)
                        }
                    }
                })
            },
        },
        components: {
            Loader
        }
    }
</script>