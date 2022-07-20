<template>
    <div class="offcanvas offcanvas-end show" tabindex="-1" id="offcanvasRight" role="dialog">
        <div class="offcanvas-header">
            <h4 id="offcanvasRightLabel" class="m-0">Добавление остатка</h4>
            <button @click="$parent.closeOffcanvas()" type="button" class="btn-close text-reset"></button>
        </div>
        <div class="offcanvas-body">
            <div class="row align-items-center">
                <div class="col-4 mb-4">
                    <label class="form-label">Кол-во</label>
                    <input v-model="quantity" type="number" min="0" class="form-control">
                </div>
                <div class="col-8 mb-4">
                    <label class="form-label">Дата</label>
                    <input v-model="date" type="date" class="form-control">
                </div>
                <div class="col-6 mb-4">
                    <label class="form-label">Цена RUB</label>
                    <input v-model="pre_rub" type="number" min="0" class="form-control">
                </div>
                <div class="col-6 mb-4">
                    <label class="form-label">Цена USD</label>
                    <input v-model="pre_usd" type="number" min="0" class="form-control">
                </div>
                <div class="col-6 mb-4">
                    <label class="form-label">Цена итог</label>
                    <input v-model="price" type="number" class="form-control" disabled>
                </div>
                <div class="col-6 mb-4">
                    <label class="form-label">Курс USD</label>
                    <input v-model="usd_kurs" type="number" class="form-control">
                </div>
            </div>
            <button @click="save()" class="btn btn-primary">Сохранить</button>
            <button @click="$parent.closeOffcanvas()" class="btn btn-outline-secondary">Отмена</button>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['item'],
        data() {
            return {
                quantity: 0,
                pre_rub: 0,
                pre_usd: 0,
                usd_kurs: 0,
                date: moment().format('YYYY-MM-DD'),
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
            this.loadUsd()
        },
        methods: {
            loadUsd() {
                axios
                .get('/api/usd')
                .then((response => {
                    this.usd_kurs = response.data.kurs
                }))
            },
            save() {
                if (this.quantity <= 0) {
                    return this.$swal({
                        text: 'Укажите количество',
                        icon: 'error',
                    })
                }

                axios.post(`/api/stock/item/${this.item.id}/balance`,
                {
                    quantity: this.quantity,
                    pre_rub: this.pre_rub,
                    pre_usd: this.pre_usd,
                    price: this.price,
                    date: this.date,
                    usd_kurs: this.usd_kurs
                })
                .then(response => {
                    this.$parent.closeOffcanvas()
                    this.$parent.loadStockItem()

                    if(response.data == 'detector') {
                        this.$router.push({name: 'StockNeedsDetector', params: {stock_item_id: this.item.id }})
                    }
                })
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