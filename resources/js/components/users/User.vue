<template>
    <div v-if="user && user.id && user.id > 0" class="user-page">
        <div class="row align-items-center mb-4">
            <div class="col-12 col-lg-6">
                <h1 class="h3 m-0">
                    <strong>
                        {{ user.name }}
                    </strong>
                </h1>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div v-if="errors && errors.length > 0" class="alert alert-danger">
                    <div class="alert-message">
                        <strong v-for="(error, index) in errors" :key="'error_' + index" class="d-block">
                            {{ error }}
                        </strong>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 col-lg-8">
                        <div class="mb-3">
                            <label>Имя</label>
                            <input v-model="name" type="text" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label>E-mail</label>
                            <input v-model="email" type="email" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label>Изменить пароль (введите новый)</label>
                            <input v-model="password" type="password" class="form-control">
                        </div>
                    </div>
                    <div class="col-12 col-lg-4 user-page-avatar">
                        <file-pond
                            name="avatar"
                            ref="avatar"
                            label-idle="Выбрать аватарку..."
                            v-bind:allow-multiple="false"
                            v-bind:allow-reorder="false"
                            accepted-file-types="image/jpeg, image/png"
                            :server="server"
                            v-bind:files="filepond_avatar_edit"
                        />
                    </div>
                </div>

                <button @click="updateUser(user.id)" class="btn btn-primary">Сохранить</button>
            </div>
        </div>
    </div>
    <div v-else>
        <div class="spinner-border text-primary me-2" role="status">
            <span class="sr-only">Загрузка...</span>
        </div>
    </div>
</template>

<script>
    import vueFilePond from "vue-filepond";
    import "filepond/dist/filepond.min.css";
    import "filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.css";
    import FilePondPluginFileValidateType from "filepond-plugin-file-validate-type";
    import FilePondPluginImagePreview from "filepond-plugin-image-preview";
    const FilePond = vueFilePond(
        FilePondPluginFileValidateType,
        FilePondPluginImagePreview
    );

    export default {
        data() {
            return {
                user: {},

                name: '',
                email: '',
                password: '',
                avatar: '',

                filepond_avatar: [],
                filepond_avatar_edit: [],

                errors: [],

                server: {
                    remove(filename, load) {
                        load('1');
                    },
                    process: (fieldName, file, metadata, load, error, progress, abort, transfer, options) => {
                        const formData = new FormData();
                        formData.append(fieldName, file, file.name);
                        const request = new XMLHttpRequest();
                        request.open('POST', '/api/file/upload');
                        request.upload.onprogress = (e) => {
                            progress(e.lengthComputable, e.loaded, e.total);
                        };
                        request.onload = function() {
                            if (request.status >= 200 && request.status < 300) {
                                load(request.responseText);
                            }
                            else {
                                error('oh no');
                            }
                        };
                        request.send(formData);
                        return {
                            abort: () => {
                                request.abort();
                                abort();
                            }
                        };
                    },
                    revert: (filename, load) => {
                        load(filename)
                    },
                    load: (source, load, error, progress, abort, headers) => {
                        var myRequest = new Request(source);
                        fetch(myRequest).then(function(response) {
                            response.blob().then(function(myBlob) {
                                load(myBlob)
                            });
                        });
                    },
                },
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

                        this.name = response.data.name
                        this.email = response.data.email

                        if(response.data.avatar) {
                            this.filepond_avatar_edit = [
                                {
                                    source: response.data.avatar,
                                    options: {
                                        type: 'local',
                                    }
                                }
                            ]
                        }
                    }))
            },
            updateUser(id) {
                this.errors = []

                if (!this.name) {
                    this.errors.push('Укажите имя');
                }
                if (!this.email) {
                    this.errors.push('Укажите E-mail');
                }

                if(this.errors && this.errors.length > 0) {
                    return
                }

                if(document.getElementsByName("avatar")[0]) {
                    this.avatar = document.getElementsByName("avatar")[0].value
                }

                axios.put(`/api/user/${id}/update`,
                {
                    name: this.name,
                    email: this.email,
                    password: this.password,
                    avatar: this.avatar
                })
                .then(response => (
                    window.location.href = '/tasks'
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
    }
</script>