<template>
    <div class="catalog-page">
        <div class="row align-items-center mb-4">
            <div class="col-12 col-lg-6">
                <h1 class="h3 m-0">
                    <router-link :to="{name: 'Catalog'}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left align-middle me-2"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                    </router-link>
                    <strong>
                        Поиск по каталогу
                    </strong>
                </h1>
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
                    <tr v-for="element in filteredElements" :key="'element_' + element.id">
                        <td class="align-middle">
                            <a>{{ element.name }}</a>
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
                elements: [],

                input: '',
            }
        },
        created() {
            this.loadElements()
        },
        mounted() {
            document.getElementById("input").focus();
        },
        computed: {
            filteredElements() {
                return this.elements.filter(element => {
                    return element.name.toLowerCase().includes(this.input.toLowerCase())
                })
            }
        },
        methods: {
            loadElements() {
                axios
                .get(`/api/elements`)
                .then(response => (
                    this.elements = response.data
                ));
            }
        },
    }
</script>