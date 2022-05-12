<template>
    <div class="offcanvas offcanvas-end show" tabindex="-1" id="offcanvasRight" role="dialog">
        <div class="offcanvas-header">
            <h4 id="offcanvasRightLabel" class="m-0">Ключ активации</h4>
            <button @click="$parent.closeOffcanvas()" type="button" class="btn-close text-reset"></button>
        </div>
        <div class="offcanvas-body">
            <div>
                <input v-model="activation_key" type="text" class="form-control mb-2" />
                
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
                activation_key: '',
            }
        },
        created() {
            this.activation_key = this.production.activation_key
        },
        methods: {
            save() {
                axios.put(`/api/production/${this.production.id}/update`, {
                    activation_key: this.activation_key
                })
                .then(response => {
                    this.$parent.loadProduction()
                    this.$parent.views.changeActivationKey = false
                    this.$parent.views.backdrop = false
                })
            }
        }
    }
</script>
