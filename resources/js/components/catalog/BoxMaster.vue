<template>
    <div class="catalog-page">
        <div class="card card-bordered">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-12 col-lg-8">
                        <h1 class="h3 m-0">
                            <router-link :to="{name: 'CatalogBoxes'}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left align-middle me-2"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                            </router-link>
                            <strong v-if="box.name">{{ box.name }}</strong>
                            <strong v-else>Новый корпус</strong>
                        </h1>
                    </div>
                    <div class="col-12 col-lg-4 text-end">
                        <button @click="save()" class="btn btn-primary">Сохранить</button>
                        <button v-if="$route.params.id" @click="del()" class="btn btn-outline-danger">Удалить</button>
                    </div>
                </div>
            </div>
        </div>

        <Loader v-if="views.loading"></Loader>

        <div v-if="!views.loading" class="tab">
            <ul class="nav nav-tabs" role="tablist">
                <li class="nav-item">
                    <a @click="selectTab('general')" class="nav-link" :class="{'active': selected.tab == 'general'}" role="tab">Общая информация</a>
                </li>
                <li class="nav-item">
                    <a @click="selectTab('type')" class="nav-link" :class="{'active': selected.tab == 'type'}" role="tab">Тип</a>
                </li>
                <li class="nav-item">
                    <a @click="selectTab('stock')" class="nav-link" :class="{'active': selected.tab == 'stock'}" role="tab">Склад</a>
                </li>
                <li class="nav-item">
                    <a @click="selectTab('prices')" class="nav-link" :class="{'active': selected.tab == 'prices'}" role="tab">Стоимость</a>
                </li>
                <li class="nav-item">
                    <a @click="selectTab('photos')" class="nav-link" :class="{'active': selected.tab == 'photos'}" role="tab">Фотки</a>
                </li>
                <li class="nav-item">
                    <a @click="selectTab('schemes')" class="nav-link" :class="{'active': selected.tab == 'schemes'}" role="tab">Чертежи</a>
                </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane" :class="{'active': selected.tab == 'general'}" role="tabpanel">
                    <div class="mb-3">
                        <label>Название</label>
                        <input v-model="name" type="text" class="form-control">
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
                </div>
                <div class="tab-pane" :class="{'active': selected.tab == 'type'}" role="tabpanel">
                    <div class="d-flex justify-content-between">
                        <label>Тип</label>
                    </div>
                    <input v-model="typeSearchInput" type="text" class="form-control mb-1" placeholder="Поиск по типу...">
                    <div class="form-control mb-3" style="height: 200px; overflow-y: auto;">
                        <div v-for="type in typesFiltered" :key="'type_' + type.id" class="form-check">
                            <input v-model="selected.types" :id="'type_' + type.id" :value="type.id" class="form-check-input" type="checkbox">
                            <label class="form-check-label" :for="'type_' + type.id">
                                {{ type.name }}
                            </label>
                        </div>
                    </div>
                </div>
                <div class="tab-pane" :class="{'active': selected.tab == 'stock'}" role="tabpanel">
                    <div class="text-end mb-2">
                        <button @click="openCopyStockItemsFromAnotherBox()" class="btn btn-sm btn-outline-secondary">Скопировать внутрянку из другого корпуса</button>
                    </div>

                    <CopyStockItemsFromAnotherBox v-if="views.copyStockItemsFromAnotherBox" />
                    
                    <input v-model="stockSearchInput" type="text" class="form-control mb-1" placeholder="Поиск по складу...">
                    <div class="form-control" style="height: 550px; overflow-y: auto;">
                        <div v-for="stockItem in stockItemsFiltered" :key="'stock_item_' + stockItem.id" class="form-check form-check-flex">
                            <div>
                                <input @change="toggleStockItems(stockItem.id, $event)" :checked="ifChecked(stockItem.id)" :id="'stock_item_' + stockItem.id" class="form-check-input" type="checkbox">
                                <label class="form-check-label" :for="'stock_item_' + stockItem.id">
                                    {{ stockItem.name }} - {{ latestBalancePrice(stockItem) | currency }} ₽
                                </label>
                            </div>
                            <div v-if="selected.stockItems.find(i => i.id == stockItem.id)">
                                <input v-model.number="selected.stockItems.find(i => i.id == stockItem.id).quantity" type="number" min="1" class="form-control form-control-mini-number">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane" :class="{'active': selected.tab == 'prices'}" role="tabpanel">
                    <div class="row mb-3">
                        <div class="col">
                            <label>Сборка дни</label>
                            <input v-model="sborkaDays" type="number" min="0" class="form-control">
                        </div>
                        <div class="col">
                            <label>Сборка люди</label>
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
                            <label>Склад</label>
                            <input v-model="stockItemsPrice" disabled type="number" class="form-control">
                        </div>
                        <div class="col">
                            <label>Цена финал</label>
                            <input v-model="price" disabled type="number" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="tab-pane" :class="{'active': selected.tab == 'photos'}" role="tabpanel">
                    <div class="mb-4">
                        <!-- <label>Фотки</label> -->
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
                </div>
                <div class="tab-pane" :class="{'active': selected.tab == 'schemes'}" role="tabpanel">
                    
                </div>
            </div>
        </div>

        <div v-if="views.backdrop" @click="closeOffcanvas()" class="offcanvas-backdrop fade show"></div>
    </div>
