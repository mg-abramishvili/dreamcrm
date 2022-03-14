<template>
    <div class="catalog-page">
        <div class="card card-bordered">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-12 col-lg-6">
                        <h1 class="h3 m-0">
                            <router-link :to="{name: 'CatalogBoxes'}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left align-middle me-2"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                            </router-link>
                            <strong>
                                Новый корпус
                            </strong>
                        </h1>
                    </div>
                </div>
            </div>
        </div>

        <div v-if="usd.kurs && usd.kurs > 0 && stockItems && stockItems.length > 0" class="card">
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

                <div class="row mb-4">
                    <div class="col-12 col-lg-6">
                        <div class="d-flex justify-content-between">
                            <label>Тип</label>
                        </div>
                        <input v-model="typeSearchInput" type="text" class="form-control mb-1" placeholder="Поиск по типу...">
                        <div class="form-control mb-3" style="height: 150px; overflow-y: auto;">
                            <div v-for="type in typesFiltered" :key="'type_' + type.id" class="form-check">
                                <input v-model="selected.types" :id="'type_' + type.id" :value="type.id" class="form-check-input" type="checkbox">
                                <label class="form-check-label" :for="'type_' + type.id">
                                    {{ type.name }}
                                </label>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-4">
                                <label>Сборка дни</label>
                                <input v-model="sborkaDays" type="number" min="0" class="form-control">
                            </div>
                            <div class="col-4">
                                <label>Сборка люди</label>
                                <input v-model="sborkaPersons" type="number" min="0" class="form-control">
                            </div>
                            <div class="col-4">
                                <label>Сборка итог</label>
                                <input v-model="sborka" disabled type="number" class="form-control">
                            </div>
                            <div class="col-4">
                                <label>Маржа</label>
                                <input v-model="marzha" type="number" min="0" class="form-control">
                            </div>
                            <div class="col-4">
                                <label>Склад</label>
                                <input v-model="stockItemsPrice" disabled type="number" class="form-control">
                            </div>
                            <div class="col-4">
                                <label>Цена финал</label>
                                <input v-model="price" disabled type="number" class="form-control">
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-3">
                                <label>Глубина, мм</label>
                                <input v-model="length" type="number" min="0" class="form-control">
                            </div>
                            <div class="col-3">
                                <label>Ширина, мм</label>
                                <input v-model="width" type="number" min="0" class="form-control">
                            </div>
                            <div class="col-3">
                                <label>Высота, мм</label>
                                <input v-model="height" type="number" min="0" class="form-control">
                            </div>
                            <div class="col-3">
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

                        <div class="mb-4">
                            <label>Фотки</label>
                            <file-pond
                                name="gallery[]"
                                ref="gallery"
                                label-idle="Выбрать картинки..."
                                v-bind:allow-multiple="true"
                                v-bind:allow-reorder="true"
                                accepted-file-types="image/jpeg, image/png"
                                :server="server"
                                v-bind:files="filepond_gallery_edit"
                            />
                        </div>

                        <button @click="save()" class="btn btn-primary">Сохранить</button>
                    </div>
                    <div class="col-12 col-lg-6">
                        <div class="d-flex justify-content-between">
                            <label>Склад</label>
                        </div>
                        <input v-model="stockSearchInput" type="text" class="form-control mb-1" placeholder="Поиск по складу...">
                        <div class="form-control" style="height: 740px; overflow-y: auto;">
                            <div v-for="stockItem in stockItemsFiltered" :key="'stock_item_' + stockItem.id" class="form-check form-check-flex">
                                <div>
                                    <input v-model="selected.stockItems" @change="selectedStockItems(stockItem.id, $event)" :id="'stock_item_' + stockItem.id" :value="stockItem.id" class="form-check-input" type="checkbox">
                                    <label class="form-check-label" :for="'stock_item_' + stockItem.id">
                                        {{ stockItem.name }} - {{ LatestBalancePrice(stockItem) | currency }} ₽
                                    </label>
                                </div>
                                <div>
                                    <input v-if="selected.stockItems.includes(stockItem.id)" v-model="selected.stockItemsQty.find(q => q.id == stockItem.id).quantity" type="number" min="0" class="form-control form-control-mini-number">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <Loader v-else></Loader>
    </div>
</template>

