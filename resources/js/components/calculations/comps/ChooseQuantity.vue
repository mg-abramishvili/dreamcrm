<template>
    <div class="mb-4">
        <div class="calculation-left-block-main-label">
            <strong>Количество</strong>
        </div>

        <input v-model="quantity" type="number" class="form-control">

        <div class="mt-4">
            <button @click="goBack()" class="btn btn-outline-primary">Назад</button>
            <button @click="goNext()" class="btn btn-outline-primary">Далее</button>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                quantity: 1,
            }
        },
        watch: {
            quantity: {
                handler() {
                    this.$parent.quantity = this.quantity
                }
            }
        },
        methods: {
            goBack() {
                this.$parent.goToStep('catalog')
            },
            goNext() {
                this.$parent.goToStep('delivery')
            },
        },
    }
</script>