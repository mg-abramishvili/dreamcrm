<template>
    <div class="user-page">
        <div class="card card-bordered">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-12 col-lg-6">
                        <h1 class="h3 m-0">
                            <strong>
                                Профиль
                            </strong>
                        </h1>
                    </div>
                </div>
            </div>
        </div>

        <Loader v-if="views.loading"></Loader>

        <div v-if="!views.loading" class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-12 col-lg-8">
                        <div class="mb-4">
                            <strong class="d-block">Имя
                                <small v-if="!views.changeName" @click="views.changeName = true" class="cursor-pointer text-muted fw-normal border-bottom">изменить</small>
                                <small v-if="views.changeName" @click="views.changeName = false" class="cursor-pointer text-muted fw-normal border-bottom">изменить</small>
                            </strong>
                            
                            <span v-if="!views.changeName" class="user-title">{{ user.name }}</span>
                            
                            <ChangeName v-if="views.changeName" :user="user" />
                        </div>

                        <div class="mb-4">
                            <strong class="d-block">E-mail
                                <small v-if="!views.changeEmail" @click="views.changeEmail = true" class="cursor-pointer text-muted fw-normal border-bottom">изменить</small>
                                <small v-if="views.changeEmail" @click="views.changeEmail = false" class="cursor-pointer text-muted fw-normal border-bottom">изменить</small>
                            </strong>
                            
                            <span v-if="!views.changeEmail" class="user-title">{{ user.email }}</span>
                            
                            <ChangeEmail v-if="views.changeEmail" :user="user" />
                        </div>

                        <div class="mb-0">
                            <strong class="d-block">Изменить пароль
                                <small v-if="!views.changePassword" @click="views.changePassword = true" class="cursor-pointer text-muted fw-normal border-bottom">изменить</small>
                                <small v-if="views.changePassword" @click="views.changePassword = false" class="cursor-pointer text-muted fw-normal border-bottom">изменить</small>
                            </strong>
                            
                            <ChangePassword v-if="views.changePassword" :user="user" />
                        </div>
                    </div>
                    <div class="col-12 col-lg-4 user-page-avatar">
                        <img v-if="user.avatar" :src="user.avatar" />
                        <img v-else src="/img/no-image.jpg" />

                        <ChangeAvatar :user="user" />
                    </div>
                </div>
            </div>
        </div>

        <div v-if="!views.loading" class="card">
            <div class="card-body">
                <p class="d-flex align-items-center my-2">
                    <strong class="me-2">Макс. скидка:</strong>
                    <span>{{ user.max_discount }}%</span>
                    <small v-if="$parent.$parent.user.id === 1" @click="changePanel('discount')" class="cursor-pointer text-muted fw-normal ms-2" style="opacity: 0.5;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="d-block feather feather-edit align-middle me-2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                    </small>
                    <ChangeDiscount v-if="views.changePanel == 'discount'" :user="user" />
                </p>
            </div>
        </div>

        <!-- <div v-if="!views.loading" class="card">
            <div class="card-body">
                <ChangeSettings :user="user" />
            </div>
        </div> -->

        <div v-if="views.backdrop" @click="closeOffcanvas()" class="offcanvas-backdrop fade show"></div>
    </div>
</template>

<script>
    import Loader from '../Loader.vue'

    import ChangeName from './comps/ChangeName'
    import ChangeEmail from './comps/ChangeEmail'
    import ChangePassword from './comps/ChangePassword'
    import ChangeAvatar from './comps/ChangeAvatar'
    import ChangeSettings from './comps/ChangeSettings'
    import ChangeDiscount from './comps/ChangeDiscount'

    export default {
        data() {
            return {
                user: {},

                views: {
                    loading: true,
                    backdrop: false,
                    changeName: false,
                    changeEmail: false,
                    changePassword: false,
                    changePanel: '',
                }
            }
        },
        created() {
            this.loadUser()
        },
        methods: {
            loadUser() {
                axios.get(`/api/user/${this.$route.params.uid}`)
                .then((response => {
                    this.user = response.data

                    this.views.loading = false
                }))
            },
            changePanel(panel) {
                this.views.backdrop = true
                this.views.changePanel = panel
            },
            closeOffcanvas() {
                this.views.backdrop = false
                this.views.changePanel = ''
            },
        },
        components: {
            Loader,
            ChangeName,
            ChangeEmail,
            ChangePassword,
            ChangeAvatar,
            ChangeSettings,
            ChangeDiscount,
        }
    }
</script>