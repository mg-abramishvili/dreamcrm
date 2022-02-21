<template>
    <div class="catalog-page">

        <div class="row align-items-center mb-4">
            <div class="col-12 col-lg-6">
                <h1 class="h3 m-0" style="position:relative;">Каталог</h1>
            </div>
            <div class="col-12 col-lg-6 text-end">
                <router-link :to="{name: 'CatalogSearch'}" class="catalog-page-search-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search align-middle"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                </router-link>
                <router-link :to="{name: 'CatalogCategoryCreate'}" class="btn btn-primary">Создать категорию</router-link>
                <router-link :to="{name: 'CatalogTypes'}" class="btn btn-outline-primary">Управление типами</router-link>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-lg-6">
                <div class="card">
                    <div class="card-body p-0">
                        <router-link :to="{name: 'CatalogBoxes'}" class="p-4">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-folder align-middle"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path></svg>
                            <span>Корпуса</span>
                        </router-link>
                    </div>
                </div>
            </div>

            <div v-for="category in categories" :key="'category_' + category.id" class="col-12 col-lg-6">
                <div class="card">
                    <div class="card-body p-0">
                        <router-link :to="{name: 'CatalogCategory', params: {id: category.id}}" class="p-4">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-folder align-middle"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path></svg>
                            <span>{{ category.name }}</span>
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

                itemsToBuy: [],
            }
        },
        created() {
            this.loadCategories()
            // this.loadItemsToBuy()
        },
        methods: {
            loadCategories() {
                axios
                .get('/api/catalog/categories')
                .then(response => (
                    this.categories = response.data
                ));
            },
        }
    }
</script>