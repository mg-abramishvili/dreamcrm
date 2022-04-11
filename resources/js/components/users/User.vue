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
    </div>
</template>

<script>
    import Loader from '../Loader.vue'

    import ChangeName from './comps/ChangeName'
    import ChangeEmail from './comps/ChangeEmail'
    import ChangePassword from './comps/ChangePassword'
    import ChangeAvatar from './comps/ChangeAvatar'

    export default {
        data() {
            return {
                user: {},

                views: {
                    loading: true,
                    changeName: false,
                    changeEmail: false,
                    changePassword: false,
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
        },
        components: {
            Loader,
            ChangeName,
            ChangeEmail,
            ChangePassword,
            ChangeAvatar
        }
    }
</script>