<template>
    <div @keyup.esc="closeModal()" class="modal fade show" tabindex="-1" style="display: block;" aria-modal="true" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Изменить колонку</h5>
                    <button @click="closeModal()" type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body m-3">
                    <div class="mb-3">
                        <input v-model="name" type="text" class="form-control" placeholder="Название колонки">
                    </div>

                    <div class="row">
                        <div class="col-12 col-lg-6">
                            <button @click="updateColumn()" class="btn btn-primary">Сохранить</button>
                        </div>
                        <div class="col-12 col-lg-6 text-end">
                            <button @click="deleteColumn()" class="btn btn-outline-danger">Удалить колонку</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>    
    export default {
        props: ['column'],
        data() {
            return {
                //
            }
        },
        mounted() {
            document.getElementsByClassName('modal')[0].focus()
            document.body.style.overflow = "hidden"
        },
        created() {
            this.getName()
        },
        methods: {
            getName() {
                this.name = this.column.name
            },
            closeModal() {
                this.$parent.views.modals.changeColumnName = false
                this.$parent.views.modals.showBackdrop = false
                document.body.style.overflow = "auto"
            },
            updateColumn() {
                axios
                .put(`/api/tasks/column/${this.column.id}/rename`, {
                    name: this.name
                })
                .then(response => (
                    this.name = '',
                    this.$parent.getColumns(),
                    this.closeModal()
                ))
            },
            deleteColumn() {
                if (confirm("Точно удалить?")) {
                    axios
                    .delete(`/api/tasks/column/${this.column.id}/delete`)
                    .then(response => (
                        this.$parent.getColumns(),
                        this.closeModal()
                    ))
                }
            }
        },
    }
</script>