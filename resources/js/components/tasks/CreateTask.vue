<template>
    <div class="card m-0 mb-2" style="box-shadow: none;">
        <div class="card-body bg-light cursor-pointer p-3">
            <textarea v-model="name" class="form-control mb-1" placeholder="Название задачи"></textarea>
            <button @click="saveTask()" class="btn btn-primary">Сохранить</button>
        </div>
    </div>
</template>

<script>    
    export default {
        props: ['column_id'],
        data() {
            return {
                name: '',
            }
        },
        methods: {
            saveTask() {
                axios
                .post(`/api/tasks`, {
                    column_id: this.column_id,
                    name: this.name,
                })
                .then((response => {
                    this.name = ''
                    this.$parent.$parent.getColumns()
                    this.$parent.$parent.views.createTask = false
                }))
            },
        },
    }
</script>
