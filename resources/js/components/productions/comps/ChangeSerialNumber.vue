<template>
    <div class="offcanvas offcanvas-end show" tabindex="-1" id="offcanvasRight" role="dialog">
        <div class="offcanvas-header">
            <h4 id="offcanvasRightLabel" class="m-0">Серийный номер</h4>
            <button @click="$parent.closeOffcanvas()" type="button" class="btn-close text-reset"></button>
        </div>
        <div class="offcanvas-body">
            <div>
                <input v-model="serial_number" type="text" class="form-control mb-2" />
                
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
                serial_number: '',
            }
        },
        created() {
            this.serial_number = this.production.serial_number
        },
        methods: {
            save() {
                axios.put(`/api/production/${this.production.id}/update`, {
                    serial_number: this.serial_number
                })
                .then(response => {
                    this.$parent.loadProduction()
                    this.$parent.closeOffcanvas()
                })
            }
        }
    }
</script>
