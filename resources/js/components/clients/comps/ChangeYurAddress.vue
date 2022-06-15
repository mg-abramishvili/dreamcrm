<template>
    <div class="offcanvas offcanvas-end show" tabindex="-1" id="offcanvasRight" role="dialog">
        <div class="offcanvas-header">
            <h4 id="offcanvasRightLabel" class="m-0">Юр. адрес</h4>
            <button @click="$parent.closeOffcanvas()" type="button" class="btn-close text-reset"></button>
        </div>
        <div class="offcanvas-body">
            <div>
                <input v-model="yur_address" type="text" class="form-control mb-2" />
                
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
                yur_address: '',
            }
        },
        created() {
            this.yur_address = this.client.yur_address
        },
        methods: {
            save() {
                axios.put(`/api/client/${this.client.id}/update`, {
                    yur_address: this.yur_address
                })
                .then(response => {
                    this.$parent.loadClient()
                    this.$parent.closeOffcanvas()
                })
            }
        }
    }
</script>
