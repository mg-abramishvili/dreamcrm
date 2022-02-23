<template>
    <div @keyup.esc="closeModal()" class="modal fade show" tabindex="-1" style="display: block;" aria-modal="true" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Новая колонка</h5>
                    <button @click="closeModal()" type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body m-3">
                    <div class="mb-3">
                        <label class="form-label">Название колонки</label>
                        <input v-model="name" type="text" class="form-control" placeholder="Название колонки">
                    </div>
                    <button @click="saveColumn()" class="btn btn-primary">Сохранить</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>    
    export default {
        props: ['board_id'],
        data() {
            return {
                name: '',
            }
        },
        mounted() {
            document.getElementsByClassName('modal')[0].focus()
            document.body.style.overflow = "hidden"

            var modal = document.getElementsByClassName('modal')[0]
            window.onclick = () => {
                if (event.target == modal) {
                    this.closeModal()
                }
            }
        },
        methods: {
            closeModal() {
                this.$parent.views.modals.columnCreate = false
                this.$parent.views.modals.showBackdrop = false
                document.body.style.overflow = "auto"
            },
            saveColumn() {
                axios
                .post(`/api/tasks/columns`, {
                    board_id: this.board_id,
                    name: this.name
                })
                .then(response => (
                    this.name = '',
                    this.$parent.getBoard(this.board_id),
                    this.closeModal()
                ))
            },
        },
    }
</script>
