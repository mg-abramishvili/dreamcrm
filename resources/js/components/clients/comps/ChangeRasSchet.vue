<template>
    <div class="offcanvas offcanvas-end show" tabindex="-1" id="offcanvasRight" role="dialog">
        <div class="offcanvas-header">
            <h4 id="offcanvasRightLabel" class="m-0">Расчетный счет</h4>
            <button @click="$parent.closeOffcanvas()" type="button" class="btn-close text-reset"></button>
        </div>
        <div class="offcanvas-body">
            <div>
                <input v-model="ras_schet" type="text" class="form-control mb-2" />
                
                <button @click="save()" class="btn btn-primary">Сохранить</button>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['client'],
        data() {
            return {
                ras_schet: '',
            }
        },
        created() {
            this.ras_schet = this.client.ras_schet
        },
        methods: {
            save() {
                axios.put(`/api/client/${this.client.id}/update`, {
                    ras_schet: this.ras_schet
                })
                .then(response => {
                    this.$parent.loadClient()
                    this.$parent.closeOffcanvas()
                })
            }
        }
    }
</script>
