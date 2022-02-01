<template>
    <div @keyup.esc="closeModal()" class="modal fade show" tabindex="-1" style="display: block;" aria-modal="true" role="dialog">
        <div v-if="task && task.id > 0" class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ task.name }}</h5>
                    <button @click="closeModal()" type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body m-3">
                    <span class="text-muted">{{ task.column.name }}</span>
                    <div class="row mt-4">
                        <div class="col-12 col-lg-9">
                            <div class="mb-4">
                                <div class="mb-1">
                                    <span class="text-muted"><strong>Срок</strong></span>
                                    <button v-if="task.column.board.admin == $parent.$parent.user.id" @click="changeDeadline()" class="btn btn-sm btn-outline-secondary">Изменить</button>
                                </div>

                                <template v-if="views.changeDeadline">
                                    <TaskChangeDeadline :task="task"></TaskChangeDeadline>
                                </template>
                                <template v-else>
                                    <p v-if="task.deadline" class="mb-0">{{ task.deadline | formatDateLong }}</p>
                                    <p v-else class="mb-0">Не указан</p>
                                </template>
                            </div>

                            <div class="mb-4">
                                <div class="mb-1">
                                    <span class="text-muted"><strong>Описание</strong></span>
                                    <button v-if="task.column.board.admin == $parent.$parent.user.id" @click="changeDescription()" class="btn btn-sm btn-outline-secondary">Изменить</button>
                                </div>

                                <template v-if="views.changeDescription">
                                    <TaskChangeDescription :task="task"></TaskChangeDescription>
                                </template>
                                <template v-else>
                                    <p v-if="task.description" class="mb-0">{{ task.description }}</p>
                                    <p v-else class="mb-0">Нет описания</p>
                                </template>
                            </div>

                            <div class="mb-4">
                                <div class="mb-1">
                                    <span class="text-muted"><strong>Файлы</strong></span>
                                    <button @click="openFileUpload()" class="btn btn-sm btn-outline-secondary">Добавить</button>
                                </div>

                                <template v-if="views.openFileUpload">
                                    <TaskFileUpload :task="task"></TaskFileUpload>
                                </template>
                                <template>
                                    <ul v-if="task.files && task.files.length > 0" class="tasks-file-list">
                                        <li v-for="file in task.files" :key="'task_file_' + file.id">
                                            <a :href="'/uploads/' + file.name" target="_blank">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file align-middle me-2"><path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"></path><polyline points="13 2 13 9 20 9"></polyline></svg>
                                                {{ file.name }}
                                            </a>
                                        </li>
                                    </ul>
                                    <p v-else class="mb-0">Нет описания</p>
                                </template>
                            </div>

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
    import TaskChangeDeadline from './TaskChangeDeadline.vue'
    import TaskFileUpload from './TaskFileUpload.vue'
    import TaskAddUser from './TaskAddUser.vue'
    
    export default {
        props: ['task_id'],
        data() {
            return {
                task: {},

                views: {
                    changeDescription: false,
                    changeDeadline: false,
                    openFileUpload: false,
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
            changeDescription() {
                if(this.views.changeDescription == true) {
                    this.views.changeDescription = false
                } else {
                    this.views.changeDescription = true
                }
            },
            changeDeadline() {
                if(this.views.changeDeadline == true) {
                    this.views.changeDeadline = false
                } else {
                    this.views.changeDeadline = true
                }
            },
            openFileUpload() {
                if(this.views.openFileUpload == true) {
                    this.views.openFileUpload = false
                } else {
                    this.views.openFileUpload = true
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
            TaskChangeDeadline,
            TaskFileUpload,
            TaskAddUser
        },
    }
</script>
