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
                    <a @click="selectTab('projects')" class="nav-link" :class="{'active': selected.tab == 'projects'}" role="tab">Проекты <template v-if="client.projects && client.projects.length"><span class="text-muted">({{ client.projects.length }})</span></template></a>
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
                        </div>
                    </div>
                </div>
                <div class="tab-pane" :class="{'active': selected.tab == 'projects'}" role="tabpanel">
                    <p v-if="!client.projects.length">Нет проектов с этим клиентом.</p>
                </div>
            </div>
        </div>

        <div v-if="views.backdrop" @click="closeOffcanvas()" class="offcanvas-backdrop fade show"></div>
    </div>
</template>

<script>
    import Loader from '../Loader.vue'
    import ChangeName from './comps/ChangeName'
    import ChangeRasSchet from './comps/ChangeRasSchet'

    export default {
        data() {
            return {
                client: {},

                selected: {
                    tab: 'general',
                    productionItem: '',
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
                this.views.changeProductionItem = false
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
            ChangeRasSchet
        },
    }
</script>