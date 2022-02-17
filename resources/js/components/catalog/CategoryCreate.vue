<template>
    <div>
        <div class="row align-items-center mb-4">
            <div class="col-12 col-lg-6">
                <h1 class="h3 m-0">Новая категория</h1>
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
                slug: '',

                errors: [],
            }
        },
        methods: {
            save() {
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
                
                axios
                .post(`/api/catalog/categories`, {
                    name: this.name,
                    slug: this.slug
                })
                .then(response => (
                    this.$router.push({name: 'CatalogCategory', params: {id: response.data }})
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
        },
    }
</script>