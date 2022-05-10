<template>
    <div class="offcanvas offcanvas-end show" tabindex="-1" id="offcanvasRight" role="dialog">
        <div class="offcanvas-header">
            <h4 id="offcanvasRightLabel" class="m-0">Статус</h4>
            <button @click="$parent.closeOffcanvas()" type="button" class="btn-close text-reset"></button>
        </div>
        <div class="offcanvas-body">
            <div>
                <select v-model="status" class="form-select mb-2">
                    <option value="new">Новое</option>
                    <option value="waiting_for_feedback">Ждет отзыва клиента</option>
                    <option value="svarka">Сварка</option>
                    <option value="warehouse">Склад</option>
                    <option value="building">Сборка</option>
                </select>
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
                status: '',
            }
        },
        created() {
            this.status = this.production.status
        },
        methods: {
            save() {
                axios.put(`/api/production/${this.production.id}/update`, {
                    status: this.status
                })
                .then(response => {
                    this.$parent.loadProduction()
                    this.$parent.views.changeStatus = false
                    this.$parent.views.backdrop = false
                })
            }
        }
    }
</script>
