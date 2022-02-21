<template>
    <div>
        <textarea v-model="description" class="form-control mb-2" placeholder="Описание задачи" style="resize: vertical;"></textarea>
        <button @click="updateDescription()" class="btn btn-primary">Сохранить</button>
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
        this.getDescription()
    },
    methods: {
        getDescription() {
            this.description = this.task.description
        },
        updateDescription() {
            axios
            .put(`/api/task/${this.task.id}/update`, {
                description: this.description,
            })
            .then(response => (
                this.$parent.getTask(),
                this.$parent.$parent.getBoard(this.board_id),
                this.$parent.views.changeTaskDescription = false
            ))
        }
    },
}
</script>