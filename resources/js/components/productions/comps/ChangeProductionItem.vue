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

                <button @click="save()" class="btn btn-primary">Сохранить</button>
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
            }
        }
    },
    created() {
        this.loadStockItems()
    },
    methods: {
        loadStockItems() {
            axios.get(`/api/stock/category/${this.productionItem.stock_item.category_id}`)
            .then(response => {
                this.stockItems = response.data

                this.selected.stock_item = this.productionItem.stock_item_id
            })
        },
        save() {
            axios.post(`/api/production-item/${this.productionItem.id}/replace`, {
                stock_item: this.selected.stock_item
            })
            .then(response => {
                console.log(response.data)
                this.$parent.loadProduction()
                this.$parent.closeOffcanvas()
            })
        },
    }
}
</script>