<script>
    import Loader from '../Loader.vue'

    import vueFilePond from "vue-filepond";
    import "filepond/dist/filepond.min.css";
    import "filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.css";
    import FilePondPluginFileValidateType from "filepond-plugin-file-validate-type";
    import FilePondPluginImagePreview from "filepond-plugin-image-preview";
    const FilePond = vueFilePond(
        FilePondPluginFileValidateType,
        FilePondPluginImagePreview
    );
    
    export default {
        data() {
            return {
                name: '',
                sborkaDays: 0,
                sborkaPersons: 0,
                marzha: 0,
                length: 0,
                width: 0,
                height: 0,
                weight: 0,
                description: '',
                manager_description: '',
                comment: '',
                gallery: [],

                selected: {
                    types: [],
                    stockItems: [],
                    stockItemsQty: [],
                },
                
                types: [],
                stockItems: [],

                sborkaTarif: {
                    arenda: 0,
                    person: 0,
                },

                usd: {
                    kurs: '',
                    date: '',
                },

                filepond_gallery: [],
                filepond_gallery_edit: [],

                stockSearchInput: '',
                typeSearchInput: '',

                errors: [],

                server: {
                    remove(filename, load) {
                        load('1');
                    },
                    process: (fieldName, file, metadata, load, error, progress, abort, transfer, options) => {
                        const formData = new FormData();
                        formData.append(fieldName, file, file.name);
                        const request = new XMLHttpRequest();
                        request.open('POST', '/api/file/upload');
                        request.upload.onprogress = (e) => {
                            progress(e.lengthComputable, e.loaded, e.total);
                        };
                        request.onload = function() {
                            if (request.status >= 200 && request.status < 300) {
                                load(request.responseText);
                            }
                            else {
                                error('oh no');
                            }
                        };
                        request.send(formData);
                        return {
                            abort: () => {
                                request.abort();
                                abort();
                            }
                        };
                    },
                    revert: (filename, load) => {
                        load(filename)
                    },
                    load: (source, load, error, progress, abort, headers) => {
                        var myRequest = new Request(source);
                        fetch(myRequest).then(function(response) {
                            response.blob().then(function(myBlob) {
                                load(myBlob)
                            });
                        });
                    },
                },
            }
        },
        computed: {
            typesFiltered() {
                return this.types.filter(type => {
                    return type.name.toLowerCase().includes(this.typeSearchInput.toLowerCase())
                })
            },
            stockItemsFiltered() {
                let filtered = this.stockItems.filter(stockItem => {
                    if(stockItem.balances && stockItem.balances.length > 0) {
                        return stockItem.name.toLowerCase().includes(this.stockSearchInput.toLowerCase())
                    }
                })

                return filtered.filter(stockItem => this.selected.stockItems.includes(stockItem.id)).concat(filtered.filter(stockItem => !this.selected.stockItems.includes(stockItem.id)))
            },
            priceRub() {
                let selectedStockItems = this.stockItems.filter(stockItem => this.selected.stockItems.includes(stockItem.id))

                return selectedStockItems.map(stockItem => stockItem.balances[stockItem.balances.length - 1].pre_rub * this.selected.stockItemsQty.find(q => q.id == stockItem.id).quantity).reduce((a, b) => parseInt(a) + parseInt(b), 0)
            },
            priceUsd() {
                let selectedStockItems = this.stockItems.filter(stockItem => this.selected.stockItems.includes(stockItem.id))

                return selectedStockItems.map(stockItem => stockItem.balances[stockItem.balances.length - 1].pre_usd * this.selected.stockItemsQty.find(q => q.id == stockItem.id).quantity).reduce((a, b) => parseInt(a) + parseInt(b), 0)
            },
            stockItemsPrice() {
                return Math.ceil((this.priceRub + (this.priceUsd * this.usd.kurs)) / 50) * 50
            },
            sborka() {

                return this.sborkaDays * (this.sborkaPersons * parseInt(this.sborkaTarif.person) + parseInt(this.sborkaTarif.arenda))
            },
            price() {
                if(!this.marzha) {
                    this.marzha = 0
                }

                const stockItemsPrice = parseInt(this.stockItemsPrice)
                const sborka = parseInt(this.sborka)
                const marzha = parseInt(this.marzha)

                return Math.ceil((stockItemsPrice + sborka + marzha) / 50) * 50
            }
        },
        created() {        
            this.loadTypes()
            this.loadStockItems()
            this.loadSborkaTarif()
            this.loadUsd()
        },
        methods: {
            loadTypes() {
                axios.get(`/api/catalog/types`)
                    .then(response => (
                        this.types = response.data
                    ))
            },
            loadStockItems() {
                axios.get(`/api/stock/items`)
                    .then(response => (
                        this.stockItems = response.data
                    ))
            },
            loadSborkaTarif() {
                axios.get(`/api/catalog/sborka`)
                    .then(response => (
                        this.sborkaTarif.arenda = response.data.arenda,
                        this.sborkaTarif.person = response.data.person
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
            LatestBalancePrice(stockItem) {
                if(stockItem.balances && stockItem.balances.length > 0) {
                    let rub = parseInt(stockItem.balances[stockItem.balances.length - 1].pre_rub)
                    let usd = parseInt(stockItem.balances[stockItem.balances.length - 1].pre_usd) * this.usd.kurs
                    
                    return Math.ceil((rub + usd) / 50) * 50
                }
            },
            selectedStockItems(id) {
                if(event.target.checked) {
                    this.selected.stockItemsQty.push({id: id, quantity: 1})
                } else {
                    this.selected.stockItemsQty = this.selected.stockItemsQty.filter(qty => qty.id !== id)
                }
            },
            save() {
                this.errors = []

                if (!this.name) {
                    this.errors.push('Укажите название');
                }
                if (!this.selected.types.length) {
                    this.errors.push('Укажите тип');
                }
                if (!this.length || this.length <= 0) {
                    this.errors.push('Укажите глубину');
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

                if(document.getElementsByName("gallery[]")) {
                    this.gallery = []
                    document.getElementsByName("gallery[]").forEach((galleryItem) => {
                        if(galleryItem.value) {
                            this.gallery.push(galleryItem.value)
                        }
                    });
                }

                axios.post('/api/catalog/boxes',
                {
                    name: this.name,
                    sborka_days: this.sborkaDays,
                    sborka_persons: this.sborkaPersons,
                    sborka: this.sborka,
                    marzha: this.marzha,
                    price: this.price,
                    pre_rub: this.priceRub,
                    pre_usd: this.priceUsd,
                    types: this.selected.types,
                    stock_items: this.selected.stockItemsQty,
                    length: this.length,
                    width: this.width,
                    height: this.height,
                    weight: this.weight,
                    description: this.description,
                    manager_description: this.manager_description,
                    comment: this.comment,
                    gallery: this.gallery,
                })
                .then(response => (
                    this.$router.push({name: 'CatalogBoxes'}) 
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
        components: {
            Loader
        }
    }
</script>