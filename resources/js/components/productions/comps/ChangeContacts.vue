<template>
    <div class="offcanvas offcanvas-end show" tabindex="-1" id="offcanvasRight" role="dialog">
        <div class="offcanvas-header">
            <h4 id="offcanvasRightLabel" class="m-0">Контакты</h4>
            <button @click="$parent.closeOffcanvas()" type="button" class="btn-close text-reset"></button>
        </div>
        <div class="offcanvas-body">
            <div>
                <input v-model="contacts" type="text" class="form-control mb-2" />
                
                <button @click="save()" class="btn btn-primary">Сохранить</button>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['production'],
        data() {
            return {
                contacts: '',
            }
        },
        created() {
            this.contacts = this.production.contacts
        },
        methods: {
            save() {
                axios.put(`/api/production/${this.production.id}/update`, {
                    contacts: this.contacts
                })
                .then(response => {
                    this.$parent.loadProduction()
                    this.$parent.closeOffcanvas()
                })
            }
        }
    }
</script>
