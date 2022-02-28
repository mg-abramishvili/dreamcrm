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
                                Корпуса
                            </strong>
                        </h1>
                    </div>
                    <div class="col-12 col-lg-6 text-end">
                        <router-link :to="{name: 'CatalogBoxCreate'}" class="btn btn-primary">Добавить</router-link>
                    </div>
                </div>
            </div>
        </div>

        <div v-if="boxes.length" class="card">
            <table class="table">
                <thead>
                    <tr>
                        <th>Наименование</th>
                        <th>Цена</th>
                    </tr>
                </thead>
                <tbody>
                    <tr @click="goTo(box.id)" v-for="box in boxes" :key="'box_' + box.id">
                        <td class="align-middle">
                            <a>{{ box.name }}</a>
                        </td>
                        <td class="align-middle">
                            {{ box.price | currency }} ₽
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <Loader v-else></Loader>
    </div>
</template>

<script>
    import Loader from '../Loader.vue'

    export default {
        data() {
            return {
                boxes: [],
            }
        },
        created() {
            this.loadBoxes()
        },
        methods: {
            loadBoxes() {
                axios
                .get(`/api/catalog/boxes`)
                .then(response => (
                    this.boxes = response.data
                ));
            },
            goTo(id) {
                this.$router.push({name: 'CatalogBoxEdit', params: {id: id}})
            },
        },
        components: {
            Loader
        },
    }
</script>