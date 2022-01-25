<template>
    <div>
        <ul>
            <li v-for="task in tasks" :key="task.id">
                {{ task.name }}
            </li>
        </ul>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                tasks: [],
            }
        },
        created() {
            axios
            .get('/api/tasks')
            .then(response => (
                this.tasks = response.data
            ))
        },
        methods: {
        },
    }
</script>