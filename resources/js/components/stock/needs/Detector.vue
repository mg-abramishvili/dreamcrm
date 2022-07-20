<template>
    <div class="stock-page">
        <loader v-if="views.loading"></loader>

        <div v-if="!views.loading" class="card card-bordered">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-12 col-lg-12">
                        <h1 class="h3 m-0">
                            <strong>
                                Куда распределить свободный остаток <span class="text-primary">{{ stockItem.name }}</span>?
                            </strong>
                        </h1>
                    </div>
                </div>
            </div>
        </div>

        <div v-if="!views.loading">
            <p>Свободное количество: <strong class="text-primary">{{ balancesQuantityLeft }}</strong></p>

            <ul class="list-group">
                <li v-for="need in needs" class="list-group-item">
                    <div class="row align-items-center">
                        <div class="col-8">
                            {{ need.production }}
                        </div>
                        <div class="col-2">
                            <span class="text-muted text-sm">нехватка:</span><br>
                            <strong>{{ need.quantity }}</strong>
                        </div>
                        <div class="col-2">
                            <span class="text-muted text-sm">дать:</span>
                            <input @change="setQuantity(need.id, $event)" type="number" min="0" class="form-control">
                        </div>
                    </div>
                </li>
            </ul>

            <button @click="save()" class="btn btn-lg btn-primary mt-2 mb-4">Применить</button>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            stockItem: {},
            needs: [],

            selected: {
                needs: {},
            },

            views: {
                loading: true,
            }
        }
    },
    computed: {
        selectedTotalQuantity() {
            return this.selected.needs.reduce((acc, need) => { return acc + need.quantity }, 0)
        },
        balancesQuantityLeft() {
            let calc = this.stockItem.balances_sum_quantity - this.selectedTotalQuantity
            
            if(calc >= 0) {
                return calc
            } else {
                return 0
            }
        },
    },
    created() {
        this.loadStockItem()
        this.loadNeeds()
    },
    methods: {
        loadStockItem() {
            axios.get(`/api/stock/item/${this.$route.params.stock_item_id}`)
            .then(response => {
                this.stockItem = response.data.data
            })
        },
        loadNeeds() {
            axios.get(`/api/stock/needs-by-stock-item/${this.$route.params.stock_item_id}`)
            .then(response => {
                this.needs = response.data.data

                this.selected.needs = response.data.data.map(({id, quantity}) => ({id: id, quantity: 0}))

                this.views.loading = false
            })
        },
        setQuantity(id, event) {
            let need = this.selected.needs.find(n => n.id === id)

            if(event.target.value < need.quantity) {
                need.quantity = parseInt(event.target.value)
            }

            if(this.balancesQuantityLeft > 0) {
                need.quantity = parseInt(event.target.value)
            }

            event.target.value = need.quantity
        },
        save() {
            this.views.loading = true
            
            axios.post(`/api/stock/needs-detector/`, {
                needs: this.selected.needs
            })
            .then(response => {
                this.loadStockItem()
                this.loadNeeds()
            })
        },
    },
}
</script>
