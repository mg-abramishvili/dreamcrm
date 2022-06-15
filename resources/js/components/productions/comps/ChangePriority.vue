<template>
    <div class="offcanvas offcanvas-end show" tabindex="-1" id="offcanvasRight" role="dialog">
        <div class="offcanvas-header">
            <h4 id="offcanvasRightLabel" class="m-0">Приоритет</h4>
            <button @click="$parent.closeOffcanvas()" type="button" class="btn-close text-reset"></button>
        </div>
        <div class="offcanvas-body">
            <div>
                <select v-model="priority" class="form-select mb-2">
                    <option value="normal">Нормальный</option>
                    <option value="high">Высокий</option>
                    <option value="urgent">Срочный</option>
                </select>
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
                priority: '',
            }
        },
        created() {
            this.priority = this.production.priority
        },
        methods: {
            save() {
                axios.put(`/api/production/${this.production.id}/update`, {
                    priority: this.priority
                })
                .then(response => {
                    this.$parent.loadProduction()
                    this.$parent.closeOffcanvas()
                })
            }
        }
    }
</script>
