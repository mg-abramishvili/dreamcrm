<template>
    <div @keyup.esc="closeModal()" class="modal fade show" tabindex="-1" style="display: block;" aria-modal="true" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Изменить доску</h5>
                    <button @click="closeModal()" type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body m-3">
                    <div class="mb-3">
                        <input v-model="name" type="text" class="form-control" placeholder="Название колонки">
                    </div>

                    <div class="row">
                        <div class="col-12 col-lg-6">
                            <button @click="update()" class="btn btn-primary">Сохранить</button>
                        </div>
                        <div class="col-12 col-lg-6 text-end">
                            <button @click="del()" class="btn btn-outline-danger">Удалить колонку</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>    
    export default {
        props: ['board'],
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
                this.name = this.board.name
            },
            closeModal() {
                this.$parent.views.modals.boardEdit = false
                this.$parent.views.modals.showBackdrop = false
                document.body.style.overflow = "auto"
            },
            update() {
                axios
                .put(`/api/tasks/board/${this.board.id}/update`, {
                    name: this.name
                })
                .then(response => (
                    this.name = '',
                    this.$parent.getBoards(),
                    this.closeModal()
                ))
            },
            del() {
                if (confirm("Точно удалить?")) {
                    axios
                    .delete(`/api/tasks/board/${this.board.id}/delete`)
                    .then(response => (
                        this.$parent.getBoards(),
                        this.closeModal()
                    ))
                }
            }
        },
    }
</script>