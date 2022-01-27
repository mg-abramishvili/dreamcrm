<template>
    <div @keyup.esc="closeModal()" class="modal fade show" tabindex="-1" style="display: block;" aria-modal="true" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Новая задача</h5>
                    <button @click="closeModal()" type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body m-3">
                    <div class="mb-3">
                        <label class="form-label">Название задачи</label>
                        <input v-model="name" type="text" class="form-control" placeholder="Название задачи">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Описание задачи</label>
                        <textarea v-model="description" class="form-control" style="resize: vertical" placeholder="Описание задачи"></textarea>
                    </div>
                    <button @click="saveTask()" class="btn btn-primary">Сохранить</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>    
    export default {
        props: ['column_id'],
        data() {
            return {
                name: '',
                description: '',
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
                this.$parent.views.modals.createTask = false
                this.$parent.views.modals.showBackdrop = false
                document.body.style.overflow = "auto"
            },
            saveTask() {
                axios
                .post(`/api/tasks`, {
                    column_id: this.column_id,
                    name: this.name,
                    description: this.description,
                })
                .then(response => (
                    this.name = '',
                    this.description = '',
                    this.$parent.getColumns(),
                    this.closeModal()
                ))
            },
        },
    }
</script>
