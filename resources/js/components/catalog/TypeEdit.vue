<template>
    <div v-if="type.id && type.id > 0">
        <div class="row align-items-center mb-4">
            <div class="col-12 col-lg-6">
                <h1 class="h3 m-0">
                    <router-link :to="{name: 'CatalogTypes'}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left align-middle me-2"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                    </router-link>
                    <strong>
                        {{ type.name }}
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

                <button @click="save(type.id)" class="btn btn-primary">Сохранить</button>
                <button @click="del(type.id)" class="btn btn-outline-danger">Удалить</button>
            </div>
        </div>
    </div>
    <div v-else>
        <Loader></Loader>
    </div>
</template>

<script>
    import Loader from '../Loader.vue'
    
    export default {
        data() {
            return {
                type: {},

                name: '',

                errors: [],
            }
        },
        created() {        
            this.loadType()
        },
        methods: {
            loadType() {
                axios.get(`/api/catalog/type/${this.$route.params.id}`)
                    .then(response => (
                        this.type = response.data,
                        this.name = response.data.name
                    ))
            },
            save(id) {
                this.errors = []

                if (!this.name) {
                    this.errors.push('Укажите название');
                }

                if(this.errors && this.errors.length > 0) {
                    return
                }

                axios.put(`/api/catalog/type/${id}/update`,
                {
                    name: this.name,
                })
                .then(response => (
                    this.$router.push({name: 'CatalogTypes'})
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
                    axios.delete(`/api/catalog/type/${id}/delete`)
                    .then(response => (
                        this.$router.push({name: 'CatalogTypes'})
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