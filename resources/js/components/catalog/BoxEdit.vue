<template>
    <div v-if="usd.kurs && usd.kurs > 0 && box && box.id && box.id > 0">
        <div class="row align-items-center mb-4">
            <div class="col-12 col-lg-6">
                <h1 class="h3 m-0">
                    <router-link :to="{name: 'Boxes'}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left align-middle me-2"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                    </router-link>
                    <strong>
                        {{ box.name }}
                    </strong>
                </h1>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div v-if="errors && errors.length > 0" class="alert alert-danger">
                    <div class="alert-message">
                        <strong v-for="(error, index) in errors" :key="'error_' + index" class="d-block">
                            {{ error }}
                        </strong>
                    </div>
                </div>

                <div class="mb-3">
                    <label>Название</label>
                    <input v-model="name" type="text" class="form-control">
                </div>

                <div class="row">
                    <div class="col">
                        <label>Цена RUB</label>
                        <input v-model="pre_rub" type="number" class="form-control">
                    </div>
                    <div class="col">
                        <label>Цена USD </label>
                        <input v-model="pre_usd" type="number" class="form-control">
                        <small>{{ usd.kurs }} ₽ от {{ usd.date | formatDateShort }}</small>
                    </div>
                    <div class="col">
                        <label>Цена Сборка</label>
                        <input v-model="sborka" type="number" class="form-control">
                    </div>
                    <div class="col">
                        <label>Цена Маржа</label>
                        <input v-model="marzha" type="number" class="form-control">
                    </div>
                    <div class="col">
                        <label>Цена (финальная)</label>
                        <input v-model="price" disabled type="number" class="form-control">
                    </div>
                </div>

                <div class="d-flex justify-content-between">
                    <label>Тип</label>
                </div>
                <select v-model="selected.types" class="form-control mb-3" style="height: 200px;" multiple>
                    <option v-for="type in types" :key="'type_' + type.id" :value="type.id">{{ type.name }}</option>
                </select>
                <button @click="updateBox(box.id)" class="btn btn-primary">Сохранить</button>
            </div>
        </div>
    </div>
    <div v-else>
        <div class="spinner-border text-primary me-2" role="status">
            <span class="sr-only">Загрузка...</span>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                box: {},

                name: '',
                pre_rub: 0,
                pre_usd: 0,
                sborka: 0,
                marzha: 0,

                selected: {
                    types: [],
                },
                
                types: [],

                usd: {
                    kurs: '',
                    date: '',
                },

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
                if(!this.sborka) {
                    this.sborka = 0
                }
                if(!this.marzha) {
                    this.marzha = 0
                }

                return Math.ceil((parseFloat(this.pre_rub) + (parseFloat(this.usd.kurs) * parseFloat(this.pre_usd)) + parseFloat(this.sborka) + parseFloat(this.marzha)) / 50) * 50
            }
        },
        created() {        
            this.loadTypes()
            this.loadUsd()
            this.loadBox()
        },
        methods: {
            loadTypes() {
                axios.get(`/api/types`)
                    .then(response => (
                        this.types = response.data
                    ))
            },
            loadUsd() {
                axios
                .get('/api/usd')
                .then((response => {
                    this.usd.kurs = response.data.kurs,
                    this.usd.date = response.data.updated_at
                }))
            },
            loadBox() {
                axios
                .get(`/api/box/${this.$route.params.id}`)
                .then((response => {
                    this.box = response.data
                    this.name = response.data.name
                    this.pre_rub = response.data.pre_rub
                    this.pre_usd = response.data.pre_usd
                    this.sborka = response.data.sborka
                    this.marzha = response.data.marzha

                    this.selected.types = response.data.types.map(type => type.id)
                }))
            },
            selectAllBoxes() {
                this.selected.boxes = this.boxes.map(box => box.id)
            },
            updateBox(id) {
                this.errors = []

                if (!this.name) {
                    this.errors.push('Укажите название');
                }
                if (!this.price) {
                    this.errors.push('Укажите цену');
                }
                if (!this.selected.types.length) {
                    this.errors.push('Укажите тип');
                }

                if(this.errors && this.errors.length > 0) {
                    return
                }

                axios.post('/api/boxes',
                {
                    name: this.name,
                    pre_rub: this.pre_rub,
                    pre_usd: this.pre_usd,
                    price: this.price,
                    types: this.selected.types,
                })
                .then(response => (
                    this.$router.push({name: 'Boxes'}) 
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