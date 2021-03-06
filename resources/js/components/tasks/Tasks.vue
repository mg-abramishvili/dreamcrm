<template>
    <div class="tasks-page">
        <div class="card card-bordered tasks-page-header">
            <div class="card-body p-0">
                <div class="row align-items-center">
                    <div class="col-12 col-lg-9">
                        <div class="d-inline-flex w-100">
                            <template v-if="views.boardCreate == false" class="form-select w-50">
                                <router-link :to="{name: 'Tasks', params: {board_id: board.id}}" v-for="board in boards" :key="'board_' + board.id" class="btn btn-pill me-1" :class="{'btn-primary': $route.params.board_id == board.id, 'btn-outline-primary': $route.params.board_id != board.id}">
                                    {{ board.name }}
                                    <span v-if="boardNotifications(board.id)" class="badge rounded-pill bg-danger">{{ boardNotifications(board.id) }}</span>
                                </router-link>
                            </template>

                            <BoardCreate v-if="views.boardCreate"></BoardCreate>

                            <button v-if="$parent.user.permissions && $parent.user.permissions.can_see_all_boards == true && views.boardCreate == false" @click="openBoardCreate()" class="btn btn-pill btn-outline-secondary">+</button>
                        </div>
                    </div>
                    <div class="col-12 col-lg-3 text-end">
                        <button @click="openBoardEdit()" class="btn btn-outline-secondary">Изменить доску</button>
                    </div>
                </div>
            </div>
        </div>
        
        <template v-if="board.id">
            <draggable v-dragscroll:nochilddrag v-model="board.columns" :move="detectMove" @change="moveColumn($event)" :disabled="views.draggable == false" draggable=".task-column" class="tasks-page-board align-items-start">
                <div v-for="column in board.columns" :key="column.id" class="task-column">
                    <div class="card-header">
                        <div class="row align-items-center mb-2">
                            <div class="col-9">
                                <h5 v-if="views.modals.columnChangeName == false" @click="openColumnChangeName(column, board)" class="card-title mb-0">
                                    {{ column.name }}
                                    <!-- <span v-if="columnNotifications(column.id)" class="badge rounded-pill bg-danger">{{ columnNotifications(column.id) }}</span> -->
                                </h5>
                            </div>
                            <div class="col-3 text-end">
                                <button v-if="board.admin == $parent.user.id || $parent.user.permissions && $parent.user.permissions.can_see_all_boards == true" @click="openCreateTask(column)" class="btn btn-sm btn-outline-primary">+</button>
                            </div>
                        </div>
                    </div>

                    <CreateTask v-if="views.createTask && selected.column.id == column.id" :column_id="selected.column.id" :board_id="board.id"></CreateTask>
                    
                    <draggable v-model="column.tasks" group="tasks" :move="detectMove" @change="moveTask($event, column)" :disabled="views.draggable == false" class="task-column-body">
                        <div @click="openTask(task)" v-for="task in column.tasks" :key="task.id" class="card m-0" style="box-shadow: none;">
                            <div class="card-body cursor-pointer p-0" :class="{ 'bg-success text-white': task.status == 'completed' }" style="white-space:normal">
                                <p>
                                    {{ task.name | truncate }}
                                    <span v-if="task.notifications.length" class="badge rounded-pill bg-danger">{{ task.notifications.length }}</span>
                                </p>
                                <!-- <div class="mt-n1">
                                    <div class="d-inline-flex me-2">
                                        <div v-for="user in task.users" :key="'task_user_' + user.id" style="margin: 0 2px;">
                                            <img v-if="user.avatar" :src="user.avatar" width="18" height="18" class="rounded-circle" :alt="user.name">
                                            <img v-else src="/img/no-image.jpg" width="18" height="18" class="rounded-circle" :alt="user.name">
                                        </div>
                                    </div>
                                    <span v-if="task.comments && task.comments.length > 0" class="btn btn-sm p-0 d-inline-flex align-items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-message-square" style="margin-right: 3px;">
                                            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                                        </svg>
                                        {{ task.comments.length }}
                                    </span>
                                </div> -->
                            </div>
                        </div>
                    </draggable>
                </div>
                <button v-if="board.admin == $parent.user.id" @click="openColumnCreate()" class="btn btn-outline-primary mt-4">Добавить колонку</button>
            </draggable>
        </template>
        <template v-else>
            <Loader></Loader>
        </template>

        <TaskItem v-if="views.modals.openTask" :task_id="selected.task.id" :board_id="$route.params.board_id"></TaskItem>
        
        <ColumnCreate v-if="views.modals.columnCreate" :board_id="$route.params.board_id"></ColumnCreate>
        
        <ColumnChangeName v-if="views.modals.columnChangeName" :column="selected.column" :board_id="$route.params.board_id"></ColumnChangeName>

        <BoardEdit v-if="views.modals.boardEdit" :board="board"></BoardEdit>

        <div v-if="views.modals.showBackdrop" class="modal-backdrop fade show"></div>
    </div>
