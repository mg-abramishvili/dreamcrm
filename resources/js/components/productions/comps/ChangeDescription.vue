<template>
    <div class="offcanvas offcanvas-end show" tabindex="-1" id="offcanvasRight" role="dialog">
        <div class="offcanvas-header">
            <h4 id="offcanvasRightLabel" class="m-0">Описание</h4>
            <button @click="$parent.closeOffcanvas()" type="button" class="btn-close text-reset"></button>
        </div>
        <div class="offcanvas-body">
            <div>
                <textarea v-model="description" class="form-control mb-2"></textarea>
                
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
                description: '',
            }
        },
        created() {
            this.description = this.production.description
        },
        methods: {
            save() {
                axios.put(`/api/production/${this.production.id}/update`, {
                    description: this.description
                })
                .then(response => {
                    this.$parent.loadProduction()
                    this.$parent.closeOffcanvas()
                })
            }
        }
    }
</script>