</template>

<script>
    import Loader from '../Loader.vue'
    import CopyStockItemsFromAnotherBox from './comps/CopyFromBox'

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
                types: [],
                stockItems: [],

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
                    tab: 'general',
                    types: [],
                    stockItems: [],
                },

                sborkaTarif: {
                    arenda: 0,
                    person: 0,
                },

                usd: {
                    kurs: '',
                },

                filepond_gallery: [],
                filepond_gallery_edit: [],

                stockSearchInput: '',
                typeSearchInput: '',

                views: {
                    loading: true,
                    copyStockItemsFromAnotherBox: false,
                },

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
                    if(stockItem.latest_balance) {
                        return stockItem.name.toLowerCase().includes(this.stockSearchInput.toLowerCase())
                    }
                })
                
                return filtered.filter(stockItem => this.selected.stockItems.find(i => i.id == stockItem.id)).concat(filtered.filter(stockItem => !this.selected.stockItems.find(i => i.id == stockItem.id)))
            },
            priceRub() {
                let priceRub = []

                this.selected.stockItems.forEach(item => {
                    let stockItem = this.stockItems.find(i => i.id === item.id)
                    if(stockItem.latest_balance && item.quantity) {
                        priceRub.push(stockItem.latest_balance.pre_rub * item.quantity)
                    }
                })

                return priceRub.reduce((a, b) => a + b, 0)
            },
            priceUsd() {
                let priceUsd = []

                this.selected.stockItems.forEach(item => {
                    let stockItem = this.stockItems.find(i => i.id === item.id)

                    if(stockItem.latest_balance && item.quantity) {
                        let usdKurs = 0

                        if(this.usd.kurs > stockItem.latest_balance.usd_kurs) {
                            usdKurs = this.usd.kurs
                        } else {
                            usdKurs = stockItem.latest_balance.usd_kurs
                        }

                        priceUsd.push((Math.ceil((stockItem.latest_balance.pre_usd * usdKurs) / 50) * 50) * item.quantity)
                    }
                })

                return priceUsd.reduce((a, b) => a + b, 0)
            },
            stockItemsPrice() {
                return this.priceRub + this.priceUsd
            },
            sborka() {
                return this.sborkaDays * (this.sborkaPersons * parseInt(this.sborkaTarif.person) + parseInt(this.sborkaTarif.arenda))
            },
            price() {
                if(!this.marzha) {
                    this.marzha = 0
                }

                return this.stockItemsPrice + this.sborka + this.marzha
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
                axios.get(`/api/stock/items-with-latest-balances-only`)
                .then(response => {
                    this.stockItems = response.data
                })
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
                .then(response => {
                    this.usd.kurs = response.data.kurs
                })
            },
            loadBox() {
                if(!this.$route.params.id) {
                    return this.views.loading = false
                }

                axios.get(`/api/catalog/box/${this.$route.params.id}`)
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
                    
                    this.selected.stockItems = response.data.stock_items.map(({id, pivot}) => ({id: id, quantity: pivot.quantity}))

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

                    this.views.loading = false
                }))
            },
            latestBalancePrice(stockItem) {
                if(stockItem.latest_balance) {
                    let usdKurs = 0

                    if(this.usd.kurs > stockItem.latest_balance.usd_kurs) {
                        usdKurs = this.usd.kurs
                    } else {
                        usdKurs = stockItem.latest_balance.usd_kurs
                    }

                    let rub = parseInt(stockItem.latest_balance.pre_rub)
                    let usd = parseInt(stockItem.latest_balance.pre_usd) * usdKurs
                    
                    return Math.ceil((rub + usd) / 50) * 50
                }
            },
            selectTab(tab) {
                this.selected.tab = tab
            },
            toggleStockItems(id) {
                if(this.selected.stockItems.find(i => i.id === id)) {
                    return this.selected.stockItems = this.selected.stockItems.filter(item => {
                        return item.id !== id;
                    })
                }

                if(!this.selected.stockItems.find(i => i.id === id)) {
                    return this.selected.stockItems.push({id: id, quantity: 1})
                }
            },
            ifChecked(id) {
                if(this.selected.stockItems.find(item => item.id === id)) {
                    return true
                }
            },
            openCopyStockItemsFromAnotherBox() {
                this.views.backdrop = true
                this.views.copyStockItemsFromAnotherBox = true
            },
            copyStockItemsFromAnotherBox(box) {
                this.sborkaDays = box.sborka_days
                this.sborkaPersons = box.sborka_persons
                this.marzha = box.marzha
                this.selected.types = box.types.map(type => type.id)
                this.selected.stockItems = box.stock_items.map(({id, pivot}) => ({id: id, quantity: pivot.quantity}))
            },
            closeOffcanvas() {
                this.views.backdrop = false
                this.views.copyStockItemsFromAnotherBox = false
            },
            save() {
                if(!this.name) {
                    return this.$swal({
                        text: 'Укажите название',
                        icon: 'error',
                    })
                }
                if(!this.selected.types.length) {
                    return this.$swal({
                        text: 'Укажите тип',
                        icon: 'error',
                    })
                }
                if(!this.length || this.length <= 0) {
                    return this.$swal({
                        text: 'Укажите глубину',
                        icon: 'error',
                    })
                }
                if(!this.width || this.width <= 0) {
                    return this.$swal({
                        text: 'Укажите ширину',
                        icon: 'error',
                    })
                }
                if (!this.height || this.height <= 0) {
                    return this.$swal({
                        text: 'Укажите высоту',
                        icon: 'error',
                    })
                }
                if (!this.weight || this.weight <= 0) {
                    return this.$swal({
                        text: 'Укажите вес',
                        icon: 'error',
                    })
                }

                if(document.getElementsByName("gallery[]")) {
                    this.gallery = []
                    document.getElementsByName("gallery[]").forEach((galleryItem) => {
                        if(galleryItem.value) {
                            this.gallery.push(galleryItem.value)
                        }
                    });
                }

                let data = {
                    name: this.name,
                    sborka_days: this.sborkaDays,
                    sborka_persons: this.sborkaPersons,
                    sborka: this.sborka,
                    marzha: this.marzha,
                    price: this.price,
                    pre_rub: this.priceRub,
                    pre_usd: this.priceUsd,
                    types: this.selected.types,
                    stock_items: this.selected.stockItems,
                    length: this.length,
                    width: this.width,
                    height: this.height,
                    weight: this.weight,
                    description: this.description,
                    manager_description: this.manager_description,
                    comment: this.comment,
                    gallery: this.gallery,
                }

                if(this.$route.params.id) {
                    axios.put(`/api/catalog/box/${this.$route.params.id}/update`, data)
                    .then(response => {
                        this.$router.push({name: 'CatalogBoxes'})
                    })
                    .catch((error) => {
                        if(error.response) {
                            for(var key in error.response.data.errors){
                                console.log(key)
                            }
                        }
                    })
                } else {
                    axios.post(`/api/catalog/boxes`, data)
                    .then(response => {
                        this.$router.push({name: 'CatalogBoxes'})
                    })
                    .catch((error) => {
                        if(error.response) {
                            for(var key in error.response.data.errors){
                                console.log(key)
                            }
                        }
                    })
                }
            },
            del() {
                if (confirm("Точно удалить?")) {
                    axios.delete(`/api/catalog/box/${this.$route.params.id}/delete`)
                    .then(response => (
                        this.$router.push({name: 'CatalogBoxes'})
                    ))
                    .catch(error => {
                        return this.$swal({
                            text: error.response.data,
                            icon: 'error',
                        })
                    })
                }
            },
        },
        components: {
            Loader,
            CopyStockItemsFromAnotherBox
        }
    }
</script>