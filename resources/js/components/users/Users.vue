<template>
    <div>
        <div class="row align-items-center mb-4">
            <div class="col-12 col-lg-6">
                <h1 class="h3 m-0">Пользователи</h1>
            </div>
        </div>

        <div class="card">
            <div class="card-body pt-1">
                <table class="table table-striped table-hover dataTable no-footer dtr-inline">
                    <thead>
                        <tr>
                            <th>Имя</th>
                            <th>E-mail</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="user in users" :key="user.id">
                            <td class="align-middle">
                                <router-link :to="{ name: 'User', params: {uid: user.uid} }"></router-link>
                                {{ user.name }}
                            </td>
                            <td class="align-middle">
                                {{ user.email }}
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
                users: [],
            }
        },
        created() {
            this.loadUsers()
        },
        methods: {
            loadUsers() {
                axios
                .get('/api/users')
                .then(response => (
                    this.users = response.data
                ))
            },
        },
    }
</script>