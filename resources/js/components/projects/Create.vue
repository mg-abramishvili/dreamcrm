<template>
    <div class="projects-page">
        <div class="card card-bordered">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-12 col-lg-6">
                        <h1 class="h3 m-0">Новый проект {{$route.params.calculation_id}}</h1>
                    </div>
                </div>
            </div>
        </div>

        <div class="card card-bordered">
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">ИНН клиента</label>
                    <input v-model="clientInn" type="text" class="form-control">
                </div>
                
                <button @click="checkClientInn()" class="btn btn-primary">Проверить</button>

                <ul v-if="projects && projects.length > 0" class="list-group mt-3">
                    <li v-for="project in projects" :key="project.id" class="list-group-item">
                        <strong>{{ project.name }}</strong>
                        <br>
                        Клиент: {{ project.client.name }} (ИНН: {{ project.client.inn }})
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                clientInn: '',

                projects: [],
            }
        },
        created() {
            //
        },
        methods: {
            checkClientInn() {
                if(this.clientInn) {
                    axios.get(`/api/projects/inn_check/${this.clientInn}`)
                    .then(response => (
                        this.projects = response.data
                    ))
                }
            },
            save() {
                axios.post(`/api/projects`, {
                    calculation_id: this.$route.params.calculation_id,
                    name: this.name,
                    user: this.$parent.user.id,
                    client_id: this.client_id
                })
            }
        },
    }
</script>