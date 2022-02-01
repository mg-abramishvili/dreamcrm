<template>
    <div>
        <file-pond
            name="task_files[]"
            ref="task_files"
            label-idle="Выбрать файлы..."
            v-bind:allow-multiple="true"
            v-bind:allow-reorder="false"
            accepted-file-types="image/jpeg, image/png"
            :server="server"
        />
    </div>
</template>

<script>
    import vueFilePond from "vue-filepond";
    import "filepond/dist/filepond.min.css";
    import "filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.css";
    
    import FilePondPluginFileValidateType from "filepond-plugin-file-validate-type";

    const FilePond = vueFilePond(
        FilePondPluginFileValidateType,
    );

    export default {
        props: ['task'],
        data() {
            return {
                task_files: [],

                server: {
                    remove(filename, load) {
                        load('1');
                    },
                    process: (fieldName, file, metadata, load, error, progress, abort, transfer, options) => {
                        const formData = new FormData();
                        formData.append(fieldName, file, file.name);
                        formData.append('task_id', this.task.id);
                        formData.append('user_id', this.$parent.$parent.$parent.user.id);
                        const request = new XMLHttpRequest();
                        request.open('POST', '/api/file/upload');
                        request.upload.onprogress = (e) => {
                            progress(e.lengthComputable, e.loaded, e.total);
                        };
                        request.onload = () => {
                            if (request.status >= 200 && request.status < 300) {
                                load(request.responseText);
                                this.$parent.getTask()
                                this.$parent.views.openFileUpload = false
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
            };
        },
        methods: {
        },
    }
</script>