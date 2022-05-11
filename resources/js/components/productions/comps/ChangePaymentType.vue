<template>
    <div class="offcanvas offcanvas-end show" tabindex="-1" id="offcanvasRight" role="dialog">
        <div class="offcanvas-header">
            <h4 id="offcanvasRightLabel" class="m-0">Оплата</h4>
            <button @click="$parent.closeOffcanvas()" type="button" class="btn-close text-reset"></button>
        </div>
        <div class="offcanvas-body">
            <div>
                <select v-model="payment_type" class="form-select mb-2">
                    <option value="Предоплата 100%">Предоплата 100%</option>
                </select>
                
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
                payment_type: '',
            }
        },
        created() {
            this.payment_type = this.production.payment_type
        },
        methods: {
            save() {
                axios.put(`/api/production/${this.production.id}/update`, {
                    payment_type: this.payment_type
                })
                .then(response => {
                    this.$parent.loadProduction()
                    this.$parent.views.changePaymentType = false
                    this.$parent.views.backdrop = false
                })
            }
        }
    }
</script>
