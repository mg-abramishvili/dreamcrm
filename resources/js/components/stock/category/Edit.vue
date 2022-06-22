<template>
    <div>
        <div v-if="category.id">
            <div class="row align-items-center mb-4">
                <div class="col-12 col-lg-6">
                    <h1 class="h3 m-0">
                        <router-link :to="{name: 'Stock'}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left align-middle me-2"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                        </router-link>
                        <strong>
                            {{ category.name }}
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
                        <input v-model="name" @change="slugify(name)" type="text" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label>Символьный код</label>
                        <input v-model="slug" type="text" class="form-control">
                    </div>

                    <button @click="update(category.id)" class="btn btn-primary">Сохранить</button>
                    <button @click="del(category.id)" class="btn btn-outline-danger">Удалить</button>
                </div>
            </div>
        </div>
        <div v-else>
            <div class="spinner-border text-primary me-2" role="status">
                <span class="sr-only">Загрузка...</span>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                category: {},

                name: '',
                slug: '',

                errors: [],
            }
        },
        created() {
            this.loadCategory()
        },
        methods: {
            loadCategory() {
                axios.get(`/api/stock/category/${this.$route.params.category_id}`)
                .then(response => (
                    this.category = response.data,

                    this.name = response.data.name,
                    this.slug = response.data.slug
                ))
            },
            slugify(str) {
                var ru = {
                    'а': 'a', 'б': 'b', 'в': 'v', 'г': 'g', 'д': 'd', 
                    'е': 'e', 'ё': 'e', 'ж': 'zh', 'з': 'z', 'и': 'i', 
                    'к': 'k', 'л': 'l', 'м': 'm', 'н': 'n', 'о': 'o', 
                    'п': 'p', 'р': 'r', 'с': 's', 'т': 't', 'у': 'u', 
                    'ф': 'f', 'х': 'kh', 'ц': 'ts', 'ч': 'ch', 'ш': 'sh', 
                    'щ': 'shch', 'ы': 'y', 'э': 'e', 'ю': 'yu', 'я': 'ya'
                }, n_str = [];
                
                str = str.replace(/[ъь!|/|_\\'"<>/№;%:?*()@#$^&*+=,~.]+/g, '').replace(/й/g, 'i');
    
                for ( var i = 0; i < str.length; ++i ) {
                    n_str.push(
                            ru[str[i]]
                        || ru[str[i].toLowerCase()] == undefined && str[i]
                        || ru[str[i].toLowerCase()]
                    );
                }
                
                this.slug = n_str.join('').replace(/\s+/g, '-')
            },
            update(id) {
                this.errors = []

                if (!this.name) {
                    this.errors.push('Укажите имя');
                }
                if (!this.slug) {
                    this.errors.push('Укажите символьный код');
                }

                if(this.errors && this.errors.length > 0) {
                    return
                }

                axios.put(`/api/stock/category/${id}/update`,
                {
                    id: id,
                    name: this.name,
                    slug: this.slug,
                })
                .then(response => (
                    this.$router.push({name: 'StockCategory', params: {category_id: id }})
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
                    axios.delete(`/api/stock/category/${id}/delete`)
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
                }
            },
        },
    }
</script>