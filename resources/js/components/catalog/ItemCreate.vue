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
                            <button @click="selectAllBoxes()" class="btn btn-sm">выбрать все</button>
                        </div>
                        <select v-model="selected.boxes" class="form-control mb-3" style="height: 250px;" multiple>
                            <option v-for="box in boxes" :key="'box_' + box.id" :value="box.id">{{ box.name }}</option>
                        </select>
                    </div>
                    <div class="col-12 col-lg-6">
                        <div class="d-flex justify-content-between">
                            <label>Склад</label>
                        </div>
                        <select v-model="selected.stockItems" class="form-control mb-3" style="height: 165px;" multiple>
                            <option v-for="stockItem in stockItems" :key="'stock_item_' + stockItem.id" :value="stockItem.id">{{ stockItem.name }} - {{ middleBalancePrice(stockItem) | currency }} ₽</option>
                        </select>
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

                errors: [],
            }
        },
        computed: {
            price() {
                let stockItems = []
                this.selected.stockItems.forEach((selectedItem) => {
                    if(this.stockItems.find(item => item.id == selectedItem)) {
                        stockItems.push(this.stockItems.find(item => item.id == selectedItem))
                    }
                })
                return stockItems.map(stockItem => stockItem.balances.map(a => a.price).reduce((a, b) => (parseInt(a) + parseInt(b))) / stockItem.balances.map(a => a.price).length).reduce((a, b) => a + b, 0)
            }
        },
        created() {
            this.loadCategories()
            this.loadBoxes()
            this.loadStockItems()
        },
        methods: {
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
                this.selected.boxes = this.boxes.map(box => box.id)
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