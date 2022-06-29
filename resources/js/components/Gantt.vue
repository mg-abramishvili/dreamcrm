<template>
    <Loader v-if="views.loading"></Loader>

    <div v-else class="card flex-fill">
        <div class="card-body py-4">
            <gantt class="left-container" :productions="productions"></gantt>
        </div>
    </div>
</template>
<script>
import Gantt from './GanttComponent.vue';
import Loader from './Loader.vue'

export default {
    data () {
        return {
            productions: {
                data: [],
            },

            views: {
                loading: true,
            },
        }
    },
    created() {
        this.loadProductions()
    },
    methods: {
        loadProductions() {
            axios.get('/api/productions-gantt')
            .then(response => {
                this.productions.data = response.data

                this.views.loading = false
            })
        },
    },
    components: {
        Loader,
        Gantt
    }
}
</script>

<style>
    .left-container {
        overflow: hidden;
        position: relative;
        height: 500px;
    }
    .gantt_task_line {
        background-color: #fd7b38;
        border-color: #fd7b38;
        border-radius: 5px;
    }
</style>