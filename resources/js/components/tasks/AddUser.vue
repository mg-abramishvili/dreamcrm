<template>
    <div class="dropdown-menu dropdown-menu-end show">
        <div v-if="views.loading == false">
            <a @click="addUser(user.id)" v-for="user in users.filter(user => user.id != task.column.board.admin)" :key="'user_' + user.id" class="dropdown-item">
                {{ user.name }}
                <svg v-if="task.users.find(task_user => task_user.id == user.id)" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check align-middle me-2"><polyline points="20 6 9 17 4 12"></polyline></svg>
            </a>
        </div>
    </div>
</template>

<script>    
    export default {
        props: ['task', 'board_id'],
        data() {
            return {
                users: [],

                views: {
                    loading: true,
                    userList: false,
                },
            }
        },
        created() {
            this.getUsers()
        },
        methods: {
            getUsers() {
                axios
                .get(`/api/users`)
                .then(response => (
                    this.users = response.data,
                    this.views.loading = false
                ))
            },
            closeForm() {
                this.$parent.views.addUser = false
            },
            addUser(user_id) {
                axios
                .put(`/api/task/${this.task.id}/update`, {
                    user_id: user_id
                })
                .then(response => (
                    this.$parent.getTask(),
                    this.$parent.$parent.getBoard(this.board_id),
                    this.closeForm()
                ))
            },
        },
    }
</script>