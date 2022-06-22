<template>
    <div class="offcanvas offcanvas-end show" tabindex="-1" id="offcanvasRight" role="dialog">
        <div class="offcanvas-header">
            <h4 id="offcanvasRightLabel" class="m-0">Название</h4>
            <button @click="$parent.closeOffcanvas()" type="button" class="btn-close text-reset"></button>
        </div>
        <div class="offcanvas-body">
            <div>
                <select v-model="selected.category" type="text" class="form-select mb-2">
                    <option v-for="category in categories" :value="category.id">{{ category.name }}</option>
                </select>
                
                <button @click="save()" class="btn btn-primary">Сохранить</button>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['stockItem'],
        data() {
            return {
                categories: [],

                selected: {
                    category: '',
                },
            }
        },
        created() {
            this.loadCategories()
        },
        methods: {
            loadCategories() {
                axios.get(`/api/stock/categories`)
                    .then((response => {
                        this.categories = response.data

                        this.selected.category = this.stockItem.category.id
                    }))
            },
            save() {
                axios.put(`/api/stock/item/${this.stockItem.id}/update`, {
                    category_id: this.selected.category
                })
                .then(response => {
                    this.$parent.loadStockItem()
                    this.$parent.closeOffcanvas()
                })
            }
        }
    }
</script>