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

        <div v-if="views.checkPage" class="row">
            <div class="col-12 col-lg-6">
                <div class="card card-bordered">
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">ИНН клиента</label>
                            <input v-model="client.inn" type="text" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Название компании</label>
                            <input v-model="client.name" type="text" class="form-control">
                        </div>
                        
                        <button @click="checkClient()" class="btn btn-primary">Проверить</button>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-6">
                <div class="card card-bordered">
                    <div class="card-body">
                        <ul v-if="projects.length" class="list-group my-2">
                            <li v-for="project in projects" :key="project.id" class="list-group-item">
                                <strong>{{ project.name }}</strong>
                                <br>
                                Клиент: {{ project.client.name }} (ИНН: {{ project.client.inn }})
                            </li>
                        </ul>
                        <button v-if="views.nextButton" @click="goToRegistration()" class="btn btn-primary">Продолжить регистрацию</button>
                        <button v-if="views.nextButtonDraft" @click="goToRegistrationDraft()" class="btn btn-primary">Продолжить регистрацию (черновик)</button>
                    </div>
                </div>
            </div>
        </div>

        <div v-if="views.registrationPage" class="card card-bordered">
            <div class="card-body">
                <template v-if="status == 'draft'">
                    <p class="text-danger mb-4">Проект будет зарегистрирован как черновик, т.к. проверка обнаружила схожие данные по заказчику в других проектах.</p>
                </template>

                <div class="row">
                    <div class="col-12 col-lg-4">
                        <div class="mb-3">
                            <label class="form-label">ИНН клиента</label>
                            <input v-model="client.inn" type="text" class="form-control" disabled>
                        </div>
                    </div>
                    <div class="col-12 col-lg-4">
                        <div class="mb-3">
                            <label class="form-label">Название компании</label>
                            <input v-model="client.name" type="text" class="form-control" disabled>
                        </div>
                    </div>
                    <div class="col-12 col-lg-4">
                        <div class="mb-3">
                            <label class="form-label">Ответственный</label>
                            <select v-model="selected.user" class="form-select" disabled>
                                <option v-for="user in users" :value="user.id">{{ user.name }}</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Название проекта</label>
                    <input v-model="name" type="text" class="form-control">
                </div>
                
                <button @click="save()" class="btn btn-primary">Сохранить проект</button>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                users: [],
                projects: [],

                client: {
                    inn: '',
                    name: '',
                },

                selected: {
                    user: '',
                },

                status: '',

                views: {
                    checkPage: true,
                    registrationPage: false,

                    nextButton: false,
                    nextButtonDraft: false,
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
                if(!this.client.name) {
                    return this.$swal({
                        text: 'Укажите название компании',
                        icon: 'error',
                    })
                }

                axios.get(`/api/projects/check`, { params: {
                    inn: this.client.inn,
                    name: this.client.name,
                }})
                .then(response => {
                    let projectsWithInn = response.data.inn
                    let projectsWithName = response.data.name
                    let projects = projectsWithInn.concat(projectsWithName)
                    
                    projects = projects.filter((v,i,a)=>a.findIndex(v2=>(v2.id===v.id))===i)

                    this.projects = projects

                    if(this.projects.length) {
                        this.views.nextButton = false
                        this.views.nextButtonDraft = true
                    } else {
                        this.views.nextButton = true
                        this.views.nextButtonDraft = false
                    }
                })
            },
            goToRegistration() {
                this.views.checkPage = false
                this.views.registrationPage = true

                this.status = 'active'
            },
            goToRegistrationDraft() {
                this.views.checkPage = false
                this.views.registrationPage = true

                this.status = 'draft'
            },
            save() {
                axios.post(`/api/projects`, {
                    calculation_id: this.$route.params.calculation_id,
                    name: this.name,
                    user_id: this.selected.user,
                    client_name: this.client.name,
                    client_inn: this.client.inn,
                    status: this.status
                })
                .then(response => {
                    this.$router.push({name: 'Project', params: {id: response.data}})
                })
            }
        },
    }
</script>