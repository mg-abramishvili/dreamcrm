<template>
    <div>
        <div class="modal fade show" id="DeliveryModal" tabindex="-1">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">ПЭК доставка</h5>
                        <button @click="$parent.closeDeliveryModal()" type="button" class="btn-close"></button>
                    </div>
                    <div class="modal-body m-3">
                        
                        <div class="delivery mt-4" id="delivery">
                            <div class="alert alert-primary alert-outline">
                                <div></div>
                                <div class="alert-message">
                                    <h6 class="alert-heading">Город доставки (ПЭК):</h6>
                                    
                                    <div class="row">
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
            this.loadTowns()
        },
        mounted () {
            document.body.classList.add('modal-open')
        },
        methods: {
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
                var width = parseInt(this.box.width)
                var height = parseInt(this.box.height)
                var length = parseInt(this.box.length)
                var weight = parseInt(this.box.weight)
                this.pek_loading = true
                axios
                    .get('http://calc.pecom.ru/bitrix/components/pecom/calc/ajax.php', {
                        params: {
                            'places[0]': [ `${width}`, `${length}`, `${height}`, `${(width * height * length).toFixed(2)}`, `${weight}`, 0, 1 ],
                            'take[town]': '-463',
                            'deliver[town]': `${this.pek_city_sub_selected}`
                        }
                    })
                    .then(response => (
                        // this.pek_response = response.data,
                        // this.pek_price = parseInt(response.data.auto[2]) + parseInt(response.data.ADD[1]),
                        // this.pek_loading = false,
                        console.log(response.data)
                    ));
            },
        },
    }
</script>

<style scoped>
    #DeliveryModal {
        display: block !important;
    }
</style>