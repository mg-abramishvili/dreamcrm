<template>
    <div v-if="categories && categories.length > 0 && boxes && boxes.length > 0 && usd.kurs && usd.kurs > 0">
        <div class="row align-items-center mb-4">
            <div class="col-12 col-lg-6">
                <h1 class="h3 m-0">
                    <router-link v-if="$route.params.category" :to="{name: 'CategoryElements', params: {category: $route.params.category}}">
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

                <div class="row">
                    <div class="col">
                        <label>Цена RUB</label>
                        <input v-model="pre_rub" type="number" min="0" class="form-control">
                    </div>
                    <div class="col">
                        <label>Цена USD </label>
                        <input v-model="pre_usd" type="number" min="0" class="form-control">
                        <small>{{ usd.kurs }} ₽ от {{ usd.date | formatDateShort }}</small>
                    </div>
                    <div class="col">
                        <label>Цена (финальная)</label>
                        <input v-model="price" disabled type="number" class="form-control">
                    </div>
                </div>

                <label>Категория</label>
                <select v-model="selected.category" class="form-select mb-3">
                    <option v-for="category in categories" :key="'key_' + category.id" :value="category.id">{{ category.name }}</option>
                </select>

                <div class="d-flex justify-content-between">
                    <label>Совместимость</label>
                    <button @click="selectAllBoxes()" class="btn btn-sm">выбрать все</button>
                </div>
                <select v-model="selected.boxes" class="form-control mb-3" style="height: 250px;" multiple>
                    <option v-for="box in boxes" :key="'box_' + box.id" :value="box.id">{{ box.name }}</option>
                </select>

                <button @click="saveElement()" class="btn btn-primary">Сохранить</button>
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
                name: '',
                pre_rub: 0,
                pre_usd: 0,

                selected: {
                    category: '',
                    boxes: [],
                },
                
                categories: [],
                boxes: [],

                usd: {
                    kurs: '',
                    date: '',
                },

                errors: [],
            }
        },
        computed: {
            price: function () {
                if(this.pre_rub && this.pre_rub.length > 0 || this.pre_usd && this.pre_usd.length > 0) {
                    if(this.pre_rub && this.pre_rub.length > 0 && this.pre_usd && this.pre_usd.length > 0) {
                        return Math.ceil((parseFloat(this.pre_rub) + (parseFloat(this.usd.kurs) * parseFloat(this.pre_usd))) / 50) * 50
                    } else if (!this.pre_rub) {
                        return Math.ceil(parseFloat(parseFloat(this.usd.kurs) * parseFloat(this.pre_usd) / 50)) * 50
                    } else if (!this.pre_usd) {
                        return Math.ceil(parseFloat(this.pre_rub) / 50) * 50
                    }
                } else {
                    return 0
                }
            }
        },
        created() {
            this.loadCategories()            
            this.loadBoxes()            
            this.loadUsd()
        },
        methods: {
            loadCategories() {
                axios.get('/api/categories')
                    .then((response => {
                        this.categories = response.data
                        if(this.$route.params.category) {
                            this.selected.category = this.$route.params.category
                        }
                    }))
            },
            loadBoxes() {
                axios.get(`/api/boxes`)
                    .then(response => (
                        this.boxes = response.data
                    ))
            },
            loadUsd() {
                axios
                .get('/api/usd')
                .then((response => {
                    this.usd.kurs = response.data.kurs,
                    this.usd.date = response.data.updated_at
                }))
            },
            selectAllBoxes() {
                this.selected.boxes = this.boxes.map(box => box.id)
            },
            saveElement() {
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

                axios.post('/api/elements',
                {
                    name: this.name,
                    pre_rub: this.pre_rub,
                    pre_usd: this.pre_usd,
                    price: this.price,
                    category: this.selected.category,
                    boxes: this.selected.boxes,
                })
                .then(response => (
                    this.$router.push({path: `/catalog/${this.selected.category}/elements/`}) 
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
    }
</script>