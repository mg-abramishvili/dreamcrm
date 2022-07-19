<template>
    <div class="catalog-page">
        <div class="card card-bordered">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-12 col-lg-8">
                        <h1 v-if="!$route.params.id" class="h3 m-0">
                            <router-link v-if="$route.params.category_id" :to="{name: 'CatalogCategory', params: {id: $route.params.category_id}}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left align-middle me-2"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                            </router-link>
                            <router-link v-else :to="{name: 'Catalog'}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left align-middle me-2"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                            </router-link>
                            <strong>Новый элемент каталога</strong>
                        </h1>
                        <h1 v-if="$route.params.id" class="h3 m-0">
                            <router-link :to="{name: 'CatalogCategory', params: {id: item.category_id}}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left align-middle me-2"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                            </router-link>
                            <strong>{{ item.name }}</strong>
                        </h1>
                    </div>
                    <div class="col-12 col-lg-4 text-end">
                        <button @click="save()" class="btn btn-primary">Сохранить</button>
                        <button v-if="$route.params.id" @click="del()" class="btn btn-outline-danger">Удалить</button>
                    </div>
                </div>
            </div>
        </div>

        <Loader v-if="views.loading"></Loader>

        <div v-if="!views.loading" class="tab">
            <ul class="nav nav-tabs" role="tablist">
                <li class="nav-item">
                    <a @click="selectTab('general')" class="nav-link" :class="{'active': selected.tab == 'general'}" role="tab">Общая информация</a>
                </li>
                <li class="nav-item">
                    <a @click="selectTab('compatibility')" class="nav-link" :class="{'active': selected.tab == 'compatibility'}" role="tab">Совместимость</a>
                </li>
                <li class="nav-item">
                    <a @click="selectTab('stock')" class="nav-link" :class="{'active': selected.tab == 'stock'}" role="tab">Склад</a>
                </li>
                <li class="nav-item">
                    <a @click="selectTab('prices')" class="nav-link" :class="{'active': selected.tab == 'prices'}" role="tab">Стоимость</a>
                </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane" :class="{'active': selected.tab == 'general'}" role="tabpanel">
                    <div class="mb-3">
                        <label>Название</label>
                        <input v-model="name" type="text" class="form-control mb-3">
                    </div>

                    <div class="mb-3">
                        <label>Категория</label>
                        <select v-model="selected.category" class="form-select mb-3">
                            <option v-for="category in categories" :key="'key_' + category.id" :value="category.id">{{ category.name }}</option>
                        </select>
                    </div>
                </div>
                <div class="tab-pane" :class="{'active': selected.tab == 'compatibility'}" role="tabpanel">
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
                <div class="tab-pane" :class="{'active': selected.tab == 'stock'}" role="tabpanel">
                    <input v-model="stockSearchInput" type="text" class="form-control mb-1" placeholder="Поиск по складу...">
                    <div class="form-control" style="height: 550px; overflow-y: auto;">
                        <div v-for="stockItem in stockItemsFiltered" :key="'stock_item_' + stockItem.id" class="form-check form-check-flex">
                            <div>
                                <input @change="toggleStockItems(stockItem.id, $event)" :checked="ifChecked(stockItem.id)" :id="'stock_item_' + stockItem.id" class="form-check-input" type="checkbox">
                                <label class="form-check-label" :for="'stock_item_' + stockItem.id">
                                    {{ stockItem.name }} - {{ latestBalancePrice(stockItem) | currency }} ₽
                                </label>
                            </div>
                            <div v-if="selected.stockItems.find(i => i.id == stockItem.id)">
                                <input v-model.number="selected.stockItems.find(i => i.id == stockItem.id).quantity" type="number" min="1" class="form-control form-control-mini-number">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane" :class="{'active': selected.tab == 'prices'}" role="tabpanel">
                    <div class="row mb-3">
                        <div class="col">
                            <label>Склад</label>
                            <input v-model="stockItemsPrice" disabled type="number" class="form-control">
                        </div>
                        <div class="col">
                            <label>Цена финал</label>
                            <input v-model="price" disabled type="number" class="form-control">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Loader from '../Loader.vue'

    export default {
        data() {
            return {
                item: '',

                name: '',

                selected: {
                    tab: 'general',
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
                },

                views: {
                    loading: true,
                },
            }
        },
        computed: {
            boxesFiltered() {
                return this.boxes.filter(box => {
                    return box.name.toLowerCase().includes(this.boxSearchInput.toLowerCase())
                })
            },
            stockItemsFiltered() {
                let filtered = this.stockItems.filter(stockItem => {
                    if(stockItem.latest_balance) {
                        return stockItem.name.toLowerCase().includes(this.stockSearchInput.toLowerCase())
                    }
                })
                
                return filtered.filter(stockItem => this.selected.stockItems.find(i => i.id == stockItem.id)).concat(filtered.filter(stockItem => !this.selected.stockItems.find(i => i.id == stockItem.id)))
            },
            priceRub() {
                let priceRub = []

                this.selected.stockItems.forEach(item => {
                    let stockItem = this.stockItems.find(i => i.id === item.id)
                    if(stockItem.latest_balance && item.quantity) {
                        priceRub.push(stockItem.latest_balance.pre_rub * item.quantity)
                    }
                })

                return priceRub.reduce((a, b) => a + b, 0)
            },
            priceUsd() {
                let priceUsd = []

                this.selected.stockItems.forEach(item => {
                    let stockItem = this.stockItems.find(i => i.id === item.id)

                    if(stockItem.latest_balance && item.quantity) {
                        let usdKurs = 0

                        if(this.usd.kurs > stockItem.latest_balance.usd_kurs) {
                            usdKurs = this.usd.kurs
                        } else {
                            usdKurs = stockItem.latest_balance.usd_kurs
                        }

                        priceUsd.push((Math.ceil((stockItem.latest_balance.pre_usd * usdKurs) / 50) * 50) * item.quantity)
                    }
                })

                return priceUsd.reduce((a, b) => a + b, 0)
            },
            stockItemsPrice() {
                return this.priceRub + this.priceUsd
            },
            price() {
                return this.stockItemsPrice
            }
        },
        created() {
            this.loadUsd()
            this.loadCategories()
            this.loadBoxes()
            this.loadStockItems()
            this.loadItem()
        },
        methods: {
            loadUsd() {
                axios
                .get('/api/usd')
                .then(response => {
                    this.usd.kurs = parseInt(response.data.kurs)
                })
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
                axios.get(`/api/stock/items-with-latest-balances-only`)
                .then(response => {
                    this.stockItems = response.data
                })
            },
            loadItem() {
                if(!this.$route.params.id) {
                    return this.views.loading = false
                }

                axios.get(`/api/catalog/item/${this.$route.params.id}`)
                .then(response => {
                    this.item = response.data

                    this.name = response.data.name
                    this.selected.category = response.data.category_id

                    this.selected.boxes = response.data.boxes.map(box => box.id)
                    
                    this.selected.stockItems = response.data.stock_items.map(({id, pivot}) => ({id: id, quantity: pivot.quantity}))

                    this.views.loading = false
                })
            },
            selectAllBoxes() {
                this.selected.boxes = this.boxesFiltered.map(box => box.id)
            },
            uncheckAllBoxes() {
                this.selected.boxes = []
            },
            latestBalancePrice(stockItem) {
                if(stockItem.latest_balance) {
                    let usdKurs = 0

                    if(this.usd.kurs > stockItem.latest_balance.usd_kurs) {
                        usdKurs = this.usd.kurs
                    } else {
                        usdKurs = stockItem.latest_balance.usd_kurs
                    }

                    let rub = parseInt(stockItem.latest_balance.pre_rub)
                    let usd = parseInt(stockItem.latest_balance.pre_usd) * usdKurs
                    
                    return Math.ceil((rub + usd) / 50) * 50
                }
            },
            selectTab(tab) {
                this.selected.tab = tab
            },
            toggleStockItems(id) {
                if(this.selected.stockItems.find(i => i.id === id)) {
                    return this.selected.stockItems = this.selected.stockItems.filter(item => {
                        return item.id !== id;
                    })
                }

                if(!this.selected.stockItems.find(i => i.id === id)) {
                    return this.selected.stockItems.push({id: id, quantity: 1})
                }
            },
            ifChecked(id) {
                if(this.selected.stockItems.find(item => item.id === id)) {
                    return true
                }
            },
            save() {
                if(!this.name) {
                    return this.$swal({
                        text: 'Укажите название',
                        icon: 'error',
                    })
                }
                if (!this.selected.category) {
                    return this.$swal({
                        text: 'Укажите категорию',
                        icon: 'error',
                    })
                }
                if (!this.selected.boxes.length) {
                    return this.$swal({
                        text: 'Укажите совместимость',
                        icon: 'error',
                    })
                }

                let data = {
                    name: this.name,
                    price: this.price,
                    pre_rub: this.priceRub,
                    pre_usd: this.priceUsd,
                    category_id: this.selected.category,
                    stock_items: this.selected.stockItems,
                    boxes: this.selected.boxes,
                }

                if(this.$route.params.id) {
                    axios.put(`/api/catalog/item/${this.$route.params.id}/update`, data)
                    .then(response => (
                        this.$router.push({name: 'CatalogCategory', params: {id: this.selected.category}}) 
                    ))
                    .catch((error) => {
                        if(error.response) {
                            for(var key in error.response.data.errors){
                                console.log(key)
                            }
                        }
                    })
                } else {
                    axios.post(`/api/catalog/items`, data)
                    .then(response => (
                        this.$router.push({name: 'CatalogCategory', params: {id: this.selected.category}}) 
                    ))
                    .catch((error) => {
                        if(error.response) {
                            for(var key in error.response.data.errors){
                                console.log(key)
                            }
                        }
                    })
                }
            },
            del() {
                if (confirm("Точно удалить?")) {
                    axios.delete(`/api/catalog/item/${this.$route.params.id}/delete`)
                    .then(response => (
                        this.$router.push({name: 'CatalogCategory', params: {id: this.selected.category}}) 
                    ))
                    .catch(error => {
                        return this.$swal({
                            text: error.response.data,
                            icon: 'error',
                        })
                    })
                }
            },
        },
        components: {
            Loader
        }
    }
</script>