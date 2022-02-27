<template>
    <div>
        <div class="row align-items-center mb-4">
            <div class="col-12 col-lg-6">
                <h1 class="h3 m-0">Проекты</h1>
            </div>
            <!-- <div class="col-12 col-lg-6 text-end">
                <router-link :to="{name: 'ProjectCreate'}" class="btn btn-primary">Создать</router-link>
            </div> -->
        </div>

        <div class="card card-bordered">
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
                                {{ project.name }}
                            </td>
                            <td class="align-middle">
                                
                            </td>
                            <td class="align-middle">
                                
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                projects: [],
            }
        },
        created() {
            axios
                .get('/api/projects')
                .then(response => (
                    this.projects = response.data
                ));
        },
        methods: {
            goTo(id) {
                this.$router.push({ name: 'Project', params: { id: id } });
            }
        },
        components: {
        }
    }
</script>

<style scoped>
    .table tr:hover {
        cursor: pointer;
    }
</style>