</template>

<script>
    import Loader from '../Loader.vue'

    import TaskItem from './Task.vue'
    import BoardCreate from './BoardCreate.vue'
    import BoardEdit from './BoardEdit.vue'
    import ColumnCreate from './ColumnCreate.vue'
    import ColumnChangeName from './ColumnChangeName.vue'
    import CreateTask from './CreateTask.vue'

    import draggable from 'vuedraggable'

    import { dragscroll } from 'vue-dragscroll'

    export default {
        data() {
            return {
                boards: [],
                board: {},

                selected: {
                    column: {},
                    task: {},
                },

                notifications: [],

                views: {
                    draggable: false,
                    createTask: false,
                    boardCreate: false,
                    boardEdit: false,
                    modals: {
                        openTask: false,
                        columnCreate: false,
                        columnChangeName: false,
                        showBackdrop: false,
                    },
                },
            }
        },
        created() {
            this.getBoards()
            this.getNotifications()
        },
        methods: {
            getBoards() {
                axios
                .get('/api/tasks/boards')
                .then((response => {
                    this.boards = response.data

                    if(!this.$route.params.board_id) {
                        this.$router.push({name: 'Tasks', params: {board_id: response.data[0].id}})
                        this.getBoard(response.data[0].id)
                        return
                    }

                    this.getBoard(this.$route.params.board_id)
                }))
            },
            getBoard(id) {
                axios
                .get(`/api/tasks/board/${id}`)
                .then((response => {
                    this.board = response.data

                    if(response.data.admin == this.$parent.user.id) {
                        this.views.draggable = true
                    } else {
                        this.views.draggable = false
                    }
                }))
            },
            getNotifications() {
                axios
                .get(`/api/notifications/${this.$parent.user.id}`)
                .then(response => (
                    this.notifications = response.data
                ))
            },
            boardNotifications(board_id) {
                let notificationCounter = []

                this.notifications.forEach(notification => {
                    if(!notification.task) {
                        return
                    }

                    if(notification.task.column.board_id == board_id) {
                        notificationCounter.push(notification.task.column.board_id)
                    }
                })

                return notificationCounter.length
            },
            columnNotifications(column_id) {
                let notificationCounter = []

                this.notifications.forEach(notification => {
                    if(!notification.task) {
                        return
                    }

                    if(notification.task.column.id == column_id) {
                        notificationCounter.push(notification.task.column.id)
                    }
                })

                return notificationCounter.length
            },
            openTask(task) {
                this.selected.task = task
                this.views.modals.openTask = true
                this.views.modals.showBackdrop = true
            },
            openColumnCreate() {
                this.views.modals.columnCreate = true
                this.views.modals.showBackdrop = true
            },
            openColumnChangeName(column, board) {
                if(board.admin == this.$parent.user.id) {
                    this.selected.column = column
                    this.views.modals.columnChangeName = true
                    this.views.modals.showBackdrop = true
                }
            },
            openBoardCreate() {
                this.views.boardCreate = true
            },
            openBoardEdit() {
                this.views.modals.showBackdrop = true
                this.views.modals.boardEdit = true
            },
            openCreateTask(column) {
                this.selected.column = column
                this.views.createTask = true
            },
            moveTask(event, column) {
                if(event.added) {
                    axios
                    .put(`/api/task/${event.added.element.id}/update`, {
                        column_id: column.id
                    })
                    .then((response => {
                        //
                    }))
                    .catch((error) => {
                        alert('Ошибка сервера')
                    })
                }
                
                var reorderedTasks = column.tasks.map(function(task, index) {
                    {
                        return {
                            id: task.id,
                            index: index,
                        } 
                    }
                })
                axios
                .put(`/api/tasks/reorder`, { tasks: reorderedTasks })
                .then((response => {
                    //
                }))
                .catch((error) => {
                    alert('Ошибка сервера')
                })
            },
            moveColumn(event) {
                var reorderedColumns = this.board.columns.map(function(column, index) {
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
            Loader,
            TaskItem,
            ColumnCreate,
            ColumnChangeName,
            CreateTask,
            BoardCreate,
            BoardEdit,
            draggable
        },
        directives: {
            dragscroll
        }
    }
</script>