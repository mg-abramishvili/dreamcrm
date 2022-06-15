<template>
    <div class="offcanvas offcanvas-end show" tabindex="-1" id="offcanvasRight" role="dialog">
        <div class="offcanvas-header">
            <h4 id="offcanvasRightLabel" class="m-0">Данные для доставки</h4>
            <button @click="$parent.closeOffcanvas()" type="button" class="btn-close text-reset"></button>
        </div>
        <div class="offcanvas-body">
            <div>
                <input v-model="supply_info" type="text" class="form-control mb-2">
                
                <button @click="save()" class="btn btn-primary">Сохранить</button>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['production'],
        data() {
            return {
                supply_info: '',
            }
        },
        created() {
            this.supply_info = this.production.supply_info
        },
        methods: {
            save() {
                axios.put(`/api/production/${this.production.id}/update`, {
                    supply_info: this.supply_info
                })
                .then(response => {
                    this.$parent.loadProduction()
                    this.$parent.closeOffcanvas()
                })
            }
        }
    }
</script>
