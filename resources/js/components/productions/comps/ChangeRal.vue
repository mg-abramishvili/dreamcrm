<template>
    <div class="offcanvas offcanvas-end show" tabindex="-1" id="offcanvasRight" role="dialog">
        <div class="offcanvas-header">
            <h4 id="offcanvasRightLabel" class="m-0">Цвет (RAL)</h4>
            <button @click="$parent.closeOffcanvas()" type="button" class="btn-close text-reset"></button>
        </div>
        <div class="offcanvas-body">
            <div>
                <input v-model="ral" class="form-control mb-2" />
                <button @click="save()" class="btn btn-outline-primary">OK</button>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['production'],
        data() {
            return {
                ral: '',
            }
        },
        created() {
            this.ral = this.production.ral
        },
        methods: {
            save() {
                axios.put(`/api/production/${this.production.id}/update`, {
                    ral: this.ral
                })
                .then(response => {
                    this.$parent.loadProduction()
                    this.$parent.views.changeRal = false
                    this.$parent.views.backdrop = false
                })
            }
        }
    }
</script>
