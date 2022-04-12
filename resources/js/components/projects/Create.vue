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

        <div v-if="views.step == 'check'" class="row">
            <div class="col-12 col-lg-6">
                <div class="card card-bordered">
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">ИНН клиента</label>
                            <input v-model="client.inn" type="text" class="form-control">
                        </div>
                        
                        <button @click="checkClient()" class="btn btn-primary">Проверить</button>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-6">
                <div class="card card-bordered">
                    <div class="card-body">
                        <div v-if="projects.length && !views.loading">
                            <ul class="list-group my-2">
                                <li v-for="project in projects" :key="project.id" class="list-group-item">
                                    <strong>{{ project.name }}</strong>
                                    <br>
                                    Клиент: {{ project.client.name }} (ИНН: {{ project.client.inn }})
                                </li>
                            </ul>
                            <button @click="goToRegistration('draft')" class="btn btn-primary">Продолжить регистрацию (черновик)</button>
                        </div>
                        <div v-if="!projects.length && !views.loading">
                            <p>ОК! Проектов с таким ИНН нет.</p>
                            <button @click="goToClientCreate()" class="btn btn-primary">Продолжить регистрацию</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <CreateClient v-if="views.step == 'clientCreate'" :innData="client.inn" />

        <div v-if="views.step == 'registration'" class="card card-bordered">
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
                projects: [],

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

                    step: 'check',
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
            checkClient() {
                if(!this.client.inn) {
                    return this.$swal({
                        text: 'Укажите ИНН',
                        icon: 'error',
                    })
                }

                this.views.loading = true

                axios.get(`/api/projects/check`, { params: {
                    inn: this.client.inn
                }})
                .then(response => {
                    this.projects = response.data

                    if(this.projects.length) {
                        this.views.loading = false
                    } else {
                        this.views.loading = false
                    }
                })
            },
            goToClientCreate() {
                this.views.step = 'clientCreate'
            },
            goToRegistration(status) {
                this.project.status = status
                
                this.views.step = 'registration'
            },
            save() {
                axios.post(`/api/projects`, {
                    calculation_id: this.$route.params.calculation_id,
                    user_id: this.selected.user,
                    name: this.project.name,
                    status: this.project.status,
                    client_name: this.client.name,
                    client_inn: this.client.inn,
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