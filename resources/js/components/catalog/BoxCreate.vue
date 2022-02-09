<template>
    <div v-if="usd.kurs && usd.kurs > 0">
        <div class="row align-items-center mb-4">
            <div class="col-12 col-lg-6">
                <h1 class="h3 m-0">
                    <router-link :to="{name: 'Boxes'}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left align-middle me-2"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                    </router-link>
                    <strong>
                        Новый корпус
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
                        <input v-model="pre_rub" type="number" min="0" class="form-control">
                    </div>
                    <div class="col">
                        <label>Цена USD </label>
                        <input v-model="pre_usd" type="number" min="0" class="form-control">
                        <small>{{ usd.kurs }} ₽ от {{ usd.date | formatDateShort }}</small>
                    </div>
                    <div class="col">
                        <label>Цена Сборка</label>
                        <input v-model="sborka" type="number" min="0" class="form-control">
                    </div>
                    <div class="col">
                        <label>Цена Маржа</label>
                        <input v-model="marzha" type="number" min="0" class="form-control">
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

                <div class="row mb-4">
                    <div class="col">
                        <label>Длина, мм</label>
                        <input v-model="length" type="number" min="0" class="form-control">
                    </div>
                    <div class="col">
                        <label>Ширина, мм</label>
                        <input v-model="width" type="number" min="0" class="form-control">
                    </div>
                    <div class="col">
                        <label>Высота, мм</label>
                        <input v-model="height" type="number" min="0" class="form-control">
                    </div>
                    <div class="col">
                        <label>Вес, кг</label>
                        <input v-model="weight" type="number" min="0" class="form-control">
                    </div>
                </div>

                <div class="mb-4">
                    <label>Описание</label>
                    <textarea v-model="description" class="form-control" style="resize: vertical"></textarea>
                </div>
                <div class="mb-4">
                    <label>Описание для менеджеров</label>
                    <textarea v-model="manager_description" class="form-control" style="resize: vertical"></textarea>
                </div>
                <div class="mb-4">
                    <label>Коммент</label>
                    <textarea v-model="comment" class="form-control" style="resize: vertical"></textarea>
                </div>

                <button @click="saveBox()" class="btn btn-primary">Сохранить</button>
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
                name: '',
                pre_rub: 0,
                pre_usd: 0,
                sborka: 0,
                marzha: 0,
                length: 0,
                width: 0,
                height: 0,
                weight: 0,
                description: '',
                manager_description: '',
                comment: '',

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
            selectAllBoxes() {
                this.selected.boxes = this.boxes.map(box => box.id)
            },
            saveBox() {
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
                if (!this.length || this.length <= 0) {
                    this.errors.push('Укажите длину');
                }
                if (!this.width || this.width <= 0) {
                    this.errors.push('Укажите ширину');
                }
                if (!this.height || this.height <= 0) {
                    this.errors.push('Укажите высоту');
                }
                if (!this.weight || this.weight <= 0) {
                    this.errors.push('Укажите вес');
                }

                if(this.errors && this.errors.length > 0) {
                    return
                }

                axios.post('/api/boxes',
                {
                    name: this.name,
                    pre_rub: this.pre_rub,
                    pre_usd: this.pre_usd,
                    sborka: this.sborka,
                    marzha: this.marzha,
                    price: this.price,
                    types: this.selected.types,
                    length: this.length,
                    width: this.width,
                    height: this.height,
                    weight: this.weight,
                    description: this.description,
                    manager_description: this.manager_description,
                    comment: this.comment,
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