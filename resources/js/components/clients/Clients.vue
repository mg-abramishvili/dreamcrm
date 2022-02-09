<template>
    <div>
        <div class="row align-items-center mb-4">
            <div class="col-12 col-lg-6">
                <h1 class="h3 m-0">Клиенты</h1>
            </div>
            <div class="col-12 col-lg-6 text-end">
                <router-link :to="{name: 'ClientCreate'}" class="btn btn-primary">Добавить</router-link>
            </div>
        </div>

        <div class="card">
            <div class="card-body pt-1">
                <table class="table table-striped table-hover dataTable no-footer dtr-inline">
                    <thead>
                        <tr>
                            <th>Имя</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="client in clients" :key="client.id" @click="goTo(client.id)">
                            <td class="align-middle">
                                {{ client.name }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
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
    }
</script>
<style scoped>
    .table tr:hover {
        cursor: pointer;
    }
</style>