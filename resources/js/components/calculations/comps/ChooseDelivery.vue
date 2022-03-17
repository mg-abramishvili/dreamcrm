<template>
    <div class="mb-4">
        <div class="calculation-left-block-main-label">
            <strong>Доставка</strong>
        </div>

        <select v-model="selected.delivery.id" @change="changeDelivery()" class="form-select">
            <option v-for="delivery in deliveries" :key="'delivery_' + delivery.id" :value="delivery.id">{{ delivery.name }} &mdash; {{ delivery.price }}</option>
        </select>

        <DeliveryPEK v-if="selected.delivery.id == 3" :box="selected.box" :quantity="quantity" :delivery="selected.delivery"></DeliveryPEK>

        <div class="mt-4">
            <button @click="goBack()" class="btn btn-outline-primary">Назад</button>
            <button class="btn btn-outline-primary" disabled>Далее</button>
        </div>
    </div>
</template>

<script>
    import DeliveryPEK from './DeliveryPEK.vue'

    export default {
        props: ['selectedCatalogItems', 'quantity'],
        data() {
            return {
                deliveries: [],

                selected: {
                    delivery: {
                        id: '',
                        name: '',
                        price: '',
                        to: '',
                        directionFrom: '',
                        directionTo: '',
                        days: '',
                    },
                },
            }
        },
        watch: {
            selectedCatalogItems: {
                deep: true,
                handler() {
                    console.log('ok')
                    this.reset()
                }
            },
            quantity: {
                handler() {
                    this.reset()
                }
            },
        },
        created() {
            this.loadDeliveries()
        },
        methods: {
            loadDeliveries() {
                axios
                .get('/api/calculation/deliveries')
                .then((response => {
                    this.deliveries = response.data
                }));
            },
            changeDelivery() {
                let delivery = this.deliveries.find(delivery => delivery.id === this.selected.delivery.id)
            
                this.selected.delivery.name = delivery.name
                this.selected.delivery.price = delivery.price
                this.selected.delivery.to = ''
                this.selected.delivery.directionFrom = ''
                this.selected.delivery.directionTo = ''
                this.selected.delivery.days = ''
                
                this.$parent.selected.delivery = this.selected.delivery
            },
            reset() {
                this.selected.delivery.id = ''
                this.selected.delivery.name = ''
                this.selected.delivery.price = ''
                this.selected.delivery.to = ''
                this.selected.delivery.directionFrom = ''
                this.selected.delivery.directionTo = ''
                this.selected.delivery.days = ''

                this.$parent.selected.delivery.id = ''
                this.$parent.selected.delivery.name = ''
                this.$parent.selected.delivery.price = ''
                this.$parent.selected.delivery.to = ''
                this.$parent.selected.delivery.directionFrom = ''
                this.$parent.selected.delivery.directionTo = ''
                this.$parent.selected.delivery.days = ''
            },
            goBack() {
                this.$parent.goToStep('quantity')
            },
        },
        components: {
            DeliveryPEK
        }
    }
</script>
