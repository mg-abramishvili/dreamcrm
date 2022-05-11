<template>
    <div class="offcanvas offcanvas-end show" tabindex="-1" id="offcanvasRight" role="dialog">
        <div class="offcanvas-header">
            <h4 id="offcanvasRightLabel" class="m-0">Срок завершения</h4>
            <button @click="$parent.closeOffcanvas()" type="button" class="btn-close text-reset"></button>
        </div>
        <div class="offcanvas-body">
            <div>
                <input v-model="end_date" type="date" class="form-control mb-2" />

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
                end_date: '',
            }
        },
        created() {
            this.end_date = this.production.end_date
        },
        methods: {
            save() {
                axios.put(`/api/production/${this.production.id}/update`, {
                    end_date: this.end_date
                })
                .then(response => {
                    this.$parent.loadProduction()
                    this.$parent.views.changeEndDate = false
                    this.$parent.views.backdrop = false
                })
            }
        }
    }
</script>
