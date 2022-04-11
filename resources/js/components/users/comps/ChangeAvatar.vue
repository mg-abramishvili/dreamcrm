<template>
    <div class="d-flex align-items-center">
        <Loader v-if="views.loading"></Loader>

        <file-pond v-if="!views.loading"
            name="avatar"
            ref="avatar"
            label-idle="Изменить аватарку..."
            v-bind:allow-multiple="false"
            v-bind:allow-reorder="false"
            v-bind:allow-image-preview="false"
            accepted-file-types="image/jpeg, image/png"
            :server="server"
        />
    </div>
</template>

<script>
import Loader from '../../Loader.vue'

import vueFilePond from "vue-filepond";
import "filepond/dist/filepond.min.css";
import FilePondPluginFileValidateType from "filepond-plugin-file-validate-type";
const FilePond = vueFilePond( FilePondPluginFileValidateType );

export default {
    props: ['user'],
    data() {
        return {
            avatar: '',

            views: {
                loading: false,
            },

            server: {
                remove(filename, load) {
                    load('1');
                },
                process: (fieldName, file, metadata, load, error, progress, abort, transfer, options) => {
                    let el = document.querySelector('.user-page-avatar .filepond--drop-label')
                    el.style.visibility = 'visible'
                    el.style.opacity = 0
                    el.style.transform = 'none'

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
                    setTimeout(() => {
                        this.save()
                    }, 1500);
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
    methods: {
        save() {
            if(document.getElementsByName("avatar")[0]) {
                this.avatar = document.getElementsByName("avatar")[0].value
            }

            axios.put(`/api/user/${this.user.uid}/update`, {
                avatar: this.avatar
            })
            .then(response => {
                this.$parent.loadUser()
                this.avatar = ''
                this.avatar = document.getElementsByName("avatar")[0].value = ''
                
                let el = document.querySelector('.user-page-avatar .filepond--drop-label')
                el.style.visibility = 'visible'
                el.style.opacity = 1
                el.style.transform = 'none'
            })
        }
    },
    components: {
        Loader
    }
}
</script>
