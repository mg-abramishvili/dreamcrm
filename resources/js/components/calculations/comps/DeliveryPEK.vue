<template>
    <div>
        <select class="form-select" v-model="selected.name" @change="onCityChange()">
            <option v-for="(city, name) in cities" :value="name">{{ name }}</option>
        </select>

        <div v-if="loading" class="spinner-border text-primary mt-4">
            <span class="sr-only">Загрузка...</span>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['box', 'quantity', 'delivery'],
        data() {
            return {
                cities: [],

                selected: {
                    name: '',
                    code: '',
                    price: 0,
                },

                loading: false,
                
                loading: false,
            }
        },
        created() {
            this.loadTowns()
        },
        mounted() {
            if(this.delivery.to) {
                this.selected.name = this.delivery.to
            }
        },
        methods: {
            loadTowns() {
                axios
                //.get('http://www.pecom.ru/ru/calc/towns.php')
                .get('/towns.php')
                .then((response => {
                    this.cities = response.data
                }));
            },
            onCityChange() {
                this.$parent.selected.delivery.to = this.selected.name
                
                var city = this.cities[this.selected.name]
                this.selected.code = Object.entries(city).find(([key, value]) => value === this.selected.name)[0]
                this.$parent.selected.delivery.code = Object.entries(city).find(([key, value]) => value === this.selected.name)[0]
                this.calcDelivery()
            },
            calcDelivery() {
                var width = parseInt(this.box.width) * 0.001
                var height = parseInt(this.box.height) * 0.001
                var length = parseInt(this.box.length) * 0.001
                var weight = parseInt(this.box.weight)

                this.loading = true

                var qty_array = Array.from(Array(parseInt(this.quantity)).keys())

                var ax_params = { places: [], take: [], deliver: [] }

                qty_array.forEach((qt) => {
                    ax_params[`places[${qt}]`] = [ `${width}`, `${length}`, `${height}`, `${(width * height * length).toFixed(2)}`, `${weight}`, 0, 1 ]
                })
                
                ax_params['take[town]'] = '-463'
                ax_params['deliver[town]'] = `${this.selected.code}`

                axios
                    .get('https://calc.pecom.ru/bitrix/components/pecom/calc/ajax.php', {
                        params: ax_params
                    })
                    .then(response => (
                        this.selected.price = response.data.auto[2],

                        this.$parent.selected.delivery.price = response.data.auto[2].toFixed(0),
                        this.$parent.selected.delivery.directionFrom = response.data.region_from.BranchName,
                        this.$parent.selected.delivery.directionTo = response.data.region_to.BranchName,
                        this.$parent.selected.delivery.days = response.data.periods_days,
                        
                        this.loading = false
                    ))
            },
        },
    }
</script>