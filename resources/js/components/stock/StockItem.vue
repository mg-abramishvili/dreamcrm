<template>
    <div v-if="item && item.id && item.id > 0" class="stock-item-page">
        <div class="row align-items-center mb-4">
            <div class="col-12 col-lg-6">
                <h1 class="h3 m-0">
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
    </div>
    <div v-else>
        <div class="spinner-border text-primary me-2" role="status">
            <span class="sr-only">Загрузка...</span>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                item: {},

                name: '',
                category: '',

                categories: [],

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

                        this.category = response.data.category_id
                    }))
            },
            updateItem(id) {
                this.errors = []

                if (!this.name) {
                    this.errors.push('Укажите имя');
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
                    this.$router.push({name: 'Stock'})
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