<template>
    <div class="offcanvas offcanvas-end show" tabindex="-1" id="offcanvasRight" role="dialog">
        <div class="offcanvas-header">
            <h4 id="offcanvasRightLabel" class="m-0">Замена</h4>
            <button @click="$parent.closeOffcanvas()" type="button" class="btn-close text-reset"></button>
        </div>
        <div class="offcanvas-body">
            <div>
                <select v-model="selected.stock_item" class="form-select mb-2">
                    <option v-for="item in stockItems" :value="item.id">{{ item.name }}</option>
                </select>

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
            })
        },
        save() {
            this.views.saveButton = false

            axios.post(`/api/production-item/${this.productionItem.id}/replace`, {
                stock_item: this.selected.stock_item
            })
            .then(response => {
                this.$parent.loadProduction()
                this.$parent.closeOffcanvas()
                
                if(response.data && response.data > 0) {
                    this.$router.push({name: 'StockNeedsDetector', params: {stock_item_id: response.data }})
                }
            })
            .catch(errors => {
                this.views.saveButton = true
            })
        },
    }
}
</script>
