<template>
    <Loader v-if="views.loading"></Loader>
    <gantt v-else class="left-container" :productions="productions"></gantt>
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
</style>