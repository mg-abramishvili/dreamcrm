<template>
    <div v-if="category.name && category.name.length > 0" class="catalog-page">
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
            <div class="col-12 col-lg-6 text-end">
                <router-link :to="{name: 'StockItemCreate', params: {category_id: category.id}}" class="btn btn-primary">Добавить</router-link>
            </div>
        </div>

        <div class="card">
            <table class="table">
                <thead>
                    <tr>
                        <th @click="orderByName()">Наименование</th>
                        <th @click="orderByAmount()" class="text-center">Остаток</th>
                        <th @click="orderByPrice()">Цена</th>
                    </tr>
                </thead>
                <tbody>
                    <tr @click="goTo(item.id)" v-for="item in items" :key="'item_' + item.id">
                        <td class="align-middle">
                            <a>{{ item.name }}</a>
                        </td>
                        <td class="align-middle text-center">
                            <span class="fw-bold" :class="{'text-danger': item.amount < 0, 'text-success': item.amount > 0}">
                                {{ item.amount }}
                            </span>
                        </td>
                        <td class="align-middle">
                            {{ item.price | currency }} ₽
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        
    </div>
</template>

<script>
    export default {
        data() {
            return {
                category: {},
                items: [],
            }
        },
        created() {
            this.loadCategory()
        },
        methods: {
            loadCategory() {
                axios
                .get(`/api/stock/category/${this.$route.params.category_id}`)
                .then(response => (
                    this.category = response.data,
                    this.items = response.data.items
                ));
            },
            goTo(id) {
                this.$router.push({name: 'StockItemEdit', params: {id: id}})
            },
            orderByName() {
                this.items = this.items.sort((a, b) => a.name.localeCompare(b.name))
            },
            orderByAmount() {
                this.items = this.items.sort((a, b) => a.amount - b.amount)
            },
            orderByPrice() {
                this.items = this.items.sort((a, b) => a.price - b.price)
            },
        },
    }
</script>
<style scoped>
    .table tr:hover {
        cursor: pointer;
    }
</style>