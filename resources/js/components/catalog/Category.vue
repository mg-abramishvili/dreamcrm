<template>
    <div class="catalog-page">
        <div class="card card-bordered">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-12 col-lg-6">
                        <h1 class="h3 m-0">
                            <router-link :to="{name: 'Catalog'}" class="back-arrow">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left align-middle me-2"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                            </router-link>
                            <strong>
                                {{ category.name }}
                            </strong>
                            <router-link :to="{name: 'CatalogCategoryEdit', params: {id: $route.params.id}}" class="btn btn-sm ms-1" style="opacity: 0.5">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit align-middle"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                            </router-link>
                        </h1>
                    </div>
                    <div class="col-12 col-lg-6 text-end">
                        <router-link :to="{name: 'CatalogItemCreate', params: {category_id: category.id}}" class="btn btn-primary">Добавить</router-link>
                    </div>
                </div>
            </div>
        </div>

        <div v-if="category.items && category.items.length" class="card">
            <table class="table table-hover dataTable">
                <thead>
                    <tr>
                        <th>
                            <span class="d-inline-block align-middle">Наименование</span>
                            
                            <button @click="orderBy('name', 'asc')" class="btn btn-order-arrow">&uarr;</button>
                            <button @click="orderBy('name', 'desc')" class="btn btn-order-arrow">&darr;</button>
                        </th>
                        <th>
                            <span class="d-inline-block align-middle">Цена</span>

                            <button @click="orderBy('price', 'asc')" class="btn btn-order-arrow">&uarr;</button>
                            <button @click="orderBy('price', 'desc')" class="btn btn-order-arrow">&darr;</button>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr @click="goTo(item.id)" v-for="item in category.items" :key="'item_' + item.id">
                        <td class="align-middle">
                            <a>{{ item.name }}</a>
                        </td>
                        <td class="align-middle">
                            <template v-if="item.price > 0">
                                {{ item.price | currency }} ₽
                            </template>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <Loader v-else></Loader>
    </div>
</template>

<script>
    import Loader from '../Loader.vue'

    export default {
        data() {
            return {
                category: {},

                order: {
                    field: 'name',
                    direction: 'asc'
                }
            }
        },
        created() {
            this.loadCategory()
        },
        methods: {
            loadCategory() {
                axios
                .get(`/api/catalog/category/${this.$route.params.id}`)
                .then(response => (
                    this.category = response.data,
                    this.orderByNameAsc()
                ));
            },
            goTo(id) {
                this.$router.push({name: 'CatalogItemEdit', params: {id: id}})
            },
            orderBy(field, direction) {
                if(field == 'name') {
                    if(direction == 'asc') {
                        return this.orderByNameAsc()
                    }
                    if(direction == 'desc') {
                        return this.orderByNameDesc()
                    }
                }

                if(field == 'price') {
                    if(direction == 'asc') {
                        return this.orderByPriceAsc()
                    }
                    if(direction == 'desc') {
                        return this.orderByPriceDesc()
                    }
                }
            },
            orderByNameAsc() {
                this.order.field = 'name'
                this.order.direction = 'asc'

                this.category.items.sort(function(a, b) {
                    return a.name.localeCompare(b.name, undefined, {
                        numeric: true,
                        sensitivity: 'base'
                    })
                })
            },
            orderByNameDesc() {
                this.order.field = 'name'
                this.order.direction = 'desc'

                this.category.items.sort(function(a, b) {
                    return b.name.localeCompare(a.name, undefined, {
                        numeric: true,
                        sensitivity: 'base'
                    })
                })
            },
            orderByPriceAsc() {
                this.order.field = 'price'
                this.order.direction = 'asc'

                this.category.items.sort((a, b) => a.price - b.price)
            },
            orderByPriceDesc() {
                this.order.field = 'price'
                this.order.direction = 'desc'

                this.category.items.sort((a, b) => b.price - a.price)
            },
        },
        components: {
            Loader
        },
    }
</script>