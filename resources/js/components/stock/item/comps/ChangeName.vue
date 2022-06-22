<template>
    <div class="offcanvas offcanvas-end show" tabindex="-1" id="offcanvasRight" role="dialog">
        <div class="offcanvas-header">
            <h4 id="offcanvasRightLabel" class="m-0">Название</h4>
            <button @click="$parent.closeOffcanvas()" type="button" class="btn-close text-reset"></button>
        </div>
        <div class="offcanvas-body">
            <div>
                <input v-model="name" type="text" class="form-control mb-2" />
                
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
                name: '',
            }
        },
        created() {
            this.name = this.stockItem.name
        },
        methods: {
            save() {
                axios.put(`/api/stock/item/${this.stockItem.id}/update`, {
                    name: this.name
                })
                .then(response => {
                    this.$parent.loadStockItem()
                    this.$parent.closeOffcanvas()
                })
            }
        }
    }
</script>