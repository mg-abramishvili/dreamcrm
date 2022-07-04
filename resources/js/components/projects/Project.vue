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
                    <div class="row">
                        <div class="col-12 col-lg-6">
                            <p class="d-flex align-items-center">
                                <strong class="me-2">Клиент:</strong>

                                <span>
                                    <template v-if="project.client">
                                        {{ project.client.name }}
                                    </template>
                                    <template v-if="!project.client">
                                        &mdash;
                                    </template>
                                </span>

                                <small @click="changePanel('client')" class="cursor-pointer text-muted fw-normal ms-2" style="opacity: 50%;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="d-block feather feather-edit align-middle me-2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                </small>

                                <ChangeClient v-if="views.changePanel == 'client'" :project="project" />
                            </p>

                            <p class="d-flex align-items-center">
                                <strong class="me-2">Конечник:</strong>

                                <span>
                                    <template v-if="project.endclient">
                                        {{ project.endclient.name }}
                                    </template>
                                    <template v-if="!project.endclient">
                                        &mdash;
                                    </template>
                                </span>

                                <small @click="changePanel('endClient')" class="cursor-pointer text-muted fw-normal ms-2" style="opacity: 50%;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="d-block feather feather-edit align-middle me-2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                </small>

                                <ChangeEndClient v-if="views.changePanel == 'endClient'" :project="project" />
                            </p>

                            <p class="d-flex align-items-center">
                                <strong class="me-2">Ответственный:</strong>

                                <span>
                                    <template v-if="project.user">
                                        {{ project.user.name }}
                                    </template>
                                    <template v-if="!project.user">
                                        &mdash;
                                    </template>
                                </span>

                                <small @click="changePanel('user')" class="cursor-pointer text-muted fw-normal ms-2" style="opacity: 50%;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="d-block feather feather-edit align-middle me-2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                </small>

                                <ChangeUser v-if="views.changePanel == 'user'" :project="project" />
                            </p>
                        </div>
                        <div class="col-12 col-lg-6">
                            <p class="d-flex align-items-center">
                                <strong class="me-2">Адрес установки:</strong>

                                <span>
                                    <template v-if="project.delivery_address">
                                        {{ project.delivery_address }}
                                    </template>
                                    <template v-if="!project.delivery_address">
                                        &mdash;
                                    </template>
                                </span>

                                <small @click="changePanel('deliveryAddress')" class="cursor-pointer text-muted fw-normal ms-2" style="opacity: 50%;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="d-block feather feather-edit align-middle me-2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                </small>

                                <ChangeDeliveryAddress v-if="views.changePanel == 'deliveryAddress'" :project="project" />
                            </p>

                            <p class="d-flex align-items-center">
                                <strong class="me-2">Срок реализации:</strong>

                                <span>
                                    <template v-if="project.end_date">
                                        {{ project.end_date }}
                                    </template>
                                    <template v-if="!project.end_date">
                                        &mdash;
                                    </template>
                                </span>

                                <small @click="changePanel('endDate')" class="cursor-pointer text-muted fw-normal ms-2" style="opacity: 50%;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="d-block feather feather-edit align-middle me-2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                </small>

                                <ChangeEndDate v-if="views.changePanel == 'endDate'" :project="project" />
                            </p>
                        </div>
                    </div>

                    <hr>

                    <p class="d-flex align-items-center">
                        <strong class="me-2">Примечания:</strong>

                        <span>
                            <template v-if="project.comment">
                                {{ project.comment }}
                            </template>
                            <template v-if="!project.comment">
                                &mdash;
                            </template>
                        </span>

                        <small @click="changePanel('comment')" class="cursor-pointer text-muted fw-normal ms-2" style="opacity: 50%;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="d-block feather feather-edit align-middle me-2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                        </small>

                        <ChangeComment v-if="views.changePanel == 'comment'" :project="project" />
                    </p>
                </div>
                <div class="tab-pane" :class="{'active': selected.tab == 'calculations'}" role="tabpanel">
                    <ProjectCalculations :project="project"></ProjectCalculations>
                </div>
                <div class="tab-pane" :class="{'active': selected.tab == 'offers'}" role="tabpanel">
                    <ProjectOffers :project_id="project.id" :offers="project.offers"></ProjectOffers>
                </div>
            </div>
        </div>

        <div v-if="views.backdrop" @click="closeOffcanvas()" class="offcanvas-backdrop fade show"></div>
    </div>
</template>

<script>
    import Loader from '../Loader.vue'
    import ProjectCalculations from './ProjectCalculations.vue'
    import ProjectOffers from './ProjectOffers.vue'
    import ChangeClient from './comps/ChangeClient.vue'
    import ChangeEndClient from './comps/ChangeEndClient.vue'
    import ChangeDeliveryAddress from './comps/ChangeDeliveryAddress.vue'
    import ChangeEndDate from './comps/ChangeEndDate.vue'
    import ChangeComment from './comps/ChangeComment.vue'

    export default {
        data() {
            return {
                project: {},

                selected: {
                    tab: 'general',
                },

                views: {
                    loading: true,
                    backdrop: false,
                    changePanel: '',
                }
            }
        },
        created() {
            this.loadProject()
        },
        methods: {
            loadProject() {
                axios.get(`/api/project/${this.$route.params.id}`)
                .then(response => (
                    this.project = response.data.data,
                    this.views.loading = false
                ))
            },
            selectTab(tab) {
                this.selected.tab = tab
            },
            changePanel(panel) {
                this.views.backdrop = true
                this.views.changePanel = panel
            },
            closeOffcanvas() {
                this.views.backdrop = false
                this.views.changePanel = ''
            },
            toProduction() {
                this.$router.push({name: 'ProductionCreate', params: {project_id: this.$route.params.id}})
            },
        },
        components: {
            Loader,
            ProjectCalculations,
            ProjectOffers,
            ChangeClient,
            ChangeEndClient,
            ChangeDeliveryAddress,
            ChangeEndDate,
            ChangeComment,
        }
    }
</script>