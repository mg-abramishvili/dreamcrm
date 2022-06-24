<template>
    <div class="clients-page">
        <div class="card card-bordered">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-12 col-lg-6">
                        <h1 class="h3 m-0">
                            <router-link :to="{name: 'Clients'}" class="back-arrow">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left align-middle me-2"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                            </router-link>
                            <strong>
                                {{ client.name }}
                            </strong>
                        </h1>
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
                    <a @click="selectTab('projects')" class="nav-link" :class="{'active': selected.tab == 'projects'}" role="tab">Проекты <template v-if="projectsCounter > 0"><span class="text-muted">({{ projectsCounter }})</span></template></a>
                </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane" :class="{'active': selected.tab == 'general'}" role="tabpanel">
                    <div class="row">
                        <div class="col-12 col-lg-6">
                            <p class="d-flex align-items-center">
                                <strong class="me-2">Имя:</strong>

                                <span>{{ client.name }}</span>

                                <small @click="changePanel('name')" class="cursor-pointer text-muted fw-normal ms-2" style="opacity: 50%;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="d-block feather feather-edit align-middle me-2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                </small>

                                <ChangeName v-if="views.changePanel == 'name'" :client="client" />
                            </p>

                            <p class="d-flex align-items-center">
                                <strong class="me-2">Телефон:</strong>

                                <span>
                                    <template v-if="client.tel">
                                        {{ client.tel }}
                                    </template>
                                    <template v-if="!client.tel">
                                        &mdash;
                                    </template>
                                </span>

                                <small @click="changePanel('tel')" class="cursor-pointer text-muted fw-normal ms-2" style="opacity: 50%;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="d-block feather feather-edit align-middle me-2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                </small>

                                <ChangeTel v-if="views.changePanel == 'tel'" :client="client" />
                            </p>

                            <p class="d-flex align-items-center">
                                <strong class="me-2">E-mail:</strong>

                                <span>
                                    <template v-if="client.email">
                                        {{ client.email }}
                                    </template>
                                    <template v-if="!client.email">
                                        &mdash;
                                    </template>
                                </span>

                                <small @click="changePanel('email')" class="cursor-pointer text-muted fw-normal ms-2" style="opacity: 50%;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="d-block feather feather-edit align-middle me-2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                </small>

                                <ChangeEmail v-if="views.changePanel == 'email'" :client="client" />
                            </p>

                            <p class="d-flex align-items-center">
                                <strong class="me-2">Контактное лицо:</strong>

                                <span>
                                    <template v-if="client.kont_litso">
                                        {{ client.kont_litso }}
                                    </template>
                                    <template v-if="!client.kont_litso">
                                        &mdash;
                                    </template>
                                </span>

                                <small @click="changePanel('kontLitso')" class="cursor-pointer text-muted fw-normal ms-2" style="opacity: 50%;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="d-block feather feather-edit align-middle me-2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                </small>

                                <ChangeKontLitso v-if="views.changePanel == 'kontLitso'" :client="client" />
                            </p>

                            <p class="d-flex align-items-center">
                                <strong class="me-2">ИНН:</strong>

                                <span>
                                    <template v-if="client.inn">
                                        {{ client.inn }}
                                    </template>
                                    <template v-if="!client.inn">
                                        &mdash;
                                    </template>
                                </span>

                                <small @click="changePanel('inn')" class="cursor-pointer text-muted fw-normal ms-2" style="opacity: 50%;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="d-block feather feather-edit align-middle me-2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                </small>

                                <ChangeInn v-if="views.changePanel == 'inn'" :client="client" />
                            </p>

                            <p class="d-flex align-items-center">
                                <strong class="me-2">КПП:</strong>

                                <span>
                                    <template v-if="client.kpp">
                                        {{ client.kpp }}
                                    </template>
                                    <template v-if="!client.kpp">
                                        &mdash;
                                    </template>
                                </span>

                                <small @click="changePanel('kpp')" class="cursor-pointer text-muted fw-normal ms-2" style="opacity: 50%;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="d-block feather feather-edit align-middle me-2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                </small>

                                <ChangeKpp v-if="views.changePanel == 'kpp'" :client="client" />
                            </p>

                            <p class="d-flex align-items-center">
                                <strong class="me-2">ОГРН:</strong>

                                <span>
                                    <template v-if="client.ogrn">
                                        {{ client.ogrn }}
                                    </template>
                                    <template v-if="!client.ogrn">
                                        &mdash;
                                    </template>
                                </span>

                                <small @click="changePanel('ogrn')" class="cursor-pointer text-muted fw-normal ms-2" style="opacity: 50%;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="d-block feather feather-edit align-middle me-2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                </small>

                                <ChangeOgrn v-if="views.changePanel == 'ogrn'" :client="client" />
                            </p>

                            <p class="d-flex align-items-center">
                                <strong class="me-2">Юр. адрес:</strong>

                                <span>
                                    <template v-if="client.yur_address">
                                        {{ client.yur_address }}
                                    </template>
                                    <template v-if="!client.yur_address">
                                        &mdash;
                                    </template>
                                </span>

                                <small @click="changePanel('yurAddress')" class="cursor-pointer text-muted fw-normal ms-2" style="opacity: 50%;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="d-block feather feather-edit align-middle me-2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                </small>

                                <ChangeYurAddress v-if="views.changePanel == 'yurAddress'" :client="client" />
                            </p>

                            <p class="d-flex align-items-center">
                                <strong class="me-2">Почт. адрес:</strong>

                                <span>
                                    <template v-if="client.pocht_address">
                                        {{ client.pocht_address }}
                                    </template>
                                    <template v-if="!client.pocht_address">
                                        &mdash;
                                    </template>
                                </span>

                                <small @click="changePanel('pochtAddress')" class="cursor-pointer text-muted fw-normal ms-2" style="opacity: 50%;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="d-block feather feather-edit align-middle me-2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                </small>

                                <ChangePochtAddress v-if="views.changePanel == 'pochtAddress'" :client="client" />
                            </p>

                            <p class="d-flex align-items-center">
                                <strong class="me-2">Факт. адрес:</strong>

                                <span>
                                    <template v-if="client.fakt_address">
                                        {{ client.fakt_address }}
                                    </template>
                                    <template v-if="!client.fakt_address">
                                        &mdash;
                                    </template>
                                </span>

                                <small @click="changePanel('faktAddress')" class="cursor-pointer text-muted fw-normal ms-2" style="opacity: 50%;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="d-block feather feather-edit align-middle me-2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                </small>

                                <ChangeFaktAddress v-if="views.changePanel == 'faktAddress'" :client="client" />
                            </p>
                        </div>
                        <div class="col-12 col-lg-6">
                            <p class="d-flex align-items-center">
                                <strong class="me-2">Расчетный счет:</strong>

                                <span>
                                    <template v-if="client.ras_schet">
                                        {{ client.ras_schet }}
                                    </template>
                                    <template v-if="!client.ras_schet">
                                        &mdash;
                                    </template>
                                </span>

                                <small @click="changePanel('rasSchet')" class="cursor-pointer text-muted fw-normal ms-2" style="opacity: 50%;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="d-block feather feather-edit align-middle me-2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                </small>

                                <ChangeRasSchet v-if="views.changePanel == 'rasSchet'" :client="client" />
                            </p>

                            <p class="d-flex align-items-center">
                                <strong class="me-2">Корр. счет:</strong>

                                <span>
                                    <template v-if="client.korr_schet">
                                        {{ client.korr_schet }}
                                    </template>
                                    <template v-if="!client.korr_schet">
                                        &mdash;
                                    </template>
                                </span>

                                <small @click="changePanel('korrSchet')" class="cursor-pointer text-muted fw-normal ms-2" style="opacity: 50%;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="d-block feather feather-edit align-middle me-2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                </small>

                                <ChangeKorrSchet v-if="views.changePanel == 'korrSchet'" :client="client" />
                            </p>

                            <p class="d-flex align-items-center">
                                <strong class="me-2">БИК:</strong>

                                <span>
                                    <template v-if="client.bik">
                                        {{ client.bik }}
                                    </template>
                                    <template v-if="!client.bik">
                                        &mdash;
                                    </template>
                                </span>

                                <small @click="changePanel('bik')" class="cursor-pointer text-muted fw-normal ms-2" style="opacity: 50%;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="d-block feather feather-edit align-middle me-2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                </small>

                                <ChangeBik v-if="views.changePanel == 'bik'" :client="client" />
                            </p>

                            <p class="d-flex align-items-center">
                                <strong class="me-2">Банк:</strong>

                                <span>
                                    <template v-if="client.bank">
                                        {{ client.bank }}
                                    </template>
                                    <template v-if="!client.bank">
                                        &mdash;
                                    </template>
                                </span>

                                <small @click="changePanel('bank')" class="cursor-pointer text-muted fw-normal ms-2" style="opacity: 50%;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="d-block feather feather-edit align-middle me-2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                </small>

                                <ChangeBank v-if="views.changePanel == 'bank'" :client="client" />
                            </p>
                        </div>
                    </div>
                </div>
                <div class="tab-pane" :class="{'active': selected.tab == 'projects'}" role="tabpanel">
                    <p v-if="projectsCounter == 0">Нет проектов с этим клиентом.</p>
                    
                    <table v-else class="table">
                        <thead>
                            <tr>
                                <th>Дата</th>
                                <th>Название проекта</th>
                                <th>Роль</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="project in client.projects_as_client">
                                <td>{{ project.created_at |date }}</td>
                                <td>{{ project.name }}</td>
                                <td>клиент</td>
                            </tr>
                            <tr v-for="project in client.projects_as_end_client">
                                <td>{{ project.created_at |date }}</td>
                                <td>{{ project.name }}</td>
                                <td>конечник</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div v-if="views.backdrop" @click="closeOffcanvas()" class="offcanvas-backdrop fade show"></div>
    </div>
