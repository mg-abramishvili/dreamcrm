<template>
    <div class="offcanvas offcanvas-end show" tabindex="-1" id="offcanvasRight" role="dialog">
        <div class="offcanvas-header">
            <h4 id="offcanvasRightLabel" class="m-0">Конечник</h4>
            <button @click="$parent.closeOffcanvas()" type="button" class="btn-close text-reset"></button>
        </div>
        <div class="offcanvas-body">
            <div>
                <select v-model="selected.client" class="form-select mb-2">
                    <option v-for="client in clients" :value="client.id">{{ client.name }}</option>
                </select>
                
                <button @click="save()" class="btn btn-primary">Сохранить</button>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['project'],
        data() {
            return {
                clients: [],

                selected: {
                    client: '',
                },
            }
        },
        created() {
            this.loadClients()
        },
        methods: {
            loadClients() {
                axios.get('/api/clients')
                .then(response => {
                    this.clients = response.data

                    if(this.project.endclient) {
                        this.selected.client = this.project.endclient.id
                    }
                })
            },
            save() {
                axios.put(`/api/project/${this.project.id}/update`, {
                    end_client_id: this.selected.client
                })
                .then(response => {
                    this.$parent.loadProject()
                    this.$parent.closeOffcanvas()
                })
            }
        }
    }
</script>