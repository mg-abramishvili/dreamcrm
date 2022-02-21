<template>
    <div class="catalog-page">
        <div class="row align-items-center mb-4">
            <div class="col-12 col-lg-6">
                <h1 class="h3 m-0">
                    <router-link :to="{name: 'Catalog'}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left align-middle me-2"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                    </router-link>
                    <strong>
                        Управление типами
                    </strong>
                </h1>
            </div>
            <div class="col-12 col-lg-6 text-end">
                <router-link :to="{name: 'CatalogTypeCreate'}" class="btn btn-primary">Добавить</router-link>
            </div>
        </div>

        <div class="card">
            <table class="table">
                <thead>
                    <tr>
                        <th>Наименование</th>
                    </tr>
                </thead>
                <tbody>
                    <tr @click="goTo(type.id)" v-for="type in types" :key="'type_' + type.id">
                        <td class="align-middle">
                            <a>{{ type.name }} {{type.boxes}}</a>
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
                types: [],
            }
        },
        created() {
            this.loadTypes()
        },
        methods: {
            loadTypes() {
                axios
                .get(`/api/catalog/types`)
                .then(response => (
                    this.types = response.data
                ));
            },
            goTo(id) {
                this.$router.push({name: 'CatalogTypeEdit', params: {id: id}})
            },
        },
    }
</script>