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
                                {{ box.name }}
                            </strong>
                        </h1>
                    </div>
                </div>
            </div>
        </div>

        <div v-if="usd.kurs && usd.kurs > 0 && box && box.id && box.id > 0" class="card">
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
                        <!-- <select v-model="selected.types" class="form-control mb-3" style="height: 225px;" multiple>
                            <option v-for="type in types" :key="'type_' + type.id" :value="type.id">{{ type.name }}</option>
                        </select> -->
                        <input v-model="typeSearchInput" type="text" class="form-control mb-1" placeholder="Поиск по типу...">
                        <div class="form-control" style="height: 220px; overflow-y: auto;">
                            <div v-for="type in typesFiltered" :key="'type_' + type.id" class="form-check">
                                <input v-model="selected.types" :id="'type_' + type.id" :value="type.id" class="form-check-input" type="checkbox">
                                <label class="form-check-label" :for="'type_' + type.id">
                                    {{ type.name }}
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-lg-6">
                        <div class="d-flex justify-content-between">
                            <label>Склад</label>
                        </div>
                        <input v-model="stockSearchInput" type="text" class="form-control mb-1" placeholder="Поиск по складу...">
                        <div class="form-control" style="height: 180px; overflow-y: auto;">
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

                <div class="row mb-3">
                    <div class="col">
                        <label>Склад</label>
                        <input v-model="stockItemsPrice" disabled type="number" class="form-control">
                    </div>
                    <div class="col">
                        <label>Сборка: кол-во дней</label>
                        <input v-model="sborkaDays" type="number" min="0" class="form-control">
                    </div>
                    <div class="col">
                        <label>Сборка: кол-во чел</label>
                        <input v-model="sborkaPersons" type="number" min="0" class="form-control">
                    </div>
                    <div class="col">
                        <label>Сборка итог</label>
                        <input v-model="sborka" disabled type="number" class="form-control">
                    </div>
                    <div class="col">
                        <label>Маржа</label>
                        <input v-model="marzha" type="number" min="0" class="form-control">
                    </div>
                    <div class="col">
                        <label>Цена (финальная)</label>
                        <input v-model="price" disabled type="number" class="form-control">
                    </div>
                </div>

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

                <button @click="save(box.id)" class="btn btn-primary">Сохранить</button>
                <button @click="del(box.id)" class="btn btn-outline-danger">Удалить</button>
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
                box: {},

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
                return this.stockItems.filter(stockItem => {
                    if(stockItem.balances && stockItem.balances.length > 0) {
                        return stockItem.name.toLowerCase().includes(this.stockSearchInput.toLowerCase())
                    }
                })
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
            this.loadUsd()
            this.loadSborkaTarif()
            this.loadBox()
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
            loadBox() {
                axios
                .get(`/api/catalog/box/${this.$route.params.id}`)
                .then((response => {
                    this.box = response.data
                    this.name = response.data.name
                    this.sborkaDays = response.data.sborka_days
                    this.sborkaPersons = response.data.sborka_persons
                    this.marzha = response.data.marzha
                    this.length = response.data.length
                    this.width = response.data.width
                    this.height = response.data.height
                    this.weight = response.data.weight
                    this.description = response.data.description
                    this.manager_description = response.data.manager_description
                    this.comment = response.data.comment
                    this.selected.types = response.data.types.map(type => type.id)
                    this.selected.stockItems = response.data.stock_items.map(item => item.id)
                    this.selected.stockItemsQty = response.data.stock_items.map(({id, pivot}) => ({id: id, quantity: pivot.quantity}))

                    if(response.data.gallery) {
                        this.filepond_gallery_edit = response.data.gallery.map(function(element){
                            {
                                return {
                                    source: element,
                                    options: {
                                        type: 'local',
                                    }
                                } 
                            }
                        })
                    }
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
            save(id) {
                this.errors = []

                if (!this.name) {
                    this.errors.push('Укажите название');
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

                if(document.getElementsByName("gallery[]")) {
                    this.gallery = []
                    document.getElementsByName("gallery[]").forEach((galleryItem) => {
                        if(galleryItem.value) {
                            this.gallery.push(galleryItem.value)
                        }
                    });
                }

                console.log(this.gallery)

                axios.put(`/api/catalog/box/${id}/update`,
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
            del(id) {
                if (confirm("Точно удалить?")) {
                    axios.delete(`/api/catalog/box/${id}/delete`)
                    .then(response => (
                        this.$router.push({name: 'CatalogBoxes'})
                    ))
                    .catch((error) => {
                        if(error.response) {
                            this.errors = []
                            for(var key in error.response.data.errors){
                                this.errors.push(key)
                            }
                        }
                    })
                }
            },
        },
        components: {
            Loader
        }
    }
</script>