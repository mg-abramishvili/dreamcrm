<template>
    <div class="d-flex align-items-center">
        <select v-model="status" class="form-select">
            <option value="new">Новое</option>
            <option value="waiting_for_feedback">Ждет отзыва клиента</option>
            <option value="svarka">Сварка</option>
            <option value="warehouse">Склад</option>
            <option value="building">Сборка</option>
        </select>
        <button @click="save()" class="btn btn-sm btn-outline-primary">OK</button>
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
                })
            }
        }
    }
</script>
