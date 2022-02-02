<template>
    <div class="tasks-page">
        <div class="row tasks-page-header">
            <div class="col-12 col-lg-9">
                <h1 class="h3 m-0 d-inline-flex w-auto me-2">Задачи</h1>
                <div class="d-inline-flex w-75">
                    <select v-if="views.createTaskBoard == false" v-model="selected.board" @change="getColumns()" class="form-select w-50">
                        <option v-for="board in boards" :key="'board_' + board.id" :value="board">
                            {{ board.name }}
                        </option>
                    </select>
                    <CreateTaskBoard v-if="views.createTaskBoard"></CreateTaskBoard>
                    <button v-if="$parent.user.permissions && $parent.user.permissions.can_see_all_boards && views.createTaskBoard == false" @click="openCreateTaskBoard()" class="btn btn-outline-primary mx-1">Создать доску</button>
                </div>
            </div>
            <div class="col-12 col-lg-3 text-end">
                <button v-if="selected.board.admin == $parent.user.id" @click="openCreateColumnModal()" class="btn btn-outline-primary">Добавить колонку</button>
            </div>
        </div>
        
        <template v-if="views.loading == false">
            <draggable v-dragscroll:nochilddrag v-model="columns" :move="detectMove" @change="moveColumn($event)" :disabled="views.draggable == false" class="tasks-page-board align-items-start">
                <div v-for="column in columns" :key="column.id" class="task-column">
                    <div class="card-header">
                        <div class="row align-items-center mb-2">
                            <div class="col-9">
                                <h5 class="card-title mb-0">{{ column.name }}</h5>
                            </div>
                            <div class="col-3 text-end">
                                <button v-if="column.board && column.board.admin == $parent.user.id || $parent.user.permissions && $parent.user.permissions.can_see_all_boards" @click="openCreateTask(column.id)" class="btn btn-sm btn-outline-primary">+</button>
                            </div>
                        </div>
                    </div>
                    <CreateTask v-if="views.createTask && selected.column == column.id" :column_id="selected.column"></CreateTask>
                    <draggable v-model="column.tasks" group="tasks" :move="detectMove" @change="moveTask($event, column.id)" :disabled="views.draggable == false" class="task-column-body">
                        <div @click="openTaskModal(task)" v-for="task in column.tasks" :key="task.id" class="card m-0" style="box-shadow: none;">
                            <div class="card-body bg-light cursor-pointer p-3">
                                <p>{{ task.name }}</p>
                                <div class="mt-n1">
                                    <div class="d-inline-flex me-2">
                                        <div v-for="user in task.users" :key="'task_user_' + user.id" style="margin: 0 2px;">
                                            <img :src="user.avatar" width="18" height="18" class="rounded-circle" :alt="user.name">
                                        </div>
                                    </div>
                                    <span v-if="task.comments && task.comments.length > 0" class="btn btn-sm p-0 d-inline-flex align-items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-message-square" style="margin-right: 3px;">
                                            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                                        </svg>
                                        {{ task.comments.length }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </draggable>
                </div>
            </draggable>
        </template>

        <TaskModal v-if="views.modals.openTask" :task_id="selected.task.id"></TaskModal>
        
        <CreateColumnModal v-if="views.modals.createColumn" :board_id="selected.board.id"></CreateColumnModal>
        
        <div v-if="views.modals.showBackdrop" class="modal-backdrop fade show"></div>
    </div>
</template>

<script>
    import TaskModal from './TaskModal.vue'
    import CreateColumnModal from './CreateColumnModal.vue'
    import CreateTask from './CreateTask.vue'
    import CreateTaskBoard from './CreateTaskBoard.vue'

    import draggable from 'vuedraggable'

    import { dragscroll } from 'vue-dragscroll'

    export default {
        data() {
            return {
                boards: [],
                columns: [],

                selected: {
                    board: {},
                    column: {},
                    task: {},
                },

                views: {
                    loading: true,
                    draggable: false,
                    createTask: false,
                    createTaskBoard: false,
                    modals: {
                        openTask: false,
                        createColumn: false,
                        showBackdrop: false,
                    },
                },
            }
        },
        created() {
            this.getBoards()
        },
        methods: {
            getBoards(board_id) {
                axios
                .get('/api/tasks/boards')
                .then((response => {
                    this.boards = response.data

                    this.views.loading = false

                    if(board_id) {
                        this.selected.board = response.data.find(board => board.id == board_id)
                        this.getColumns()
                        return
                    }

                    if(response.data[0]) {
                        console.log(response.data[0])
                        this.selected.board = response.data[0]
                        this.getColumns()
                    }
                }))
            },
            getColumns() {
                if(this.selected.board.admin == this.$parent.user.id) {
                    this.views.draggable = true
                } else {
                    this.views.draggable = false
                }

                axios
                .get(`/api/tasks/board/${this.selected.board.id}/columns`)
                .then(response => (
                    this.columns = response.data
                ))
            },
            openTaskModal(task) {
                this.selected.task = task
                this.views.modals.openTask = true
                this.views.modals.showBackdrop = true
            },
            openCreateColumnModal() {
                this.views.modals.createColumn = true
                this.views.modals.showBackdrop = true
            },
            openCreateTaskBoard() {
                this.views.createTaskBoard = true
            },
            openCreateTask(column) {
                this.selected.column = column
                this.views.createTask = true
            },
            moveTask(event, column_id) {
                console.log(event, column_id)
                if(event.added) {
                    var task_id = event.added.element.id
                    
                    axios
                    .put(`/api/task/${event.added.element.id}/update`, {
                        column_id: column_id
                    })
                    .then((response => {
                        //
                    }))
                    .catch((error) => {
                        alert('Ошибка сервера')
                    })
                }
            },
            moveColumn(event) {
                var reorderedColumns = this.columns.map(function(column, index){
                    {
                        return {
                            id: column.id,
                            index: index,
                        } 
                    }
                })

                axios
                .put(`/api/tasks/columns/reorder`, { columns: reorderedColumns })
                .then((response => {
                    //
                }))
                .catch((error) => {
                    alert('Ошибка сервера')
                })
            },
            detectMove: function(evt) {
                // console.log(evt)
            }
        },
        components: {
            TaskModal,
            CreateColumnModal,
            CreateTask,
            CreateTaskBoard,
            draggable
        },
        directives: {
            dragscroll
        }
    }
</script>