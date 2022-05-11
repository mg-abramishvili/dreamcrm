<template>
    <div class="offcanvas offcanvas-end show" tabindex="-1" id="offcanvasRight" role="dialog">
        <div class="offcanvas-header">
            <h4 id="offcanvasRightLabel" class="m-0">Дата начала</h4>
            <button @click="$parent.closeOffcanvas()" type="button" class="btn-close text-reset"></button>
        </div>
        <div class="offcanvas-body">
            <div>
                <input v-model="start_date" type="date" class="form-control mb-2" />

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
                start_date: '',
            }
        },
        created() {
            this.start_date = this.production.start_date
        },
        methods: {
            save() {
                axios.put(`/api/production/${this.production.id}/update`, {
                    start_date: this.start_date
                })
                .then(response => {
                    this.$parent.loadProduction()
                    this.$parent.views.changeStartDate = false
                    this.$parent.views.backdrop = false
                })
            }
        }
    }
</script>
