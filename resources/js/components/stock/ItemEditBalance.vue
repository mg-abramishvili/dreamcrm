<template>
    <div class="card w-50 m-0 m-auto mb-1">
        <div class="card-body">
            <div v-if="errors && errors.length > 0" class="alert alert-danger">
                <div class="alert-message">
                    <strong v-for="(error, index) in errors" :key="'error_' + index" class="d-block">
                        {{ error }}
                    </strong>
                </div>
            </div>
            
            <div class="row align-items-center">
                <div class="col-6 mb-4">
                    <label class="form-label">Кол-во</label>
                    <input v-model="quantity" type="number" min="0" class="form-control">
                </div>
                <div class="col-6 mb-4">
                    <label class="form-label">Дата</label>
                    <input v-model="date" type="date" class="form-control">
                </div>
                <div class="col-3 mb-4">
                    <label class="form-label">Цена RUB</label>
                    <input v-model="pre_rub" type="number" min="0" class="form-control">
                </div>
                <div class="col-3 mb-4">
                    <label class="form-label">Цена USD</label>
                    <input v-model="pre_usd" type="number" min="0" class="form-control">
                </div>
                <div class="col-3 mb-4">
                    <label class="form-label">Цена итог</label>
                    <input v-model="price" type="number" class="form-control" disabled>
                </div>
                <div class="col-3 mb-4">
                    <label class="form-label">Курс USD</label>
                    <input v-model="usd_kurs" type="number" class="form-control">
                </div>
            </div>
            <button @click="save()" class="btn btn-primary">Сохранить</button>
            <button @click="$parent.closeEditBalance()" class="btn btn-outline-secondary">Отмена</button>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['item', 'balance'],
        data() {
            return {
                quantity: 0,
                pre_rub: 0,
                pre_usd: 0,
                usd_kurs: 0,
                date: '',

                errors: [],
            }
        },
        computed: {
            price() {
                if(!this.pre_rub) {
                    this.pre_rub = 0
                }
                if(!this.pre_usd) {
                    this.pre_usd = 0
                }

                return Math.ceil((parseFloat(this.pre_rub) + (parseFloat(this.usd_kurs) * parseFloat(this.pre_usd))) / 50) * 50
            }
        },
        created() {
            this.quantity = this.balance.quantity
            this.pre_rub = this.balance.pre_rub
            this.pre_usd = this.balance.pre_usd
            this.date = this.balance.date
            this.usd_kurs = this.balance.usd_kurs
        },
        methods: {
            save() {
                this.errors = []

                if (this.quantity < 0) {
                    this.errors.push('Укажите количество');
                }
                if (!this.date) {
                    this.errors.push('Укажите дату');
                }

                if(this.errors && this.errors.length > 0) {
                    return
                }

                axios.put(`/api/stock/balance/${this.balance.id}/update`,
                {
                    quantity: this.quantity,
                    pre_rub: this.pre_rub,
                    pre_usd: this.pre_usd,
                    price: this.price,
                    date: this.date,
                    usd_kurs: this.usd_kurs
                })
                .then(response => (
                    this.$parent.closeEditBalance(),
                    this.$parent.loadItem()
                ))
                .catch((error) => {
                    if(error.response) {
                        for(var key in error.response.data.errors){
                            console.log(key)
                        }
                    }
                })
            },
        },
    }
</script>