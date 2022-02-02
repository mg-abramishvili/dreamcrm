<template>
    <div class="w-75 d-flex mb-2">
        <input v-model="name" class="form-control" placeholder="Название">
        <button @click="updateName()" class="btn btn-primary">Сохранить</button>
    </div>
</template>

<script>
export default {
    props: ['task'],
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
                this.$parent.$parent.getColumns(),
                this.$parent.views.changeTaskName = false
            ))
        }
    },
}
</script>