<template>
    <div class="catalog-page">
        <div class="row align-items-center mb-4">
            <div class="col-12 col-lg-6">
                <h1 class="h3 m-0" style="position:relative;">Каталог</h1>
            </div>
            <div class="col-12 col-lg-6 text-end">
                <router-link :to="{name: 'ElementCreate'}" class="btn btn-primary">Новый компонент</router-link>
            </div>
        </div>

        <div class="row">
            <div v-for="category in categories" :key="'category_' + category.id" class="col-12 col-lg-6">
                <div class="card">
                    <div class="card-body p-0">
                        <router-link :to="{name: 'Elements', params: {category: category.id}}" class="p-4">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-folder align-middle"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path></svg>
                            {{ category.name }}
                        </router-link>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                categories: [],
            }
        },
        created() {
            this.loadCategories()
        },
        methods: {
            loadCategories() {
                axios
                .get('/api/categories')
                .then(response => (
                    this.categories = response.data
                ));
            }
        }
    }
</script>