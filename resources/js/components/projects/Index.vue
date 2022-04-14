<template>
    <div class="projects-page">
        <div class="card card-bordered">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-12 col-lg-6">
                        <h1 class="h3 m-0">Проекты</h1>
                    </div>
                    <!-- <div class="col-12 col-lg-6 text-end">
                        <router-link :to="{name: 'ProjectCreate'}" class="btn btn-primary">Создать</router-link>
                    </div> -->
                </div>
            </div>
        </div>

        <div v-if="!views.loading" class="card card-bordered">
            <div class="card-body p-0">
                <table class="table table-hover dataTable">
                    <thead>
                        <tr>
                            <th>Название</th>
                            <th>Корпус</th>
                            <th>Автор</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="project in projects" :key="project.id" @click="goTo(project.id)">

                            <td class="align-middle">
                                {{ project.name }} <span v-if="project.status == 'draft'" class="badge rounded-pill bg-warning">черновик</span>
                            </td>
                            <td class="align-middle">
                                <template v-if="project.calculations.length && project.calculations[0].boxes && project.calculations[0].boxes[0]">
                                    {{ project.calculations[0].boxes[0].name }}
                                </template>
                            </td>
                            <td class="align-middle">
                                {{ project.user.name }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <Loader v-else></Loader>
    </div>
</template>

<script>
    import Loader from '../Loader.vue'

    export default {
        data() {
            return {
                projects: [],

                views: {
                    loading: true
                }
            }
        },
        created() {
            axios
                .get('/api/projects')
                .then(response => (
                    this.projects = response.data,
                    this.views.loading = false
                ));
        },
        methods: {
            goTo(id) {
                this.$router.push({ name: 'Project', params: { id: id } });
            }
        },
        components: {
            Loader
        }
    }
</script>

<style scoped>
    .table tr:hover {
        cursor: pointer;
    }
</style>