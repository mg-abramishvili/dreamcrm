<template>
    <div @keyup.esc="closeModal()" class="modal fade show" tabindex="-1" style="display: block;" aria-modal="true" role="dialog">
        <div v-if="task && task.id > 0" class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ task.name }}</h5>
                    <button @click="closeModal()" type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body m-3">
                    <div class="row">
                        <div class="col-12 col-lg-9">
                            <template v-if="views.changeDescription">
                                <TaskChangeDescription :task="task"></TaskChangeDescription>
                            </template>
                            <template v-else>
                                <p v-if="task.description" class="mb-0">{{ task.description }}</p>
                                <p v-else class="mb-0">Нет описания</p>
                            </template>

                            <TaskModalComment :task_id="task.id"></TaskModalComment>
                        </div>
                        <div class="col-12 col-lg-3">
                            <h6 class="text-muted mb-0">Статус</h6>
                            <span v-if="task.status == 'active'" class="badge rounded-pill bg-primary text-sm">В работе</span>
                            <span v-if="task.status == 'completed'" class="badge rounded-pill bg-success text-sm">Выполнено</span>
                            
                            <h6 class="text-muted mt-4">Участники</h6>
                            <div v-for="user in task.users" :key="'task_user_' + user.id" class="d-flex align-items-center mb-1">
                                <img :src="user.avatar" width="36" height="36" class="rounded-circle me-2" alt="">
                                <div class="flex-grow-1">
                                    <strong>{{ user.name }}</strong>
                                </div>
                            </div>
                            
                            <h6 class="text-muted mt-4">Действия</h6>
                            <div style="position: relative;">
                                <button @click="addUser()" class="w-100 btn btn-outline-primary mb-2">Добавить участника</button>
                                <TaskAddUser v-if="views.addUser" :task="task"></TaskAddUser>
                            </div>

                            <button @click="views.changeDescription = true" class="w-100 btn btn-outline-primary mb-2">Изменить описание</button>
                            <button v-if="task.status !== 'completed'" @click="completeTask()" class="w-100 btn btn-success">Отметить как выполненную</button>
                            <button v-if="task.status == 'completed'" @click="returnTask()" class="w-100 btn btn-warning">Вернуть в работу</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import TaskModalComment from './TaskModalComment.vue'
    import TaskChangeDescription from './TaskChangeDescription.vue'
    import TaskAddUser from './TaskAddUser.vue'
    
    export default {
        props: ['task_id'],
        data() {
            return {
                task: {},

                views: {
                    changeDescription: false,
                    addUser: false,
                },
            }
        },
        created() {
            this.getTask()
        },
        mounted() {
            document.getElementsByClassName('modal')[0].focus()
            document.body.style.overflow = "hidden"

            var modal = document.getElementsByClassName('modal')[0]
            window.onclick = () => {
                if (event.target == modal) {
                    this.closeModal()
                }
            }
        },
        methods: {
            getTask() {
                axios
                .get(`/api/task/${this.task_id}`)
                .then(response => (
                    this.task = response.data
                ))
            },
            closeModal() {
                this.$parent.views.modals.openTask = false
                this.$parent.views.modals.showBackdrop = false
                document.body.style.overflow = "auto"
            },
            completeTask() {
                if (confirm("Точно выполнена?")) {
                    axios
                    .put(`/api/task/${this.task.id}/update`, {
                        status: 'completed'
                    })
                    .then(response => (
                        this.$parent.getColumns(),
                        this.closeModal()
                    ))
                }
            },
            returnTask() {
                if (confirm("Точно вернуть в работу?")) {
                    axios
                    .put(`/api/task/${this.task.id}/update`, {
                        status: 'active'
                    })
                    .then(response => (
                        this.$parent.getColumns(),
                        this.closeModal()
                    ))
                }
            },
            addUser() {
                if(this.views.addUser == true) {
                    this.views.addUser = false
                } else {
                    this.views.addUser = true
                }
            },
        },
        components: {
            TaskModalComment,
            TaskChangeDescription,
            TaskAddUser
        },
    }
</script>
