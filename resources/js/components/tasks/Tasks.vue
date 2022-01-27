<template>
    <div class="tasks-page">
        <div class="row align-items-center mb-4">
            <div class="col-12 col-lg-8">
                <h1 class="h3 m-0 d-inline-flex w-auto me-2">Задачи</h1>
                <select v-model="selected.board" @change="getTasks()" class="form-select d-inline-flex w-50">
                    <option v-for="board in boards" :key="'board_' + board.id" :value="board.id">
                        {{ board.name }}
                    </option>
                </select>
            </div>
            <div class="col-12 col-lg-4">
                
            </div>
        </div>
        
        <div v-if="views.loading == false">
            <div class="tasks-page-board">
                <div class="row">
                    <div class="col-12 col-lg-4">
                        <div class="card">
                            <div class="card-header pb-0">
                                <h5 class="card-title mb-0">Сделать</h5>
                            </div>
                            <div class="card-body">
                                <div @click="openTaskModal(task)" v-for="task in tasks.filter(task => task.status == 'todo')" :key="task.id" class="card mb-3 bg-light cursor-pointer border">
                                    <div class="card-body p-3">
                                        <p>{{ task.name }}</p>
                                        <div class="mt-n1">
                                            <span v-if="task.comments && task.comments.length > 0" class="btn btn-sm p-0">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-message-square"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>
                                                {{ task.comments.length }}
                                            </span>
                                            <img src="img/no-image.jpg" width="32" height="32" class="rounded-circle" alt="Avatar">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-12 col-lg-4">
                        <div class="card">
                            <div class="card-header pb-0">
                                <h5 class="card-title mb-0">В работе</h5>
                            </div>
                            <div class="card-body">
                                <div @click="openTaskModal(task)" v-for="task in tasks.filter(task => task.status == 'inprogress')" :key="task.id" class="card mb-3 bg-light cursor-pointer border">
                                    <div class="card-body p-3">
                                        <p>{{ task.name }}</p>
                                        <div class="mt-n1">
                                            <span v-if="task.comments && task.comments.length > 0" class="btn btn-sm p-0">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-message-square"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>
                                                {{ task.comments.length }}
                                            </span>
                                            <img src="img/no-image.jpg" width="32" height="32" class="rounded-circle" alt="Avatar">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-12 col-lg-4">
                        <div class="card">
                            <div class="card-header pb-0">
                                <h5 class="card-title mb-0">Выполнено</h5>
                            </div>
                            <div class="card-body">
                                <div @click="openTaskModal(task)" v-for="task in tasks.filter(task => task.status == 'completed')" :key="task.id" class="card mb-3 bg-light cursor-pointer border">
                                    <div class="card-body p-3">
                                        <p>{{ task.name }}</p>
                                        <div class="mt-n1">
                                            <span v-if="task.comments && task.comments.length > 0" class="btn btn-sm p-0">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-message-square"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>
                                                {{ task.comments.length }}
                                            </span>
                                            <img src="img/no-image.jpg" width="32" height="32" class="rounded-circle" alt="Avatar">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <TaskModal v-if="views.modal" :task="selected.task"></TaskModal>
        <div v-if="views.modal" class="modal-backdrop fade show"></div>
    </div>
</template>

<script>
    import TaskModal from './TaskModal.vue'

    export default {
        data() {
            return {
                boards: [],
                tasks: [],

                selected: {
                    board: {},
                    task: {},
                },

                views: {
                    loading: true,
                    modal: false,
                },
            }
        },
        created() {
            this.getBoards()
        },
        computed: {

        },
        methods: {
            getBoards() {
                axios
                .get('/api/boards')
                .then((response => {
                    this.boards = response.data

                    this.views.loading = false

                    if(response.data[0]) {
                        this.selected.board = response.data[0].id
                        this.getTasks()
                    }
                }))
            },
            getTasks() {
                axios
                .get(`/api/tasks/board/${this.selected.board}`)
                .then(response => (
                    this.tasks = response.data
                ))
            },
            openTaskModal(task) {
                this.selected.task = task
                this.views.modal = true
            },
        },
        components: {
            TaskModal
        }
    }
</script>