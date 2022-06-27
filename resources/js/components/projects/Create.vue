<template>
    <div class="projects-page">
        <div class="card card-bordered">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-12 col-lg-6">
                        <h1 class="h3 m-0">Новый проект</h1>
                    </div>
                </div>
            </div>
        </div>

        <div v-if="views.step == 'inn'">
            <div class="card card-bordered">
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">ИНН клиента</label>
                        <input v-model="client.inn" type="text" class="form-control">
                    </div>
                    
                    <button @click="goToStep('client')" class="btn btn-primary">Проверить</button>
                </div>
            </div>
        </div>

        <div v-if="views.step == 'client' && client.id && !views.loading">
            <div class="card card-bordered">
                <div class="card-body">
                    <p class="fw-bold text-danger">Найден клиент с указанным ИНН</p>
                    
                    <ul class="list-group my-2">
                        <li class="list-group-item">
                            <strong>{{ client.name }} ({{ client.inn }})</strong>
                        </li>
                    </ul>

                    <div v-if="projects_as_client && projects_as_client.length">
                        <p class="fw-bold text-danger mt-2">Проекты с этим клиентом:</p>
                        
                        <ul class="list-group my-2">
                            <li v-for="project in projects_as_client" :key="project.id" class="list-group-item">
                                <strong>{{ project.name }}</strong>
                            </li>
                        </ul>
                    </div>

                    <button @click="goToStep('registration')" class="btn btn-primary">Продолжить регистрацию (черновик)</button>
                </div>
            </div>
        </div>

        <CreateClient v-if="views.step == 'client' && !client.id && !views.loading" :innData="client.inn" />

        <div v-if="views.step == 'registration' && !views.loading" class="card card-bordered">
            <div class="card-body">
                <template v-if="project.status == 'draft'">
                    <p class="text-danger mb-4">Проект будет зарегистрирован как черновик, т.к. проверка обнаружила схожие данные по заказчику в других проектах.</p>
                </template>

                <div class="row">
                    <div class="col-12 col-lg-4">
                        <div class="mb-3">
                            <label class="form-label">Название проекта</label>
                            <input v-model="project.name" type="text" class="form-control">
                        </div>
                    </div>
                </div>
                
                <button @click="save()" class="btn btn-primary">Сохранить проект</button>
            </div>
        </div>
    </div>
</template>

<script>
    import CreateClient from '../clients/ClientCreate'

    export default {
        data() {
            return {
                users: [],
                projects_as_client: [],

                client: {
                    id: '',
                    name: '',
                    inn: '',
                },

                project: {
                    name: '',
                    status: '',
                },

                selected: {
                    user: '',
                },

                views: {
                    loading: true,

                    step: 'inn',
                }
            }
        },
        created() {
            this.loadUsers()
        },
        methods: {
            loadUsers() {
                axios.get(`/api/users`)
                .then(response => {
                    this.users = response.data
                    
                    this.selected.user = this.$parent.user.id
                })
            },
            goToStep(step) {
                if(step == 'client') {
                    if(!this.client.inn) {
                        return this.$swal({
                            text: 'Укажите ИНН',
                            icon: 'error',
                        })
                    }

                    this.checkClientInn()
                }

                this.views.step = step
            },
            checkClientInn() {
                axios.get(`/api/clients/${this.client.inn}`)
                .then(response => {
                    if(response.data) {
                        this.client.id = response.data.id
                        this.client.name = response.data.name
                    }

                    if(response.data.projects_as_client) {
                        this.projects_as_client = response.data.projects_as_client
                    }

                    if(response.data.id) {
                        this.project.status = 'draft'
                    } else {
                        this.project.status = 'normal'
                    }

                    this.views.loading = false
                })
            },
            save() {
                axios.post(`/api/projects`, {
                    calculation_id: this.$route.params.calculation_id,
                    user_id: this.selected.user,
                    name: this.project.name,
                    status: this.project.status,
                    client_id: this.client.id,
                })
                .then(response => {
                    this.$router.push({name: 'Project', params: {id: response.data}})
                })
            }
        },
        components: {
            CreateClient
        }
    }
</script>