<template>
    <div>
        <p>{{ productionItem }}</p>

        <select v-model="selected.stock_item">
            <option v-for="item in stockItems" :value="item.id">{{ item.name }}</option>
        </select>

        <button @click="save()" class="btn btn-sm btn-outline-primary">OK</button>
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
            }
        }
    },
    created() {
        this.loadStockItems()
    },
    methods: {
        loadStockItems() {
            axios.get('/api/stock/items')
            .then(response => {
                this.stockItems = response.data

                if(this.productionItem) {
                    this.selected.stock_item = this.productionItem.stock_item_id
                }
            })
        },
        save() {
            axios.post(`/api/production-item/${this.productionItem.id}/replace`, {
                stock_item: this.selected.stock_item
            })
            .then(response => {
                console.log(response.data)
                this.$parent.loadProduction()
                this.$parent.views.changeProductionItem = false
            })
        },
    }
}
</script>
