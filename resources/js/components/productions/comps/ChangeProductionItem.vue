<template>
    <div class="offcanvas offcanvas-end show" tabindex="-1" id="offcanvasRight" role="dialog">
        <div class="offcanvas-header">
            <h4 id="offcanvasRightLabel" class="m-0">Замена</h4>
            <button @click="$parent.closeOffcanvas()" type="button" class="btn-close text-reset"></button>
        </div>
        <div class="offcanvas-body">
            <div>
                <input :value="stockItems.find(item => item.id === productionItem.stock_item.id).name" type="text" disabled class="form-control mb-2">

                <select v-model="selected.stock_item" class="form-select mb-2">
                    <template v-for="item in stockItems">
                        <option v-if="item.id !== productionItem.stock_item.id" :value="item.id">{{ item.name }}</option>
                    </template>
                </select>

                <input v-model="quantity" type="number" min="1" class="form-control mb-2">

                <button @click="save()" v-if="views.saveButton" class="btn btn-primary">Сохранить</button>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ['productionItem'],
    data() {
        return {
            stockItems: [],

            selected: {
                stock_item: '',
            },

            quantity: 0,

            views: {
                saveButton: true,
            },
        }
    },
    created() {
        this.loadStockItems()
    },
    methods: {
        loadStockItems() {
            axios.get(`/api/stock/category/${this.productionItem.stock_item.category_id}`)
            .then(response => {
                this.stockItems = response.data.data.items

                this.selected.stock_item = this.productionItem.stock_item_id

                this.quantity = this.productionItem.reserves.reduce((acc, reserve) => { return acc + reserve.quantity }, 0) + this.productionItem.stock_needs.reduce((acc, need) => { return acc + need.quantity }, 0)
            })
        },
        save() {
            this.views.saveButton = false

            axios.post(`/api/production-item/${this.productionItem.id}/replace`, {
                stock_item: this.selected.stock_item,
                quantity: this.quantity
            })
            .then(response => {
                this.$parent.loadProduction()
                this.$parent.closeOffcanvas()
            })
            .catch(errors => {
                this.views.saveButton = true
            })
        },
    }
}
</script>
