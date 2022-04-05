<template>
    <div class="projects-page">
        <div class="card card-bordered">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-12 col-lg-6">
                        <h1 class="h3 m-0">
                            <router-link :to="{name: 'Projects'}" class="back-arrow">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left align-middle me-2"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                            </router-link>
                            <strong>
                                {{ project.name }}
                            </strong>
                        </h1>    
                    </div>
                    <div v-if="!views.loading" class="col-12 col-lg-6 text-end">
                        <button v-if="!project.production" @click="toProduction()" class="btn btn-primary">Пуск в производство</button>
                    </div>
                </div>
            </div>
        </div>

        <Loader v-if="views.loading"></Loader>

        <div v-if="!views.loading" class="tab">
            <ul class="nav nav-tabs" role="tablist">
                <li class="nav-item">
                    <a @click="selectTab('general')" class="nav-link" :class="{'active': selected.tab == 'general'}" role="tab">Общая информация</a>
                </li>
                <li class="nav-item">
                    <a @click="selectTab('calculations')" class="nav-link" :class="{'active': selected.tab == 'calculations'}" role="tab">Расчеты</a>
                </li>
                <li class="nav-item">
                    <a @click="selectTab('offers')" class="nav-link" :class="{'active': selected.tab == 'offers'}" role="tab">КП</a>
                </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane" :class="{'active': selected.tab == 'general'}" role="tabpanel">
                    <p><strong>Клиент:</strong> {{ project.client.name }}</p>
                    <p><strong>Ответственный:</strong> {{ project.user.name }}</p>
                </div>
                <div class="tab-pane" :class="{'active': selected.tab == 'calculations'}" role="tabpanel">
                    <ProjectCalculations :calculations="project.calculations"></ProjectCalculations>
                </div>
                <div class="tab-pane" :class="{'active': selected.tab == 'offers'}" role="tabpanel">
                    <ProjectOffers :project_id="project.id" :offers="project.offers"></ProjectOffers>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Loader from '../Loader.vue'
    import ProjectCalculations from './ProjectCalculations.vue'
    import ProjectOffers from './ProjectOffers.vue'

    export default {
        data() {
            return {
                project: {},

                selected: {
                    tab: 'general',
                },

                views: {
                    loading: true,
                }
            }
        },
        created() {
            axios.get(`/api/project/${this.$route.params.id}`)
            .then(response => (
                this.project = response.data,
                this.views.loading = false
            ))
        },
        methods: {
            selectTab(tab) {
                this.selected.tab = tab
            },
            toProduction() {
                axios.post(`/api/productions`, {
                    project: this.$route.params.id
                })
                .then(response => {
                    console.log(response)
                })
            },
        },
        components: {
            Loader,
            ProjectCalculations,
            ProjectOffers
        }
    }
</script>