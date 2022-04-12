<template>
    <div class="clients-page">
        <div class="card card-bordered">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-12 col-lg-6">
                        <h1 class="h3 m-0">Клиенты</h1>
                    </div>
                    <div class="col-12 col-lg-6 text-end">
                        <router-link :to="{name: 'ClientCreate'}" class="btn btn-primary">Добавить</router-link>
                    </div>
                </div>
            </div>
        </div>

        <div v-if="clients && clients.length" class="card card-bordered">
            <div class="card-body p-0">
                <table class="table table-hover dataTable">
                    <thead>
                        <tr>
                            <th>Название</th>
                            <th>ИНН</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="client in clients" :key="client.id" @click="goTo(client.id)">
                            <td class="align-middle">
                                {{ client.name }}
                            </td>
                            <td class="align-middle">
                                <template v-if="client.inn">
                                    {{ client.inn }}
                                </template>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <Loader v-else></Loader>
    </div>
</template>

<script>
    import Loader from '../Loader.vue'

    export default {
        data() {
            return {
                clients: [],
            }
        },
        created() {
            this.loadClients()
        },
        methods: {
            loadClients() {
                axios
                .get('/api/clients')
                .then(response => (
                    this.clients = response.data
                ))
            },
            goTo(id) {
                this.$router.push({ name: 'Client', params: { id: id } });
            }
        },
        components: {
            Loader
        },
    }
</script>
<style scoped>
    .table tr:hover {
        cursor: pointer;
    }
</style>