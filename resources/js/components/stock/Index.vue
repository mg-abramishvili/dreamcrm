<template>
    <div class="stock-page">
        <div class="card card-bordered">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-12 col-lg-3">
                        <h1 class="h3 m-0" style="position:relative;">Склад</h1>
                    </div>
                    <div class="col-12 col-lg-6 text-center">
                        <router-link :to="{name: 'Reserves'}" class="btn btn-outline-primary mx-1">Зарезервировано</router-link>
                        <router-link :to="{name: 'StockNeeds'}" class="btn btn-outline-primary mx-1">Закупки</router-link>
                    </div>
                    <div class="col-12 col-lg-3 text-end">
                        <!-- <router-link :to="{name: 'StockSearch'}" class="catalog-page-search-icon stock-page-search-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search align-middle"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                        </router-link> -->
                        <router-link :to="{name: 'StockCategoryCreate'}" class="btn btn-primary">Создать категорию</router-link>
                    </div>
                </div>
            </div>
        </div>

        <template v-if="views.loading == false">
            <div class="row">
                <div v-for="category in categories" :key="'category_' + category.id" class="col-12 col-lg-4">
                    <div class="card">
                        <div class="card-body p-0">
                            <router-link :to="{name: 'StockCategory', params: {category_id: category.id}}" class="stock-list-item p-4">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-folder align-middle"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path></svg>
                                <span>{{ category.name }}</span>
                            </router-link>
                        </div>
                    </div>
                </div>
            </div>
        </template>
        <template v-else>
            <Loader></Loader>
        </template>
    </div>
</template>

<script>
    import Loader from '../Loader.vue'
    
    export default {
        data() {
            return {
                categories: [],

                itemsToBuy: [],

                views: {
                    loading: true,
                }
            }
        },
        created() {
            this.loadCategories()
            // this.loadItemsToBuy()
        },
        methods: {
            loadCategories() {
                axios
                .get('/api/stock/categories')
                .then(response => (
                    this.categories = response.data,
                    this.views.loading = false
                ));
            },
            loadItemsToBuy() {
                axios
                .get('/api/stock/items-to-buy')
                .then(response => (
                    this.itemsToBuy = response.data
                ));
            }
        },
        components: {
            Loader
        }
    }
</script>