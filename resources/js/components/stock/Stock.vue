<template>
    <div class="catalog-page stock-page">
        <!-- <div class="row">
            <div class="col-12 col-lg-3 d-flex">
                <div class="card flex-fill">
                    <div class="card-body py-4">
                        <div class="d-flex align-items-center h-100">
                            <div class="flex-grow-1">
                                <h2 class="mb-0">Склад</h2>
                            </div>
                            <div class="d-inline-block ms-3">
                                <div class="stat">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-shopping-bag align-middle text-danger"><line x1="16.5" y1="9.4" x2="7.5" y2="4.21"></line><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line></svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-3 d-flex">
                <div class="card flex-fill">
                    <div class="card-body py-4">
                        <div class="d-flex align-items-center h-100">
                            <div class="flex-grow-1">
                                <h2 class="mb-0">
                                    Закупка
                                </h2>
                            </div>
                            <div class="d-inline-block ms-3">
                                <div v-if="itemsToBuy.length > 0" class="stat">
                                    <h2 class=" text-center text-danger lh-1 m-0">{{ itemsToBuy.length }}</h2>
                                </div>
                                <div v-else class="stat">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-shopping-bag align-middle text-danger"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path><line x1="3" y1="6" x2="21" y2="6"></line><path d="M16 10a4 4 0 0 1-8 0"></path></svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
		</div> -->

        <div class="row align-items-center mb-4">
            <div class="col-12 col-lg-6">
                <h1 class="h3 m-0" style="position:relative;">Склад</h1>
            </div>
            <div class="col-12 col-lg-6 text-end">
                <!-- <router-link :to="{name: 'StockSearch'}" class="catalog-page-search-icon stock-page-search-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search align-middle"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                </router-link> -->
                <!-- <router-link :to="{name: 'StockCategoryCreate'}" class="btn btn-primary">Создать категорию</router-link> -->
            </div>
        </div>

        <div class="row">
            <div v-for="category in categories" :key="'category_' + category.id" class="col-12 col-lg-6">
                <div class="card">
                    <div class="card-body p-0">
                        <router-link :to="{name: 'StockCategory', params: {category_id: category.id}}" class="p-4">
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
            this.loadItemsToBuy()
        },
        methods: {
            loadCategories() {
                axios
                .get('/api/stock/categories')
                .then(response => (
                    this.categories = response.data
                ));
            },
            loadItemsToBuy() {
                axios
                .get('/api/stock/items-to-buy')
                .then(response => (
                    this.itemsToBuy = response.data
                ));
            }
        }
    }
</script>