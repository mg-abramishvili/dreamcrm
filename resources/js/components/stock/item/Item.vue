<template>
    <div class="stock-page stock-item-page">
        <div class="card card-bordered">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-12 col-lg-6">
                        <h1 class="h3 m-0">
                            <router-link v-if="stockItem.category && stockItem.category.id" :to="{name: 'StockCategory', params: { category_id: stockItem.category.id }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left align-middle me-2"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                            </router-link>
                            <router-link v-else :to="{name: 'Stock'}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left align-middle me-2"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                            </router-link>
                            <strong>
                                {{ stockItem.name }}
                            </strong>
                        </h1>
                    </div>
                    <div class="col-12 col-lg-6 text-end">
                        <button @click="del(stockItem.id)" class="btn btn-outline-danger">Удалить</button>
                    </div>
                </div>
            </div>
        </div>

        <Loader v-if="views.loading"></Loader>

        <div v-if="!views.loading" class="tab">
            <ul class="nav nav-tabs" role="tablist">
                <li class="nav-item">
                    <a @click="selectTab('general')" class="nav-link" :class="{'active': selected.tab == 'general'}" role="tab">Общая информация</a>
                </li>
                <li class="nav-item">
                    <a @click="selectTab('balances')" class="nav-link" :class="{'active': selected.tab == 'balances'}" role="tab">Остатки <template><span class="text-muted">({{ stockItem.balances_sum_quantity }} шт)</span></template></a>
                </li>
                <li class="nav-item">
                    <a @click="selectTab('productions')" class="nav-link" :class="{'active': selected.tab == 'productions'}" role="tab">Производства <template v-if="stockItem.productions && stockItem.productions.length"><span class="text-muted">({{ stockItem.productions.length }})</span></template></a>
                </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane" :class="{'active': selected.tab == 'general'}" role="tabpanel">
                    <div class="row">
                        <div class="col-12 col-lg-6">
                            <p class="d-flex align-items-center">
                                <strong class="me-2">Название:</strong>

                                <span>{{ stockItem.name }}</span>

                                <small @click="changePanel('name')" class="cursor-pointer text-muted fw-normal ms-2" style="opacity: 50%;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="d-block feather feather-edit align-middle me-2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                </small>

                                <ChangeName v-if="views.changePanel == 'name'" :stockItem="stockItem" />
                            </p>

                            <p class="d-flex align-items-center">
                                <strong class="me-2">Категория:</strong>

                                <span>{{ stockItem.category.name }}</span>

                                <small @click="changePanel('category')" class="cursor-pointer text-muted fw-normal ms-2" style="opacity: 50%;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="d-block feather feather-edit align-middle me-2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                </small>

                                <ChangeCategory v-if="views.changePanel == 'category'" :stockItem="stockItem" />
                            </p>
                        </div>
                        <div class="col-12 col-lg-6">
                            
                        </div>
                    </div>
                </div>
                <div class="tab-pane" :class="{'active': selected.tab == 'balances'}" role="tabpanel">
                    <button @click="views.addBalance = true" class="btn btn-outline-primary ms-1">Добавить</button>

                    <AddBalance v-if="views.addBalance" :item="stockItem"></AddBalance>

                    <EditBalance v-if="views.editBalance" :item="stockItem" :balance="views.editBalance"></EditBalance>

                    <table v-if="stockItem.balances && stockItem.balances.length" class="table">
                        <thead>
                            <tr>
                                <th>Дата</th>
                                <th>Кол-во</th>
                                <th>Рубли</th>
                                <th>Доллары</th>
                                <th>Стоимость</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="balance in stockItem.balances">
                                <td>{{ balance.date | date }}</td>
                                <td>{{ balance.quantity }} шт</td>
                                <td>{{ balance.pre_rub | currency }} ₽</td>
                                <td>${{ balance.pre_usd | currency }} <small class="fw-normal text-muted d-block">курс {{ balance.usd_kurs }}</small></td>
                                <td>{{ balance.price | currency }} ₽</td>
                                <td class="text-center">
                                    <button @click="editBalance(balance)" class="btn btn-sm btn-outline-warning">
                                        <svg data-v-766bff82="" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit align-middle"><path data-v-766bff82="" d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path data-v-766bff82="" d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                    </button>
                                    <button @click="delBalance(balance.id)" class="btn btn-sm btn-outline-danger">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2 align-middle"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <p v-else>Нет поступлений.</p>
                </div>
                <div class="tab-pane" :class="{'active': selected.tab == 'productions'}" role="tabpanel">
                    <table v-if="stockItem.productions && stockItem.productions.length" class="table">
                        <thead>
                            <tr>
                                <th>Производство</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="production in stockItem.productions">
                                <td>{{ production.name }}</td>
                                <td class="text-center">
                                    
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <p v-else>Нет производств.</p>
                </div>
            </div>
        </div>

        <div v-if="views.backdrop" @click="closeOffcanvas()" class="offcanvas-backdrop fade show"></div>
    </div>
</template>

<script>
    import Loader from '../../Loader.vue'
    import AddBalance from './balances/Add.vue'
    import EditBalance from './balances/Edit.vue'
    import ChangeName from './comps/ChangeName.vue'
    import ChangeCategory from './comps/ChangeCategory.vue'

    export default {
        data() {
            return {
                stockItem: {},

                selected: {
                    tab: 'general',
                },

                views: {
                    loading: true,
                    changePanel: '',
                    addBalance: false,
                    editBalance: '',
                }
            }
        },
        created() {
            this.loadStockItem()
        },
        methods: {
            loadStockItem() {
                axios.get(`/api/stock/item/${this.$route.params.id}`)
                    .then((response => {
                        this.stockItem = response.data.data

                        this.views.loading = false
                    }))
            },
            selectTab(tab) {
                this.selected.tab = tab
            },
            editBalance(balance) {
                this.views.backdrop = true
                this.views.editBalance = balance
            },
            changePanel(panel) {
                this.views.backdrop = true
                this.views.changePanel = panel
            },
            closeOffcanvas() {
                this.views.backdrop = false
                this.views.editBalance = ''
                this.views.changePanel = ''
            },
            del(id) {
                if (confirm("Точно удалить?")) {
                    axios.delete(`/api/stock/item/${id}/delete`)
                    .then(response => (
                        this.$router.push({name: 'StockCategory', params: {category_id: this.category }})
                    ))
                    .catch(error => {
                        return this.$swal({
                            text: error.response.data,
                            icon: 'error',
                        })
                    })
                }
            },
            delBalance(id) {
                if (confirm("Точно удалить?")) {
                    axios.delete(`/api/stock/balance/${id}/delete`)
                    .then(response => (
                        this.loadStockItem()
                    ))
                    .catch(error => {
                        return this.$swal({
                            text: error.response.data,
                            icon: 'error',
                        })
                    })
                }
            }
        },
        components: {
            AddBalance,
            EditBalance,
            ChangeName,
            ChangeCategory,
            Loader
        }
    }
</script>