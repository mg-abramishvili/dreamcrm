<template>
    <div class="d-inline-flex w-100 tasks-new-board-form">
        <input v-model="name" type="text" class="form-control w-50" placeholder="Название новой доски">
        <button @click="saveTaskBoard()" class="btn btn-primary mx-1">OK</button>
        <button @click="cancelCreateTaskBoard()" class="btn btn-outline-danger">Отмена</button>
    </div>
</template>

<script>
export default {
        data() {
            return {
                name: '',
            }
        },
        mounted() {
            document.getElementsByClassName('form-control')[0].focus()
        },
        methods: {
            saveTaskBoard() {
                axios
                .post(`/api/tasks/boards`, {
                    name: this.name
                })
                .then(response => (
                    this.name = '',
                    this.$parent.views.createTaskBoard = false,
                    this.$parent.getBoards(response.data.id)
                ))
            },
            cancelCreateTaskBoard() {
                this.name = '',
                this.$parent.views.createTaskBoard = false
            },
        },
    }
</script>