</template>

<script>
    import Loader from '../Loader.vue'
    import ChangeName from './comps/ChangeName'
    import ChangeTel from './comps/ChangeTel'
    import ChangeEmail from './comps/ChangeEmail'
    import ChangeKontLitso from './comps/ChangeKontLitso'
    import ChangeInn from './comps/ChangeInn'
    import ChangeKpp from './comps/ChangeKpp'
    import ChangeOgrn from './comps/ChangeOgrn'
    import ChangeYurAddress from './comps/ChangeYurAddress'
    import ChangePochtAddress from './comps/ChangePochtAddress'
    import ChangeFaktAddress from './comps/ChangeFaktAddress'
    import ChangeRasSchet from './comps/ChangeRasSchet'
    import ChangeKorrSchet from './comps/ChangeKorrSchet'
    import ChangeBik from './comps/ChangeBik'
    import ChangeBank from './comps/ChangeBank'

    export default {
        data() {
            return {
                client: {},

                selected: {
                    tab: 'general',
                },

                name: '',
                tel: '',
                email: '',
                kont_litso: '',
                yur_address: '',
                pocht_address: '',
                fakt_address: '',
                inn: '',
                kpp: '',
                ogrn: '',
                ras_schet: '',
                korr_schet: '',
                bank: '',
                bik: '',

                views: {
                    loading: true,
                    backdrop: false,
                    changePanel: '',
                }
            }
        },
        computed: {
            projectsCounter() {
                let projects_as_client = 0
                let projects_as_end_client = 0

                if(this.client.projects_as_client) {
                    projects_as_client = this.client.projects_as_client.length
                }
                if(this.client.projects_as_end_client) {
                    projects_as_end_client = this.client.projects_as_end_client.length
                }

                return projects_as_client + projects_as_end_client
            }
        },
        created() {
            this.loadClient()
        },
        methods: {
            loadClient() {
                axios.get(`/api/client/${this.$route.params.id}`)
                .then((response => {
                    this.client = response.data

                    this.name = response.data.name
                    this.tel = response.data.tel
                    this.email = response.data.email
                    this.kont_litso = response.data.kont_litso
                    this.yur_address = response.data.yur_address
                    this.pocht_address = response.data.pocht_address
                    this.fakt_address = response.data.fakt_address
                    this.inn = response.data.inn
                    this.kpp = response.data.kpp
                    this.ogrn = response.data.ogrn
                    this.ras_schet = response.data.ras_schet
                    this.korr_schet = response.data.korr_schet
                    this.bank = response.data.bank
                    this.bik = response.data.bik

                    this.views.loading = false
                }))
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
            updateClient(id) {
                if (!this.name) {
                    return this.$swal({
                        text: 'Укажите имя',
                        icon: 'error',
                    })
                }

                axios.put(`/api/client/${id}/update`,
                {
                    name: this.name,
                    tel: this.tel,
                    email: this.email,
                    kont_litso: this.kont_litso,
                    yur_address: this.yur_address,
                    pocht_address: this.pocht_address,
                    fakt_address: this.fakt_address,
                    inn: this.inn,
                    kpp: this.kpp,
                    ogrn: this.ogrn,
                    ras_schet: this.ras_schet,
                    korr_schet: this.korr_schet,
                    bank: this.bank,
                    bik: this.bik,
                })
                .then(response => (
                    this.$router.push({name: 'Clients'})
                ))
                .catch((error) => {
                    if(error.response) {
                        this.errors = []
                        for(var key in error.response.data.errors){
                            this.errors.push(key)
                        }
                    }
                })
            },
        },
        components: {
            Loader,
            ChangeName,
            ChangeTel,
            ChangeEmail,
            ChangeKontLitso,
            ChangeInn,
            ChangeKpp,
            ChangeOgrn,
            ChangeYurAddress,
            ChangePochtAddress,
            ChangeFaktAddress,
            ChangeRasSchet,
            ChangeKorrSchet,
            ChangeBik,
            ChangeBank,
        },
    }
</script>