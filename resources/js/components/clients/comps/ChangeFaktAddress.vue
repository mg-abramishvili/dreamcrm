<template>
    <div class="offcanvas offcanvas-end show" tabindex="-1" id="offcanvasRight" role="dialog">
        <div class="offcanvas-header">
            <h4 id="offcanvasRightLabel" class="m-0">Фактический адрес</h4>
            <button @click="$parent.closeOffcanvas()" type="button" class="btn-close text-reset"></button>
        </div>
        <div class="offcanvas-body">
            <div>
                <input v-model="fakt_address" type="text" class="form-control mb-2" />
                
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
                fakt_address: '',
            }
        },
        created() {
            this.fakt_address = this.client.fakt_address
        },
        methods: {
            save() {
                axios.put(`/api/client/${this.client.id}/update`, {
                    fakt_address: this.fakt_address
                })
                .then(response => {
                    this.$parent.loadClient()
                    this.$parent.closeOffcanvas()
                })
            }
        }
    }
</script>
