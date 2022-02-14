<template>
    <div class="stock-item-page">
        <div class="row align-items-center mb-4">
            <div class="col-12 col-lg-6">
                <h1 class="h3 m-0">
                    <router-link v-if="$route.params.category_id" :to="{name: 'StockCategory', params: { category_id: $route.params.category_id }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left align-middle me-2"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                    </router-link>
                    <router-link v-else :to="{name: 'Stock'}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left align-middle me-2"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                    </router-link>
                    <strong>
                        Новый товар на склад
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

                <button @click="save()" class="btn btn-primary">Сохранить</button>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                name: '',
                category: '',

                categories: [],

                errors: [],
            }
        },
        created() {
            this.loadCategories()
        },
        methods: {
            loadCategories() {
                axios.get(`/api/stock/categories`)
                    .then((response => {
                        this.categories = response.data

                        this.category = this.$route.params.category_id
                    }))
            },
            save() {
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

                axios.post(`/api/stock/items`,
                {
                    name: this.name,
                    category_id: this.category,
                })
                .then(response => (
                    this.$router.push({name: 'StockCategory', params: {category_id: this.$route.params.category_id }})
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
        },
    }
</script>