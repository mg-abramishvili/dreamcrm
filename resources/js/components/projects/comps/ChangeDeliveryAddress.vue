<template>
    <div class="offcanvas offcanvas-end show" tabindex="-1" id="offcanvasRight" role="dialog">
        <div class="offcanvas-header">
            <h4 id="offcanvasRightLabel" class="m-0">Адрес установки</h4>
            <button @click="$parent.closeOffcanvas()" type="button" class="btn-close text-reset"></button>
        </div>
        <div class="offcanvas-body">
            <div>
                <input v-model="delivery_address" type="text" class="form-control mb-2">
                
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
                delivery_address: '',
            }
        },
        created() {
            this.delivery_address = this.project.delivery_address
        },
        methods: {
            save() {
                axios.put(`/api/project/${this.project.id}/update`, {
                    delivery_address: this.delivery_address
                })
                .then(response => {
                    this.$parent.loadProject()
                    this.$parent.closeOffcanvas()
                })
            }
        }
    }
</script>