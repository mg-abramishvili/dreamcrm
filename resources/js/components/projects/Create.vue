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

        <div class="card card-bordered">
            <div class="card-body">
                <div class="row">
                    <div class="col-12 col-lg-6">
                        <div class="mb-3">
                            <label class="form-label">ИНН клиента</label>
                            <input v-model="client.inn" type="text" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Название компании</label>
                            <input v-model="client.name" type="text" class="form-control">
                        </div>
                    </div>
                    <div class="col-12 col-lg-6">
                        <ul v-if="projects.length" class="list-group mt-3">
                            <li v-for="project in projects" :key="project.id" class="list-group-item">
                                <strong>{{ project.name }}</strong>
                                <br>
                                Клиент: {{ project.client.name }} (ИНН: {{ project.client.inn }})
                            </li>
                        </ul>
                    </div>
                </div>
                
                <button @click="checkClient()" class="btn btn-primary">Проверить</button>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                client: {
                    inn: '',
                    name: '',
                },

                projects: [],
            }
        },
        created() {
            //
        },
        methods: {
            checkClient() {
                if(!this.client.inn && !this.client.name) {
                    return this.$swal({
                        text: 'Укажите хоть что-нибудь',
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
                })
            },
            save() {
                //
            }
        },
    }
</script>