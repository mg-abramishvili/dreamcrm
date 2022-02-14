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
                    <router-link :to="{name: 'StockCategoryEdit', params: {category_id: $route.params.category_id}}" class="btn btn-sm ms-1" style="opacity: 0.5">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit align-middle"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                    </router-link>
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
                        <th>Наименование</th>
                        <th class="text-center">Общий остаток</th>
                        <th>Остатки</th>
                        <th>Мин. цена</th>
                    </tr>
                </thead>
                <tbody>
                    <tr @click="goTo(item.id)" v-for="item in items" :key="'item_' + item.id">
                        <td class="align-middle">
                            <a>{{ item.name }}</a>
                        </td>
                        <td class="align-middle text-center">
                            <span v-if="item.balances.length" class="badge rounded-pill" :class="{'bg-danger': item.balances.reduce((acc, balance) => acc + parseInt(balance.quantity), 0) < 0, 'bg-secondary': item.balances.reduce((acc, balance) => acc + parseInt(balance.quantity), 0) == 0, 'bg-success': item.balances.reduce((acc, balance) => acc + parseInt(balance.quantity), 0) > 0}">
                                {{ item.balances.reduce((acc, balance) => acc + parseInt(balance.quantity), 0) }}
                            </span>
                        </td>
                        <td class="align-middle">
                            <ul v-if="item.balances.length" class="m-0 p-0" style="list-style: none;">
                                <li v-for="balance in item.balances" :key="'balance_' + balance.id">
                                    {{ balance.quantity }} шт. | {{ balance.price | currency }} ₽ | {{ balance.created_at | formatDate }}
                                </li>
                            </ul>
                        </td>
                        <td class="align-middle">
                            <template v-if="item.balances.length">
                                {{ item.balances[0].price | currency }} ₽
                            </template>
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
                this.$router.push({name: 'StockItem', params: {id: id}})
            },
            orderBy(field) {
                if(field == 'name') {
                    this.items = this.items.sort((a, b) => a.name.localeCompare(b.name))
                    return
                }
                if(field == 'amount') {
                    this.items = this.items.sort((a, b) => a.amount - b.amount)
                    return
                }
                if(field == 'price') {
                    this.items = this.items.sort((a, b) => a.price - b.price)
                    return
                }
            },
        },
    }
</script>
<style scoped>
    .table tr:hover {
        cursor: pointer;
    }
</style>