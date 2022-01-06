<template>
    <div>
        <div class="modal fade show" id="DeliveryModal" tabindex="-1">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Доставка</h5>
                        <!-- <button @click="$parent.closeDeliveryModal()" type="button" class="btn-close"></button> -->
                    </div>
                    <div class="modal-body m-3">
                        
                        <div class="mb-3">
                            <select v-model="inputDelivery" class="form-select">
                                <option v-for="delivery in deliveries" :key="'delivery_' + delivery.id" :value="delivery">{{ delivery.name }}</option>
                            </select>
                        </div>

                        <div v-if="inputDelivery.id == 3" class="delivery mt-4" id="delivery">
                            <div class="alert alert-primary alert-outline">
                                <div></div>
                                <div class="alert-message">
                                    <h6 class="alert-heading">Город доставки (ПЭК):</h6>
                                    
                                    <div class="row mb-3">
                                        <div class="col-12 col-md-6">
                                            <select class="form-select" v-model="pek_city_selected" @change="onCityChange()">
                                                <option v-for="pek_city in pek_cities" :value="pek_city">{{ pek_city }}</option>
                                            </select>
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <select class="form-select" v-model="pek_city_sub_selected" @change="calcDelivery()">
                                                <option v-for="pek_city_sub in pek_cities_sub" :value="pek_city_sub.id">{{ pek_city_sub.name }}</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div v-if="pek_loading" class="spinner-border text-primary mt-4">
                                        <span class="sr-only">Загрузка...</span>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <p v-if="inputDelivery && inputDelivery.id > 0" class="text-center">
                            <strong class="text-primary">
                                {{ priceDelivery.toString().replace(/\B(?=(\d{3})+(?!\d))/g, " ") }} ₽
                            </strong>
                        </p>
                    </div>
                    <div class="modal-footer">
                        <button @click="saveDelivery()" class="btn btn-primary">Окей</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show"></div>
    </div>
</template>

<script>
    export default {
        props: ['box'],
        data() {
            return {
                deliveries: '',

                inputDelivery: '',

                pek_cities_data: {},
                pek_cities: {},
                pek_city_selected: '',
                pek_cities_sub: {},
                pek_city_sub_selected: '',
                pek_response: '',
                pek_price: 0,
                pek_loading: false,
            }
        },
        created() {
            this.loadDeliveries()
            this.loadTowns()
        },
        mounted () {
            document.body.classList.add('modal-open')
        },
        computed: {
            priceDelivery() {
                if(this.inputDelivery.price && this.inputDelivery.price > 0)
                {
                    return this.inputDelivery.price
                } else {
                    return this.pek_price
                }
            }
        },
        methods: {
            loadDeliveries() {
                axios
                .get('/api/deliveries')
                .then((response => {
                    this.deliveries = response.data
                }));
            },
            loadTowns() {
                axios
                //.get('http://www.pecom.ru/ru/calc/towns.php')
                .get('/towns.php')
                .then((response => {
                    this.pek_cities_data = response.data
                    
                    var data = this.pek_cities_data;
                    var pek_cities = [];
                    for(var i in data) {
                        pek_cities.push(i);
                    }
                    this.pek_cities = pek_cities.sort()
                }));
            },
            onCityChange() {
                this.pek_response = ''
                var data = this.pek_cities_data;
                var pek_cities_sub = [];
                for(var i in data) {
                    if(i === this.pek_city_selected) {
                        for (const [key, value] of Object.entries(data[i])) {
                            pek_cities_sub.push({id: `${key}`, name: `${value}`});
                        }
                    }
                }
                this.pek_cities_sub = pek_cities_sub.sort((a, b) => (a.name > b.name) ? 1 : -1)
            },
            calcDelivery() {
                var width = parseInt(this.box.width) * 0.001
                var height = parseInt(this.box.height) * 0.001
                var length = parseInt(this.box.length) * 0.001
                var weight = parseInt(this.box.weight)
                this.pek_loading = true

                var qty_array = Array.from(Array(parseInt(this.$parent.quantity)).keys())

                var ax_params = { places: [], take: [], deliver: [] }

                qty_array.forEach((qt) => {
                    ax_params[`places[${qt}]`] = [ `${width}`, `${length}`, `${height}`, `${(width * height * length).toFixed(2)}`, `${weight}`, 0, 1 ]
                })
                
                ax_params['take[town]'] = '-463'
                ax_params['deliver[town]'] = `${this.pek_city_sub_selected}`

                axios
                    .get('http://calc.pecom.ru/bitrix/components/pecom/calc/ajax.php', {
                        params: ax_params
                    })
                    .then(response => (
                        console.log(response.data),
                        this.pek_response = response.data,
                        this.pek_price = response.data.auto[2],
                        this.pek_loading = false
                    ));
            },
            saveDelivery() {
                if(this.inputDelivery && this.inputDelivery.id > 0) {
                    if(this.inputDelivery.id == 3) {
                            if(!this.priceDelivery > 0) {
                            alert('Ошибка')
                            return
                        } else {
                            this.$parent.delivery.direction = this.pek_response.region_from.BranchName + ' - ' + this.pek_response.region_to.BranchName
                            this.$parent.delivery.days = this.pek_response.periods_days
                        }
                    }
                    
                    this.$parent.delivery.id = this.inputDelivery.id
                    this.$parent.delivery.name = this.inputDelivery.name
                    this.$parent.delivery.price = parseInt(this.priceDelivery).toFixed(0)

                    this.$parent.closeDeliveryModal()

                } else {
                    alert('Ошибка')
                }
            },
        },
    }
</script>

<style scoped>
    #DeliveryModal {
        display: block !important;
    }
</style>