<template>
    <div v-if="item && item.id && item.id > 0" class="stock-item-page">
        <div class="row align-items-center mb-4">
            <div class="col-12 col-lg-6">
                <h1 class="h3 m-0">
                    <router-link v-if="item.category_id && item.category_id > 0" :to="{name: 'StockCategory', params: { category_id: item.category_id }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left align-middle me-2"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                    </router-link>
                    <router-link v-else :to="{name: 'Stock'}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left align-middle me-2"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                    </router-link>
                    <strong>
                        {{ item.name }}
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

                <div class="mb-3">
                    <label>Название</label>
                    <input v-model="name" type="text" class="form-control">
                </div>

                <div class="mb-3">
                    <label>Категория</label>
                    <select v-model="category" type="text" class="form-select">
                        <option v-for="categoryItem in categories" :key="categoryItem.id" :value="categoryItem.id">{{ categoryItem.name }}</option>
                    </select>
                </div>

                <button @click="updateItem(item.id)" class="btn btn-primary">Сохранить</button>
            </div>
        </div>
        
        <h2 class="h3 mt-0 mb-2 text-center">
            Остатки
            <button @click="openAddBalance()" class="btn btn-outline-primary ms-1">Добавить</button>
        </h2>

        <AddBalance v-if="views.addBalance" :item="item"></AddBalance>

        <div v-for="balance in item.balances" :key="balance.id" class="card mb-1">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-2 text-center">
                        <span class="text-lg fw-bold text-primary" style="font-size: 30px; line-height: 1;">{{ balance.quantity }}</span>
                        <small class="text-muted">шт.</small>
                    </div>
                    <div class="col-2 text-center fw-bold">
                        {{ balance.date | date }}
                    </div>
                    <div class="col-2 text-center fw-bold">
                        {{ balance.pre_rub | currency }} ₽
                    </div>
                    <div class="col-2 text-center fw-bold">
                        ${{ balance.pre_usd | currency }} <small class="fw-normal text-muted d-block">курс {{ balance.usd_kurs }}</small>
                    </div>
                    <div class="col-2 text-center fw-bold">
                        {{ balance.price | currency }} ₽
                    </div>
                    <div class="col-2 text-center">
                        <button @click="del(balance.id)" class="btn btn-sm btn-outline-danger">&times;</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div v-else>
        <div class="spinner-border text-primary me-2" role="status">
            <span class="sr-only">Загрузка...</span>
        </div>
    </div>
</template>

<script>
    import AddBalance from './ItemAddBalance.vue'

    export default {
        data() {
            return {
                item: {},

                name: '',
                category: '',

                categories: [],

                views: {
                    addBalance: false,
                },

                errors: [],
            }
        },
        created() {
            this.loadCategories()
            this.loadItem()
        },
        methods: {
            loadCategories() {
                axios.get(`/api/stock/categories`)
                    .then((response => {
                        this.categories = response.data
                    }))
            },
            loadItem() {
                axios.get(`/api/stock/item/${this.$route.params.id}`)
                    .then((response => {
                        this.item = response.data

                        this.name = response.data.name
                        this.category = response.data.category_id
                    }))
            },
            openAddBalance() {
                this.views.addBalance = true
            },
            closeAddBalance() {
                this.views.addBalance = false
            },
            updateItem(id) {
                this.errors = []

                if (!this.name) {
                    this.errors.push('Укажите имя');
                }
                if (!this.category) {
                    this.errors.push('Укажите категорию');
                }

                if(this.errors && this.errors.length > 0) {
                    return
                }

                axios.put(`/api/stock/item/${id}/update`,
                {
                    name: this.name,
                    category_id: this.category,
                })
                .then(response => (
                    this.$router.push({name: 'StockCategory', params: {category_id: this.category }})
                ))
                .catch((error) => {
                    if(error.response) {
                        this.errors = []
                        for(var key in error.response.data.errors){
                            this.errors.push(key)
                        }
                    }
                })
            },
            del(id) {
                if (confirm("Точно удалить?")) {
                    axios.delete(`/api/stock/balance/${id}/delete`)
                    .then(response => (
                        this.loadItem()
                    ))
                    .catch((error) => {
                        if(error.response) {
                            this.errors = []
                            for(var key in error.response.data.errors){
                                this.errors.push(key)
                            }
                        }
                    })
                }
            }
        },
        components: {
            AddBalance
        }
    }
</script>