<template>
    <div class="catalog-page">
        <div class="card card-bordered">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-12 col-lg-6">
                        <h1 class="h3 m-0">
                            <router-link :to="{name: 'Catalog'}" class="back-arrow">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left align-middle me-2"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                            </router-link>
                            <strong>
                                Поиск по каталогу
                            </strong>
                        </h1>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-body">
                <input v-model="input" id="input" type="text" placeholder="Поиск" class="form-control form-control-lg">
            </div>
        </div>

        <div v-if="input && input.length > 0" class="card">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">Наименование</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="box in filteredBoxes" :key="'box_' + box.id">
                        <td class="align-middle">
                            <router-link :to="{name: 'CatalogBoxEdit', params: {id: box.id}}">{{ box.name }}</router-link>
                        </td>
                    </tr>
                    <tr v-for="catalogItem in filteredCatalogItems" :key="'catalogItem_' + catalogItem.id">
                        <td class="align-middle">
                            <router-link :to="{name: 'CatalogItemEdit', params: {id: catalogItem.id}}">{{ catalogItem.name }}</router-link>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        
    </div>
</template>

<script>
    export default {
        data() {
            return {
                catalogItems: [],
                boxes: [],

                input: '',
            }
        },
        created() {
            this.loadBoxes()
            this.loadCatalogItems()
        },
        mounted() {
            document.getElementById("input").focus();
        },
        computed: {
            filteredBoxes() {
                return this.boxes.filter(box => {
                    return box.name.toLowerCase().includes(this.input.toLowerCase())
                })
            },
            filteredCatalogItems() {
                return this.catalogItems.filter(item => {
                    return item.name.toLowerCase().includes(this.input.toLowerCase())
                })
            }
        },
        methods: {
            loadBoxes() {
                axios
                .get(`/api/catalog/boxes`)
                .then(response => (
                    this.boxes = response.data
                ));
            },
            loadCatalogItems() {
                axios
                .get(`/api/catalog/items`)
                .then(response => (
                    this.catalogItems = response.data
                ));
            }
        },
    }
</script>