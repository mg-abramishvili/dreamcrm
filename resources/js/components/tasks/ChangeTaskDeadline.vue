<template>
    <div>
        <input type="date" v-model="deadline" class="form-control w-25 d-inline-flex">
        <button @click="updateDeadline()" class="btn btn-primary">Сохранить</button>
    </div>
</template>

<script>
export default {
    props: ['task', 'board_id'],
    data() {
        return {
            //
        }
    },
    created() {
        this.getDeadline()
    },
    methods: {
        getDeadline() {
            this.deadline = this.task.deadline
        },
        updateDeadline() {
            axios
            .put(`/api/task/${this.task.id}/update`, {
                deadline: this.deadline,
            })
            .then(response => (
                this.$parent.getTask(),
                this.$parent.$parent.getBoard(this.board_id),
                this.$parent.views.changeTaskDeadline = false
            ))
        }
    },
}
</script>