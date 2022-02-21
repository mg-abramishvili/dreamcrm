<template>
    <div class="w-75 d-flex">
        <input v-model="name" class="modal-title modal-title-input" placeholder="Название">
        <button @click="updateName()" class="btn btn-primary">Сохранить</button>
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
        this.getName()
    },
    methods: {
        getName() {
            this.name = this.task.name
        },
        updateName() {
            axios
            .put(`/api/task/${this.task.id}/update`, {
                name: this.name,
            })
            .then(response => (
                this.$parent.getTask(),
                this.$parent.$parent.getBoard(this.board_id),
                this.$parent.views.changeTaskName = false
            ))
        }
    },
}
</script>