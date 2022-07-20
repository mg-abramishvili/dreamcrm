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
                            <p class="d-flex align-items-center">
                                <strong class="me-2">Нехватка:</strong>

                                <span>
                                    <template v-if="stockItem.needs_sum && stockItem.needs_sum > 0">
                                        <strong class="text-danger">{{ stockItem.needs_sum }}</strong>
                                    </template>
                                    <template v-else>
                                        &mdash;
                                    </template>
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="tab-pane" :class="{'active': selected.tab == 'balances'}" role="tabpanel">
                    <button @click="addBalance()" class="btn btn-outline-primary ms-1">Добавить</button>

                    <AddBalance v-if="views.addBalance" :item="stockItem"></AddBalance>

                    <EditBalance v-if="views.editBalance" :item="stockItem" :balance="views.editBalance"></EditBalance>
                    
                    <ViewBalance v-if="views.viewBalance" :balance="views.viewBalance"></ViewBalance>

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
                                    <button @click="viewBalance(balance)" class="btn btn-sm btn-outline-info">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
                                            <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"/>
                                            <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"/>
                                        </svg>
                                    </button>
                                    <button @click="editBalance(balance)" class="btn btn-sm btn-outline-warning">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                            <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                                            <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/>
                                        </svg>
                                    </button>
                                    <button @click="delBalance(balance.id)" class="btn btn-sm btn-outline-danger">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                                            <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                                            <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                                        </svg>
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
    import ViewBalance from './balances/View.vue'
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
                    viewBalance: '',
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
            addBalance(balance) {
                this.views.backdrop = true
                this.views.addBalance = true
            },
            editBalance(balance) {
                this.views.backdrop = true
                this.views.editBalance = balance
            },
            viewBalance(balance) {
                this.views.backdrop = true
                this.views.viewBalance = balance
            },
            changePanel(panel) {
                this.views.backdrop = true
                this.views.changePanel = panel
            },
            closeOffcanvas() {
                this.views.backdrop = false
                this.views.addBalance = false
                this.views.editBalance = ''
                this.views.viewBalance = ''
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
            ViewBalance,
            ChangeName,
            ChangeCategory,
            Loader
        }
    }
</script>